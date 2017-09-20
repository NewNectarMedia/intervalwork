<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function processIncomingMessage(Request $request)
    {
        $sid = getenv('TWILIO_ACCOUNT_SID'); 
        $token = getenv('TWILIO_AUTH_TOKEN');
        $number = getenv('TWILIO_NUMBER');
        $client = new \Twilio\Rest\Client($sid, $token);

        // Use the client to do fun stuff like send text messages!
    /*    $client->messages->create(
            // the number you'd like to send the message to
            '+16198823517',
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $number,
                // the body of the text message you'd like to send
                'body' => 'Hey Bob! Good luck on the bar exam!'
            )
        );
    */

        $repetitions = array();
        $repetitions[] = date('Y-m-d'); // get today's date
        $repetitions[] = date("Y-m-d", strtotime("+2 weekdays")); // rep 1
        $repetitions[] = date("Y-m-d", strtotime("+10 weekdays")); // rep 2
        $repetitions[] = date("Y-m-d", strtotime("+20 weekdays")); // rep 3
        $repetitions[] = date("Y-m-d", strtotime("+40 weekdays")); // rep 4

        // make an associative array of senders we know, indexed by phone number
        // twillio number: $number 
        $people = array(
            "+16198823517"=>"Dad",
            "+17068777561"=>"Erika",
            "+13038083698"=>"Dad",
        );

        // if the sender is known, then greet them by name
        // otherwise, consider them just another monkey
        if(!array_key_exists($request['From'], $people)) {
            exit;
        }

        $name = $people[$request['From']];
        
        $body = $request['Body'];
        //$response = "Thanks for the message ".$name;
        $response = "Roger - topics added to calendar. Text 'schedule' to view today's topics.";

        if($body == "schedule" || $body == "Schedule"){
     /*       $response = "";

            $optParams = array(
              'maxResults' => 20,
              'orderBy' => 'startTime',
              'singleEvents' => TRUE,
              'timeMin' => date('Y-m-d\T')."00:00:00Z",
              'timeMax' => date('Y-m-d\T')."23:59:59Z",
            );
            $results = $service->events->listEvents($calendarId, $optParams);

            if (count($results->getItems()) == 0) {
              //print "No upcoming events found.\n";
              $response = "Nothing to work on today! ".date('Y-m-d');
            } else {
              //print "Upcoming events:\n";
              foreach ($results->getItems() as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                  $start = $event->start->date;
                }
                //printf("%s (%s)\n", $event->getSummary(), $start);
                $response = $response.$event->getSummary()."\n";
              }
            }
            
            //$response = "Today's schedule... TBD";
            */
        }else{
          $i = 1;
            foreach($repetitions as $rep){
                /*
                $event = new Google_Service_Calendar_Event(array(
                  'summary' => $body." (".$i.")",
                  'location' => '',
                  'description' => '',
                  'start' => array(
                    'date' => $rep,
                    'timeZone' => 'America/Los_Angeles',
                  ),
                  'end' => array(
                    'date' => $rep,
                    'timeZone' => 'America/Los_Angeles',
                  ),
                  'attendees' => array(
                    array('email' => 'p@newnectar.com'),
                  ),
                  'reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                      array('method' => 'email', 'minutes' => 24 * 60),
                      array('method' => 'popup', 'minutes' => 10),
                    ),
                  ),
                ));
                */
                //'dateTime' => '2017-06-28T17:00:00-07:00',
                //,
                //  'recurrence' => array(
                //    'RRULE:FREQ=DAILY;COUNT=2'
                //  )

                //$event = $service->events->insert($calendarId, $event);
                
                
                $i++;
            }
            
        }

        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "<Message>".$response."</Message>";
        echo "</Response>";
    }
}
