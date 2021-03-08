<?php

namespace App\Http\Controllers;
use App\Models\Event;
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
}
