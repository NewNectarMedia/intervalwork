<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        define('DATE_ICAL', 'Ymd\THis\Z');
         
        // max line length is 75 chars. New line is \\n
        $output = "BEGIN:VCALENDAR\n";
        $output .= "METHOD:PUBLISH\n";
        $output .= "VERSION:2.0\n";
        $output .= "PRODID:-//Interval Work//Topics//EN\n";
         
        // loop over events
        foreach ($events as $event):
            $output .= "BEGIN:VEVENT\n";
            $output .= "SUMMARY:".$event->topic->name."\n";
            $output .= "UID:intervalwork".$user->slug.$event->id."\n";
            $output .= "STATUS:CONFIRMED\n";
            $output .= "DTSTAMP:" . date(DATE_ICAL, strtotime($event->when)) . "\n";
            $output .= "DTSTART:" . date(DATE_ICAL, strtotime($event->when)) . "\n";
            $output .= "DTEND:" . date(DATE_ICAL, strtotime($event->when)) . "\n";
            $output .= "LAST-MODIFIED:" . date(DATE_ICAL, strtotime($event->when)) . "\n";
            $output .= "LOCATION:N/A\n";
            $output .= "END:VEVENT\n";
        endforeach;
         
        // close calendar
        $output .= "END:VCALENDAR";
         
        return $output;
    }
}





