<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use App\Services\EventService;
use Illuminate\Database\Query\JoinClause;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today();

        $reservedPeople = DB::table('reservations')
        ->selectRaw('event_id, sum(number_of_people) as number_of_people')
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        // $reservedPeople = DB::table('reservations')
        // ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
        // ->groupBy('event_id');

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function(JoinClause $join) {
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })
        ->whereDate('start_date', '>=', $today)
        ->orderBy('start_date', 'asc')
        ->paginate(10);

        return Inertia::render('Manager/Events/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Manager/Events/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $check = EventService::checkEventDuplication($request['event_date'], $request['start_time'], $request['end_time']);

        if($check) {
            return to_route('events.create')->with([
                'message' => 'この時間帯はすでに他の予約が存在します',
                'status' => 'danger',
            ]);
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        Event::create([
            'name' => $request['event_name'],
            'information' => $request['information'],
            'max_people' => $request['max_people'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_visible' => $request['is_visible'],
        ]);

        return to_route('events.index')->with([
            'message' => '登録しました',
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event = Event::findOrFail($event->id);
        $users = $event->users;

        $reservations = [];

        foreach($users as $user)
        {
            $reservedInfo = [
                'id' => $user->id,
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date
            ];

            array_push($reservations, $reservedInfo);
        }

        // dd($reservations);

        // $eventData = $event->toArray();
        $event_date = $event->event_date;
        $start_time = $event->start_time;
        $end_time = $event->end_time;
        // dd($event, $event_date, $start_time, $end_time);
        return Inertia::render('Manager/Events/Show', [
            'event' => $event,
            'users' => $users,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'reservations' => $reservations
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $event = Event::findOrFail($event->id);

        $now = Carbon::now()->format('Y-m-d H:i');
        $eventDateTime = $event->edit_event_date . ' ' . $event->edit_start_time;

        if ($eventDateTime < $now) {
            return abort(404);
        }


        $event_date = $event->edit_event_date;
        $start_time = $event->edit_start_time;
        $end_time = $event->edit_end_time;
        return Inertia::render('Manager/Events/Edit', [
            'event' => $event,
            'event_date' => $event_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $check = EventService::checkEditEventDuplication($event->id, $request['event_date'], $request['start_time'], $request['end_time']);

        if($check) {
            return to_route('events.edit', ['event' => $event->id])->with([
                'message' => 'この時間帯はすでに他の予約が存在します',
                'status' => 'danger',
            ]);
        }

        $startDate = EventService::joinDateAndTime($request['event_date'], $request['start_time']);
        $endDate = EventService::joinDateAndTime($request['event_date'], $request['end_time']);

        $event->name = $request['event_name'];
        $event->information = $request['information'];
        $event->max_people = $request['max_people'];
        $event->start_date = $startDate;
        $event->end_date = $endDate;
        $event->is_visible = $request['is_visible'];

        $event->save();

        return to_route('events.show', ['event' => $event->id])->with([
            'message' => '更新しました',
            'status' => 'success',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }

    public function past()
    {
        $today = Carbon::today();

        $reservedPeople = DB::table('reservations')
        ->selectRaw('event_id, sum(number_of_people) as number_of_people')
        ->whereNull('canceled_date')
        ->groupBy('event_id');

        $events = DB::table('events')
        ->leftJoinSub($reservedPeople, 'reservedPeople', function(JoinClause $join) {
            $join->on('events.id', '=', 'reservedPeople.event_id');
        })
        ->whereDate('start_date', '<', $today)
        ->orderBy('start_date', 'desc')
        ->paginate(10);

        return Inertia::render('Manager/Events/Past', [
            'events' => $events,
        ]);
    }
}
