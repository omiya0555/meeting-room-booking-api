<?php

// 当初名前空間が App\Http\Controllers　になっていた。 500のステータスコードが返ったため laravel.logを確認
// class名が重複しているという旨のエラーメッセージだったが、namespaceの指定が誤っている事から起因するエラーだった。
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // 予約一覧の取得
    public function index()
    {
        return Booking::with(['participants', 'status'])->get();
    }

    // 新しい予約を作成
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_id' => 'required|exists:users,id',
            'participants' => 'required|array|min:1',
            'participants.*.user_id' => 'exists:users,id',
        ]);

        // トランザクション処理
        try {
            $booking = DB::transaction(function () use ($request) {
                // 予約の作成
                $booking = Booking::create([
                    'room_id' => $request->room_id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'status_id' => 1,
                    'user_id' => $request->user_id,
                ]);

                // 参加者の追加
                foreach ($request->participants as $participant) {
                    Participant::create([
                        'booking_id' => $booking->id,
                        'user_id' => $participant['user_id'], // 修正：user_idを取得
                    ]);
                }

                return $booking;
            });

            return response()->json(['message' => 'Booking created successfully!', 'booking' => $booking], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Booking creation failed!', 'error' => $e->getMessage()], 500);
        }
    }

    // 予約の詳細を取得
    public function show($id)
    {
        $booking = Booking::with(['status', 'participants'])->findOrFail($id);
        return response()->json($booking);
    }

    // 予約を更新
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'room_id' => 'sometimes|required|exists:rooms,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'status_id' => 'sometimes|required|exists:booking_statuses,id',
            'participants' => 'sometimes|array|min:1',
            'participants.*.user_id' => 'exists:users,id',
        ]);

        // トランザクション処理
        try {
            $booking = Booking::findOrFail($id);
            DB::transaction(function () use ($request, $booking) {
                $booking->update($request->only(['room_id', 'start_time', 'end_time', 'status_id']));

                // 参加者の更新
                if (isset($request->participants)) {
                    // 参加者の削除
                    $booking->participants()->delete();

                    // 新しい参加者の追加
                    foreach ($request->participants as $participant) {
                        Participant::create([
                            'booking_id' => $booking->id,
                            'user_id' => $participant['user_id'],
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
            return response()->json(['message' => 'Booking deletion failed!', 'error' => $e->getMessage()], 500);
        }
    }
}
