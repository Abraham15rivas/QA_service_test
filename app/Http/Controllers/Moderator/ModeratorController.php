<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\{Event, Question, Answer};
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function index() {
        $events = Event::get();
        return $events->toJson();
    }

    public function store(Request $request) {
        $user = auth()->user();
        $event = new Event();
        $event->title = $request->title;
        $event->slug = $request->slug;
        $event->description = $request->description;
        $event->video = $request->video;
        $event->qa = $request->qa == 'true' ? 1 : 0;
        $event->status = $request->status == 'true' ? 1 : 0;
        $event->user_id = $user->id;
        if($request->hasFile('image')) {
            $path = $request->file('image')->store('public/img/events');
            $event->image = explode('public/', $path)[1];
        }
        $event->save();
        return 'ok';
    }

    public function update(Event $event, Request $request) {
        $user = auth()->user();
        if($request->hasFile('image')) {
            if($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $path = $request->file('image')->store('public/img/events');
            $request->image = explode('public/', $path)[1];
        }
        $event->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'image' => $request->image,
            'video' => $request->video,
            'qa' => $request->qa == 'true' ? 1 : 0,
            'status' => $request->status == 'true' ? 1 : 0,
            'user_id' => $user->id
        ]);
        return 'ok';
    }

    public function destroy(Event $event) {
        if($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        $event->delete();
        return 'ok';
    }

    public function deniedQuestion(Request $request) {
        $question = Question::findOrFail($request->id);
        $question->update([
            'status' => 'denied'
        ]);
        return 'ok';
    }

    public function answerQuestion(Request $request) {
        Answer::create([
            'content' => $request->content,
            'question_id' => $request->question_id
        ]);
        $question = Question::findOrFail($request->question_id);
        $question->update([
            'status' => 'approved'
        ]);
        return 'ok';
    }
}
