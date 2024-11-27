<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Registration;

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

    public function mine(Request $request)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $events = Event::where('user_id', $user->id)->get();
        } else {
            $events = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.user_id', $user->id)
                ->select('events.*')
                ->get();
        }

        return response($events);
    }

    public function filter(Request $request, $id)
    {

        if ($request->user()->is_admin) {
            return response([
                Event::where('kategori_id', $id)
                    ->where('user_id', $request->user()->id)
                    ->get()
            ]);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function registered(Request $request)
    {

        if (!$request->user()->is_admin) {
            return response([
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', $request->user()->id)
                    ->where('registrations.is_cancelled', false)
                    ->select('events.*')
                    ->get()
            ]);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function cancelled(Request $request)
    {

        if (!$request->user()->is_admin) {
            return response([
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', $request->user()->id)
                    ->where('registrations.is_cancelled', true)
                    ->select('events.*')
                    ->get()
            ]);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function participants(Request $request, $id)
    {

        $user = $request->user();

        if ($user->is_admin && Event::where('id', $id)->where('user_id', $user->id)->exists()) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.event_id', $id)
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select('users.fullname', 'users.photo', 'registrations.created_at as join_date')
                    ->get()
            );

        } else {

            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function attend(Request $request, $id)
    {

        if ($request->user()->is_admin && Event::where('id', $id)->where('user_id', $request->user()->id)->exists()) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.event_id', $id)
                    ->where('registrations.is_cancelled', false)
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select('users.fullname', 'users.photo', 'registrations.created_at as join_date')
                    ->get()
            );

        } else {

            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function absent(Request $request, $id)
    {

        if ($request->user()->is_admin && Event::where('id', $id)->where('user_id', $request->user()->id)->exists()) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.event_id', $id)
                    ->where('registrations.is_cancelled', true)
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select('users.fullname', 'users.photo', 'registrations.created_at as join_date')
                    ->get()
            );

        } else {

            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function kode(Request $request, $id)
    {

        $kode = Registration::where('event_id', $id)->where('user_id', $request->user()->id)->select('id as kode_unik')->first();

        if (!$request->user()->is_admin && $kode) {
            return response($kode);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }
}