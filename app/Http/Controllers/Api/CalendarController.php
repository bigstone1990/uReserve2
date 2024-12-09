<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\EventService;
use App\Constants\EventConst;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date.' 00:00:00';
        $end_date = Carbon::parse($start_date)->addDays(6)->format('Y-m-d 23:59:59');
        $week_calendar = [];
        $check_day = [];
        $day_name = [];

        for ($i = 0; $i < 7; $i++)
        {
            array_push($week_calendar, Carbon::parse($start_date)->addDays($i)->format('m月d日'));
            array_push($check_day, Carbon::parse($start_date)->addDays($i)->format('Y-m-d'));
            array_push($day_name, Carbon::parse($start_date)->addDays($i)->shortDayName);
        }

        $events = EventService::getWeekEvents($start_date, $end_date);
        $day_of_event = [];
        $week_of_event = [];

        if ($events->isNotEmpty()) {
            for ($i = 0; $i < count($check_day); $i++) {
                for ($j = 0; $j < count(EventConst::EVENT_TIME); $j++) {
                    if (!is_null($events->firstWhere('start_date', $check_day[$i]. ' '. EventConst::EVENT_TIME[$j]))) {
                        $event = $events->firstWhere('start_date', $check_day[$i]. ' '. EventConst::EVENT_TIME[$j]);
                        
                        if ($j == 0) {
                            $event_info = [
                                'index' => 0,
                                'id' => $event->id,
                                'name' => $event->name,
                                'add_blocks' => Carbon::parse($event->start_date)->diffInMinutes($event->end_date) / 30 - 1,
                                'reserved' => true,
                            ];

                            $day_of_event[0] = $event_info;
                        }
                        else {
                            // Log::debug('50');
                            // Log::debug($j);
                            // Log::debug($day_of_event);
                            if ($day_of_event[$j - 1]['add_blocks'] == null || $day_of_event[$j - 1]['add_blocks'] == 0) {
                                $event_info = [
                                    'index' => $j,
                                    'id' => $event->id,
                                    'name' => $event->name,
                                    'add_blocks' => Carbon::parse($event->start_date)->diffInMinutes($event->end_date) / 30 - 1,
                                    'reserved' => true,
                                ];

                                $day_of_event[$j] = $event_info;
                            }
                            else {
                                for ($count = 0; $count < $day_of_event[$j-1]['add_blocks']; $count++) {
                                    $event_info = [
                                        'index' => $j + $count,
                                        'id' => null,
                                        'name' => null,
                                        'add_blocks' => 0,
                                        'reserved' => true,
                                    ];

                                    $day_of_event[$j + $count] = $event_info;
                                }
                                // Log::debug('74');
                                // Log::debug($j);
                                // Log::debug($day_of_event);
                                $j += $day_of_event[$j-1]['add_blocks'] - 1;
                                // Log::debug('78');
                                // Log::debug($j);
                            }
                        }
                    }
                    else {
                        if ($j == 0) {
                            $event_info = [
                                'index' => 0,
                                'id' => null,
                                'name' => null,
                                'add_blocks' => 0,
                                'reserved' => false,
                            ];

                            $day_of_event[0] = $event_info;
                        }
                        else {
                            // Log::debug('90');
                            // Log::debug($j);
                            // Log::debug($day_of_event);
                            if ($day_of_event[$j - 1]['add_blocks'] == null || $day_of_event[$j - 1]['add_blocks'] == 0) {
                                $event_info = [
                                    'index' => $j,
                                    'id' => null,
                                    'name' => null,
                                    'add_blocks' => 0,
                                    'reserved' => false,
                                ];

                                $day_of_event[$j] = $event_info;
                            }
                            else {
                                for ($count = 0; $count < $day_of_event[$j-1]['add_blocks']; $count++) {
                                    $event_info = [
                                        'index' => $j + $count,
                                        'id' => null,
                                        'name' => null,
                                        'add_blocks' => 0,
                                        'reserved' => true,
                                    ];

                                    $day_of_event[$j + $count] = $event_info;
                                }
                                // Log::debug('119');
                                // Log::debug($j);
                                // Log::debug($day_of_event);
                                $j += $day_of_event[$j-1]['add_blocks'] - 1;
                                // Log::debug('123');
                                // Log::debug($j);
                            }
                        }
                    }
                }
                $week_of_event[$i] = $day_of_event;
            }
        }
        else {
            for ($i = 0; $i < count($check_day); $i++) {
                for ($j = 0; $j < count(EventConst::EVENT_TIME); $j++) {
                    $event_info = [
                        'index' => $j,
                        'id' => null,
                        'name' => null,
                        'add_blocks' => 0,
                        'reserved' => false,
                    ];
                    $day_of_event[$j] = $event_info;
                }
                $week_of_event[$i] = $day_of_event;
            }
        }

        return response()->json([
            'week_calendar'=> $week_calendar,
            'day_name' => $day_name,
            'events' => $events,
            'week_of_event' => $week_of_event,
            'day_of_event' => $day_of_event,
        ]);
    }
}
