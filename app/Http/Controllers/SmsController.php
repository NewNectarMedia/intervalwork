<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\Phone;
use App\Topic;
use App\Repetition;

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

        // get the phone information if it exists
        if(empty($request['From'])){
            exit;
        }

        $phone = Phone::where("phone", "=", $request['From'])->first();

        if(count($phone) == 0){
            exit;
        }

        $body = $request['Body'];
        if($body == '' || $body == null){
            exit;
        }

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
            $topic = new Topic([
                'name' => $body,
                'user_id' => $phone->user_id
            ]);
            $topic->save();

            $repetitions = array();
            $repetitions[] = date('Y-m-d'); // get today's date
            $repetitions[] = date("Y-m-d", strtotime("+2 weekdays")); // rep 1
            $repetitions[] = date("Y-m-d", strtotime("+10 weekdays")); // rep 2
            $repetitions[] = date("Y-m-d", strtotime("+20 weekdays")); // rep 3
            $repetitions[] = date("Y-m-d", strtotime("+40 weekdays")); // rep 4

            foreach($repetitions as $rep){
                $repetition = new Repetition([
                    'topic_id' => $topic->id,
                    'user_id' => $phone->user_id,
                    'when' => $rep,
                    'timezone' => 'America/Los_Angeles'
                ]);
                $repetition->save();
            }

            $response = "Roger - topics added to calendar. Text 'schedule' to view today's topics."; 
        }

        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>";
        echo "<Message>".$response."</Message>";
        echo "</Response>";
    }
}
