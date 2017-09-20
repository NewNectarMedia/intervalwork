<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Auth;
use App\Phone;
use App\Topic;
use App\Repetition;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $user = Auth::user();

        $schedule = Repetition::where('user_id', '=', $user->id)
                                ->where('when', '>=', date('Y-m-d').' 00:00:00')
                                ->with('topic')
                                ->orderBy('when', 'ASC')
                                ->get();

        return view('home', compact(['user', 'schedule']));
    }

    public function createPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withInput()
                ->withErrors($validator);
        }

        $user = Auth::user();

        $phone = new Phone;
        $phone->name = $request->name;
        $phone->phone = $request->phone;
        $phone->user_id = $user->id;
        $phone->save();
        
        return redirect('/home');
    }

    public function deletePhone($id=null, Request $request)
    {
        Phone::findOrFail($id)->delete();

        return redirect('/home');
    }

    public function createTopic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withInput()
                ->withErrors($validator);
        }

        $user = Auth::user();

        $topic = new Topic([
            'name' => $request['topic'],
            'user_id' => $user->id
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
                'user_id' => $user->id,
                'when' => $rep,
                'timezone' => 'America/Los_Angeles'
            ]);
            $repetition->save();
        }

        return redirect('/home');
    }
}
