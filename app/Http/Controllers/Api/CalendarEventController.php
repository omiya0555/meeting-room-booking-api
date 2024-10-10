<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use Carbon\Carbon;

class CalendarEventController extends Controller
{

    public function getEvents(Request $request)
    {
        try {
            $start = Carbon::parse($request->query('start'));
            $end = Carbon::parse($request->query('end'));
    
            $events = CalendarEvent::whereBetween('event_start', [$start, $end])
                            ->orWhereBetween('event_end', [$start, $end])
                            ->get();
            $formattedEvents = $events->map(function ($event) {
                return [
                    'title' => $event->event_title,
                    'start' => $event->event_start,
                    'end' => $event->event_end,
                ];
            });
    
            return response()->json($formattedEvents);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch events'], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CalendarEvent::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $calendarEvent = CalendarEvent::findOrFail($id);
        return response()->json($calendarEvent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // 月ごとのイベント情報を返す
    public function getEventsByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m'
        ]);

        // 月初と月末を設定
        $month = $request->month;
        // carbonライブラリのcreateFromFormatで$monthをY-m形式にパースし、
        // startOfMonthメソッドでその月の月初日を取得。指定月の１日の日付オブジェクトが格納
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        // $startDateをコピーし、その月の月末日を取得。copy()でオブジェクトの複製。
        $endDate = $startDate->copy()->endOfMonth();

        // 月内のイベントを取得
        // start_dateが$startDateとendDateの間にあるイベントを取得
        // end_date  が$startDateとendDateの間にあるイベントを取得
        $events = CalendarEvent::whereBetween('event_start', [$startDate, $endDate])
                               ->orWhereBetween('event_end', [$startDate, $endDate])
                               ->get();

        return response()->json($events);
    }
}
