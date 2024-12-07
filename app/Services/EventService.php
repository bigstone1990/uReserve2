<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Query\JoinClause;

class EventService
{
    public static function checkEventDuplication($eventDate, $startTime, $endTime)
    {
      return DB::table('events')
      ->whereDate('start_date', $eventDate)
      ->whereTime('end_date', '>', $startTime)
      ->whereTime('start_date', '<', $endTime)
      ->exists();
    }

    public static function checkEditEventDuplication($eventId, $eventDate, $startTime, $endTime)
    {
      return DB::table('events')
      ->where('id', '<>', $eventId)
      ->whereDate('start_date', $eventDate)
      ->whereTime('end_date', '>', $startTime)
      ->whereTime('start_date', '<', $endTime)
      ->exists();
    }

    public static function joinDateAndTime($date, $time)
    {
        $join = $date . " " . $time;
        return Carbon::createFromFormat('Y-m-d H:i', $join);
    }

    public static function getWeekEvents($startDate, $endDate) {
      $reservedPeople = DB::table('reservations')
      ->selectRaw('event_id, sum(number_of_people) as number_of_people')
      ->whereNull('canceled_date')
      ->groupBy('event_id');

      return DB::table('events')
      ->leftJoinSub($reservedPeople, 'reservedPeople', function(JoinClause $join) {
          $join->on('events.id', '=', 'reservedPeople.event_id');
      })
      ->whereBetween('start_date', [$startDate, $endDate])
      ->orderBy('start_date', 'asc')
      ->get();
    }
}
