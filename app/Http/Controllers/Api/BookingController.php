<?php

// 当初名前空間が App\Http\Controllers　になっていた。 500のステータスコードが返ったため laravel.logを確認
// class名が重複しているという旨のエラーメッセージだったが、namespaceの指定が誤っている事から起因するエラーだった。
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Room;
use App\Models\Participant;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // 予約一覧の取得
    public function index()
    {
        return Booking::with(['participants', 'status', 'room'])->get();
    }

    // 予約を作成
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'room_id'                   => 'required|exists:rooms,id',       // 外部キーとして roomsに存在するIDか確認
            'start_time'                => 'required|date',                  // date形式か
            'end_time'                  => 'required|date|after:start_time', // start_timeより後の日付か確認
            'user_id'                   => 'required|exists:users,id',       // 申請者のユーザーIDがusersテーブルに存在するか
            'participants'              => 'required|array|min:1',           // 配列で、最小1つの要素が必要
            'participants.*.user_id'    => 'required|exists:users,id',       // 参加者のユーザーIDがusersテーブルに存在するか
            'event_title'               => 'required|max:30'
        ]);

        // トランザクション処理
        try {
            $booking = DB::transaction(function () use ($request) {
                // 予約の作成
                $booking = Booking::create([
                    'room_id'       => $request->room_id,
                    'start_time'    => $request->start_time,
                    'end_time'      => $request->end_time,
                    'status_id'     => 1, //初期ステータス（承認待ち）
                    'user_id'       => $request->user_id,
                ]);
    
                // 参加者の作成
                foreach ($request->participants as $participant) {
                    Participant::create([
                        'booking_id'    => $booking->id,
                        'user_id'       => $participant['user_id'], 
                    ]);
                }

                // カレンダーイベントの作成
                CalendarEvent::create([
                    'booking_id'    => $booking->id,
                    'event_title'   => $request->event_title,
                    'event_start'   => $booking->start_time,
                    'event_end'     => $booking->end_time,
                ]);
    
                return $booking;
            });
    
            return response()->json(['message' => 'Booking created successfully!', 'booking' => $booking], 201);
        } catch (\Exception $e) {
            // トランザクション内で例外が発生した場合にエラーレスポンスを返す
            return response()->json(['message' => 'Booking creation failed!', 'error' => $e->getMessage()], 500);
        }
    }

    // 予約の詳細を取得
    public function show($id)
    {
        $booking = Booking::with(['status', 'participants'])->findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'room_id'                => 'sometimes|required|exists:rooms,id',
            'start_time'             => 'sometimes|required|date',
            'end_time'               => 'sometimes|required|date|after:start_time',
            'status_id'              => 'sometimes|required|exists:booking_statuses,id',
            'participants'           => 'sometimes|array|min:1',
            'participants.*.user_id' => 'exists:users,id',
            'event_title'            => 'required|max:30',
        ]);
    
        // トランザクション処理
        try {
            $booking = Booking::findOrFail($id);
            
            // 変更前のデータを保持
            $status_id_before   = $booking->status_id;
            $start_time_before  = $booking->start_time;
            $end_time_before    = $booking->end_time;
    
            DB::transaction(function () use ($request, $booking, $status_id_before, $start_time_before, $end_time_before) {
                // 予約情報の更新
                $booking->update($request->only(['room_id', 'start_time', 'end_time', 'status_id', 'event_title']));
                $status_id_after    = $booking->status_id;
                $start_time_after   = $booking->start_time;
                $end_time_after     = $booking->end_time;
    
                // 参加者の更新処理
                if (isset($request->participants)) {
                    // 現在の参加者IDを取得
                    $existingParticipants = $booking->participants()->pluck('user_id')->toArray();
                    $newParticipants = array_column($request->participants, 'user_id');
    
                    // 削除する参加者を特定
                    $participantsToRemove = array_diff($existingParticipants, $newParticipants);
                    // 追加する参加者を特定
                    $participantsToAdd = array_diff($newParticipants, $existingParticipants);
    
                    // 参加者の削除
                    if (!empty($participantsToRemove)) {
                        Participant::where('booking_id', $booking->id)
                            ->whereIn('user_id', $participantsToRemove)
                            ->delete();
                    }
    
                    // 新しい参加者の追加
                    foreach ($participantsToAdd as $user_id) {
                        Participant::create([
                            'booking_id'    => $booking->id,
                            'user_id'       => $user_id,
                        ]);
                    }
                }
    
                // statusの更新が発生した場合、booking_historiesテーブルに記録
                if ($status_id_before !== $status_id_after) {
                    BookingHistory::create([
                        'booking_id'    => $booking->id,
                        'status_before' => $status_id_before,
                        'status_after'  => $status_id_after,
                    ]);
                }
    
                // 予約期間やタイトルが変更された場合、カレンダーイベントを更新
                $calendarEvent = CalendarEvent::where('booking_id', $booking->id)->first();
                if ($start_time_before !== $start_time_after || $end_time_before !== $end_time_after || $request->event_title !== $booking->event_title) {
                    if ($calendarEvent) {
                        // 既存のカレンダーイベントを更新
                        $calendarEvent->update([
                            'event_start'   => $start_time_after,
                            'event_end'     => $end_time_after,
                            'event_title'   => $request->event_title,
                        ]);
                    } else {
                        // カレンダーイベントが存在しない場合、新規作成
                        CalendarEvent::create([
                            'booking_id'    => $booking->id,
                            'event_start'   => $start_time_after,
                            'event_end'     => $end_time_after,
                            'event_title'   => $request->event_title,
                        ]);
                    }
                }
            });
    
            return response()->json(['message' => 'Booking updated successfully!', 'booking' => $booking], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking update failed!', 'error' => $e->getMessage()], 500);
        }
    }

    // 予約を削除
    public function destroy($id)
    {
        // トランザクション処理
        try {
            $booking = Booking::findOrFail($id);
            DB::transaction(function () use ($booking) {
                $booking->participants()->delete();
                $booking->delete();
            });

            return response()->json(['message' => 'Booking deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'Booking deletion failed!', 'error' => $e->getMessage()],500);
        }
    }
}
