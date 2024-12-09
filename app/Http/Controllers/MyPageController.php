<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\MyPageService;
use Carbon\Carbon;

class MyPageController extends Controller
{
    public function index() {
        $user = User::findOrFail(Auth::id());
        $events = $user->events;
        $fromTodayEvents = MyPageService::reservedEvent($events, 'fromToday');
        $pastEvents = MyPageService::reservedEvent($events, 'past');

        return Inertia::render('MyPage/Index', [
            'fromTodayEvents' => $fromTodayEvents,
            'pastEvents' => $pastEvents
        ]);
    }

    public function show($id) {
        $event = Event::findOrFail($id);
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        $event_date = $event->event_date;
        $start_time = $event->start_time;
        $end_time = $event->end_time;

        return Inertia::render('MyPage/Show', [
            'event' => $event,
            'reservation' => $reservation,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
    }

    public function cancel($id) {
        $reservation = Reservation::where('user_id', '=', Auth::id())
        ->where('event_id', '=', $id)
        ->latest()
        ->first();

        $reservation->canceled_date = Carbon::now()->format('Y-m-d H:i:s');
        $reservation->save();

        return to_route('dashboard')->with([
            'message' => 'キャンセルしました',
            'status' => 'success',
        ]);
    }
}
