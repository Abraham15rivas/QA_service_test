<?php

namespace App\Http\Controllers;
use App\Models\{Event, Question};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $events = Event::where('status', '=', true)
                        ->get();
        return $events->toJson();
    }

    public function show(Event $event, Request $request)
    {
        if($request->ajax()) {
            return $event;
        }
        $check = \Auth::check();
        if(isset($request->guest)) {
            return view('index', compact('event', 'check'));
        }
        return view('welcome', compact('check'));
    }

    public function allQuestions() {
        $questions = Question::all()->load('event');
        return $questions;
    }

    public function allDenied() {
        $questions = Question::where('status', '=', 'denied')->get();
        return $questions;
    }

    public function allApproved(Event $event) {
        $event->load(['questions' => function($query) {
            return $query->where('status', '=', 'approved')->get();
        }]);
        return $event->questions;
    }

    public function setQuestion(Request $request) {
        $check = \Auth::check();
        if($check) {
            $user = auth()->user();
            $request->participant_data = [
                'email' => $user->email,
                'user_id' => $user->id
            ];
        }
        Question::create([
            'content' => "¿ $request->content ?",
            'participant_data' => json_encode($request->participant_data),
            'event_id' => $request->event_id
        ]);
        return 'ok';
    }
}
