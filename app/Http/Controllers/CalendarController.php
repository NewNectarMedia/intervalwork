<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Session;
use App\User;
use App\Phone;
use App\Topic;
use App\Repetition;

class CalendarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function exportiCal($slug = null)
    {
        $user = User::where('slug', '=', $slug)->with('repetitions')->first();

        if(!$user){
            return null;
        }

        if($user->repetitions){
            $events = $user->repetitions;
        }else{
            $events = null;
        }

        // the iCal date format. Note the Z on the end indicates a UTC timestamp.
        define('DATE_ICAL', 'Ymd\THis');
         
        // max line length is 75 chars. New line is \\r\n
        $output = "BEGIN:VCALENDAR\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "VERSION:2.0\r\n";
        $output .= "PRODID:-//Interval Work//Topics//EN\r\n";
         
        // loop over events
        foreach ($events as $event):
            $output .= "BEGIN:VEVENT\r\n";
            $output .= "SUMMARY:".$event->topic->name."\r\n";
            $output .= "UID:intervalwork".$user->slug.$event->id."\r\n";
            $output .= "STATUS:CONFIRMED\r\n";
            $output .= "DTSTAMP:" . date(DATE_ICAL, strtotime($event->when)) . "\r\n";
            $output .= "DTSTART:" . date(DATE_ICAL, strtotime($event->when)) . "\r\n";
            $output .= "DTEND:" . date(DATE_ICAL, strtotime($event->when)) . "\r\n";
            //$output .= "DATE:" . date('Ymd', strtotime($event->when)) . "\r\n";
            $output .= "LAST-MODIFIED:" . date(DATE_ICAL, strtotime($event->when)) . "\r\n";
            //$output .= "LOCATION:N/A\r\n";
            $output .= "END:VEVENT\r\n";
        endforeach;
         
        // close calendar
        $output .= "END:VCALENDAR";
         
        return response($output)->header('Content-Type', 'text/calendar');
    }
}





