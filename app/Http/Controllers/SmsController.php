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

    // TBD
    // public function checkPhoneNumber($phone=null, Request $request)
    // {
    //     $sid = getenv('TWILIO_ACCOUNT_SID'); 
    //     $token = getenv('TWILIO_AUTH_TOKEN');
    //     $number = getenv('TWILIO_NUMBER');
    //     $client = new \Twilio\Rest\Client($sid, $token);
        
    //     try {
    //         $number = $client->lookups
    //             ->phoneNumbers("226198823517")
    //             ->fetch(
    //                 array("countryCode" => "US", "type" => "carrier")
    //             );
    //         if($number){
    //             if($number->carrier["type"] == 'mobile'){
    //                 return 1;
    //             }else{
    //                 return null;
    //             }
    //         }else{
    //             return null; 
    //         }
    //     } catch (Exception $e) {
    //         return null; 
    //     }
        
    // }

    public function createNewAccount($email, $phone)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $password = implode($pass);

        $new_user = new User;
        $new_user->email = $email;
        $new_user->password = bcrypt($password);
        $new_user->slug = uniqid();
        $new_user->save();

        if($new_user->id){
            $phone = new Phone;
            $phone->phone = $phone;
            $phone->user_id = $new_user->id;
            $phone->save();

            if($phone){ return 1;}else{return null;}
        }else{
            return null;
        }
    }

    public function processIncomingMessage(Request $request)
    {
        $sid = getenv('TWILIO_ACCOUNT_SID'); 
        $token = getenv('TWILIO_AUTH_TOKEN');
        $number = getenv('TWILIO_NUMBER');
        $client = new \Twilio\Rest\Client($sid, $token);

        // get the phone information if it exists
        $from = $request['From'];
        if(empty($from)){
            exit;
        }

        $body = $request['Body'];
        if(empty($body)){
            exit;
        }

        $response = "";

        if (filter_var($body, FILTER_VALIDATE_EMAIL)) {
            // we are getting an email
            // check if this email is already in the user table
            // if not create a new user and send confirmation
            if(User::where('email','=',$body)->count() == 0){
                if($this->createNewAccount($body, $from)){
                    $response = "You're in! Text a topic or the word 'schedule' to list today's topics.";
                }else{
                    exit;
                }
            }   
            // if already there, proceed further as it may be a topic
        }

        $phone = Phone::where("phone", "=", $request['From'])->first();

        if(count($phone) == 0){
            exit;
        }

        if($body == "schedule" || $body == "Schedule"){

            $today = date('Y-m-d')." 00:00:00";

            $schedule = Repetition::where('user_id','=', $phone->user_id)
                                    ->where('when', '=', $today)
                                    ->with('topic')
                                    ->get();

            if (count($schedule) == 0) {
              //print "No upcoming events found.\n";
              $response = "Nothing to work on today! ".date('Y-m-d');
            } else {
              //print "Upcoming events:\n";
              foreach ($schedule as $schedule_item) {
                $response = $response.$schedule_item->topic->name."\n";
              }
            }
        }elseif($response == ""){
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
