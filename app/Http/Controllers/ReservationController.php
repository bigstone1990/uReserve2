<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class ReservationController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $event_date = $event->event_date;
        $start_time = $event->start_time;
        $end_time = $event->end_time;

        $reservedPeople = DB::table('reservations')
        ->selectRaw('event_id, sum(number_of_people) as number_of_people')
        ->whereNull('canceled_date')
        ->groupBy('event_id')
        ->having('event_id', $event->id)
        ->first();

        if(!is_null($reservedPeople)) {
            $reservable_people = $event->max_people - $reservedPeople->number_of_people;
        }else{
            $reservable_people = $event->max_people;
        }

        return Inertia::render('EventDetail', [
            'event' => $event,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'reservable_people' => $reservable_people
        ]);
    }

    public function reserve(Request $request)
    {
        $event = Event::findOrFail($request->event_id);

        $reservedPeople = DB::table('reservations')
        ->selectRaw('event_id, sum(number_of_people) as number_of_people')
        ->whereNull('canceled_date')
        ->groupBy('event_id')
        ->having('event_id', $event->id)
        ->first();

        if (is_null($reservedPeople) || $event->max_people >= $reservedPeople->number_of_people + $request->reserve_people) {
            Reservation::create([
                'user_id' => Auth::id(),
                'event_id' => $request['event_id'],
                'number_of_people' => $request['reserve_people'],
            ]);
    
            return to_route('dashboard')->with([
                'message' => '登録しました',
                'status' => 'success',
            ]);
        }
        else {
            return to_route('events.detail')->with([
                'message' => 'この人数は予約できません',
                'status' => 'danger',
            ]);
        }
    }
}
