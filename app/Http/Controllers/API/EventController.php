<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name');

        if ($request->has('cat')) {
            $query->where('kategori_id', $request->cat);
        }

        $events = $query->get();

        return response($events);
    }


    public function categories()
    {
        return response(Category::all()->pluck('kategori'));
    }

    public function details($id)
    {
        $event = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('events.id', $id)
            ->first();

        if ($event) {
            return response($event);
        } else {
            return response([
                'message' => 'Event not found',
            ], 404);
        }
    }


}
