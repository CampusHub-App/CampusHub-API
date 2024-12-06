<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Registration;
use App\Services\S3Service;

class EventController extends Controller
{

    protected $s3;

    public function __construct()
    {
        $this->s3 = new S3Service();
    }

    public function index()
    {
        return response([
            'trending' => Event::whereRaw("CONCAT(date, ' ', start_time) >= ?", [date('Y-m-d H:i:s')])->count(),
            'category' => Category::count(),
            'events' => Event::join('categories', 'events.kategori_id', '=', 'categories.id')
                ->select('events.*', 'categories.kategori as category_name')
                ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time'])
        ]);
    }

    public function webinar()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 1)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
    }

    public function seminar()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 2)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
    }

    public function kuliahtamu()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 3)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
    }

    public function workshop()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 4)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
    }

    public function sertifikasi()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 5)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
    }

    public function categories()
    {
        return response(Category::all()->select('id', 'kategori'));
    }

    public function details($id)
    {
        $event = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('events.id', $id)
            ->first();

        if ($event) {
            return response($event->makeHidden(['user_id', 'created_at', 'updated_at']));
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
            return response(
                Event::where('user_id', $user->id)
                    ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                    ->get()
            );
        } else {

            $absent = Registration::join('events', 'registrations.event_id', '=', 'events.id')
                ->where('registrations.user_id', $user->id)
                ->where('registrations.status', 'registered')
                ->where('events.date', '<=', date('Y-m-d'))
                ->where('events.end_time', '<=', date('H:i:s'))
                ->select('registrations.*')
                ->get();

            if ($absent) {
                foreach ($absent as $alpha) {
                    $alpha->status = 'absent';
                    $alpha->save();
                }
            }
            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', $user->id)
                    ->select(
                        'events.id',
                        'events.judul',
                        'events.foto_event',
                        \DB::raw('DATE(registrations.created_at) as join_date'),
                        'registrations.status'
                    )
                    ->get()
            );
        }
    }

    public function mywebinar(Request $request)
    {

        if ($request->user()->is_admin) {

            return response(Event::where('kategori_id', 1)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                ->get());

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function myseminar(Request $request)
    {

        if ($request->user()->is_admin) {

            return response(
                Event::where('kategori_id', 2)
                    ->where('user_id', $request->user()->id)
                    ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function mykuliahtamu(Request $request)
    {

        if ($request->user()->is_admin) {

            return response(
                Event::where('kategori_id', 3)
                    ->where('user_id', $request->user()->id)
                    ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function myworkshop(Request $request)
    {

        if ($request->user()->is_admin) {

            return response(
                Event::where('kategori_id', 4)
                    ->where('user_id', $request->user()->id)
                    ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);

        }

    }

    public function mysertifikasi(Request $request)
    {

        if ($request->user()->is_admin) {

            return response(
                Event::where('kategori_id', 5)
                    ->where('user_id', $request->user()->id)
                    ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'), 'events.kategori_id')
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function registered(Request $request)
    {

        if (!$request->user()->is_admin) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', $request->user()->id)
                    ->where('registrations.status', 'registered')
                    ->select(
                        'events.id',
                        'events.judul',
                        'events.foto_event',
                        \DB::raw('DATE(registrations.created_at) as join_date'),
                        \DB::raw("'registered' as status")
                    )
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function cancelled(Request $request)
    {

        if (!$request->user()->is_admin) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', $request->user()->id)
                    ->where('registrations.status', 'cancelled')
                    ->select(
                        'events.id',
                        'events.judul',
                        'events.foto_event',
                        \DB::raw('DATE(registrations.updated_at) as cancel_date'),
                        \DB::raw("'cancelled' as status")
                    )
                    ->get()
            );

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function participants(Request $request, $id)
    {

        $user = $request->user();
        $absent = Registration::join('events', 'registrations.event_id', '=', 'events.id')
            ->where('registrations.event_id', $id)
            ->where('registrations.status', 'registered')
            ->where('events.date', '<=', date('Y-m-d'))
            ->where('events.end_time', '<=', date('H:i:s'))
            ->select('registrations.*')
            ->get();

        if ($absent) {
            foreach ($absent as $alpha) {
                $alpha->status = 'absent';
                $alpha->save();
            }
        }

        if ($user->is_admin && Event::where('id', $id)->where('user_id', $user->id)->exists()) {

            return response(
                Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                    ->where('registrations.event_id', $id)
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select(
                        'users.fullname',
                        'users.photo',
                        \DB::raw('DATE(registrations.created_at) as join_date'),
                        'registrations.status'
                    )
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
                    ->where('registrations.status', 'registered')
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select(
                        'users.fullname',
                        'users.photo',
                        \DB::raw('DATE(registrations.created_at) as join_date'),
                        \DB::raw("'registered' as status")
                    )
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
                    ->where('registrations.status', 'cancelled')
                    ->join('users', 'registrations.user_id', '=', 'users.id')
                    ->select(
                        'users.fullname',
                        'users.photo',
                        \DB::raw('DATE(registrations.created_at) as join_date'),
                        \DB::raw("'cancelled' as status")
                    )
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

        $kode = Registration::where('event_id', $id)
            ->where('user_id', $request->user()->id)
            ->where('status', '!=', 'cancelled')
            ->select('id as kode_unik', 'status')
            ->first();

        if ($request->user()->is_admin) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        } else if (!$kode) {
            return response([
                'message' => 'You are not registered to this event'
            ], 401);
        } else {
            return response($kode);
        }

    }

    public function register(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->available_slot <= 0) {
            return response([
                'message' => 'Event is already full'
            ], 400);
        }

        if ($request->user()->is_admin) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if (date('Y-m-d', strtotime($event->date . ' -1 day')) <= date('Y-m-d')) {
            return response([
                'message' => 'Registration is closed'
            ], 400);
        }

        $registration = Registration::where('event_id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($registration) {

            if ($registration->status == 'registered' || $registration->status == 'attended') {
                return response([
                    'message' => 'Already registered'
                ], 400);
            }
        }

        if (!$registration) {
            Registration::create([
                'user_id' => $request->user()->id,
                'event_id' => $id,
                'status' => 'registered'
            ]);
        } else {
            $registration->update([
                'status' => 'registered'
            ]);
        }

        $event->update([
            'available_slot' => $event->available_slot - 1
        ]);

        return response([
            'message' => 'Registered successfully'
        ]);
    }

    public function cancel(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->user_id == $request->user()->id) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $registration = Registration::where('event_id', $id)->where('user_id', $request->user()->id)->first();

        if (!$registration) {
            return response([
                'message' => 'Not registered'
            ], 400);
        }

        if ($registration->status == 'cancelled') {
            return response([
                'message' => 'Already cancelled'
            ], 400);
        }

        $registration->update([
            'status' => 'cancelled'
        ]);

        $event->update([
            'available_slot' => $event->available_slot + 1
        ]);

        return response([
            'message' => 'Cancelled successfully'
        ]);
    }

    public function create(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'desc' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'event_img' => 'nullable|image|max:15000',
            'speaker' => 'required',
            'speaker_img' => 'nullable|image|max:15000',
            'role' => 'required',
            'location' => 'nullable',
            'category' => 'required|integer|between:1,5',
            'slot' => 'required|integer|min:1',
        ]);



        if ($request->hasFile('event_img')) {

            try {
                $foto_event = $this->s3->uploadImg('events', $request->file('event_img'));
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }

        }

        if ($request->hasFile('speaker_img')) {

            try {
                $foto_pembicara = $this->s3->uploadImg('speakers', $request->file('speaker_img'));
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }

        }

        Event::create([
            'judul' => $request->title,
            'deskripsi' => $request->desc,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'foto_event' => $foto_event,
            'pembicara' => $request->speaker,
            'foto_pembicara' => $foto_pembicara,
            'role' => $request->role,
            'tempat' => $request->location,
            'kategori_id' => $request->category,
            'available_slot' => $request->slot,
            'user_id' => $request->user()->id,
        ]);

        return response([
            'message' => 'Event created successfully'
        ]);
    }

    public function update(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->user_id != $request->user()->id) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'title' => 'required',
            'desc' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'event_img' => 'nullable|image|max:15000',
            'speaker' => 'required',
            'speaker_img' => 'nullable|image|max:15000',
            'role' => 'required',
            'location' => 'nullable',
            'category' => 'required|integer|between:1,5',
            'slot' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('event_img')) {

            if ($event->foto_event) {
                try {
                    $this->s3->deleteImg('events', $event->foto_event);
                } catch (\Exception $error) {
                    return response([
                        'message' => $error->getMessage(),
                    ], 500);
                }
            }

            try {
                $foto_event = $this->s3->uploadImg('events', $request->file('event_img'));

                $event->update([
                    'foto_event' => $foto_event,
                ]);
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }

        }

        if ($request->hasFile('speaker_img')) {

            if ($event->foto_pembicara) {
                try {
                    $this->s3->deleteImg('speakers', $event->foto_pembicara);
                } catch (\Exception $error) {
                    return response([
                        'message' => $error->getMessage(),
                    ], 500);
                }
            }

            try {
                $foto_pembicara = $this->s3->uploadImg('speakers', $request->file('speaker_img'));

                $event->update([
                    'foto_pembicara' => $foto_pembicara,
                ]);
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }

        }

        $event->update([
            'judul' => $request->title,
            'deskripsi' => $request->desc,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'pembicara' => $request->speaker,
            'role' => $request->role,
            'tempat' => $request->location,
            'kategori_id' => $request->category,
            'available_slot' => $request->slot,
        ]);

        return response([
            'message' => 'Event updated successfully'
        ]);

    }

    public function delete(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->user_id != $request->user()->id) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if ($event->foto_event) {
            try {
                $this->s3->deleteImg('events', $event->foto_event);
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }
        }

        if ($event->foto_pembicara) {
            try {
                $this->s3->deleteImg('speakers', $event->foto_pembicara);
            } catch (\Exception $error) {
                return response([
                    'message' => $error->getMessage(),
                ], 500);
            }
        }

        $event->delete();

        return response([
            'message' => 'Event deleted successfully'
        ]);

    }

    public function checkin(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event not found'
            ], 404);
        }

        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        $registration = Registration::where('id', $request->kode)
            ->where('event_id', $id)
            ->first();

        if (!$registration) {
            return response([
                'message' => 'Not registered'
            ], 400);
        }

        if ($registration->status == 'cancelled') {
            return response([
                'message' => 'Registration already cancelled'
            ], 400);
        }

        if ($registration->status == 'absent') {
            return response([
                'message' => 'You missed the event'
            ], 400);
        }

        if ($registration->status == 'attended') {
            return response([
                'message' => 'Already checked in'
            ], 400);
        }

        $registration->update([
            'status' => 'attended'
        ]);

        return response([
            'message' => 'Checked in successfully'
        ]);

    }

    public function status(Request $request, $id)
    {

        $registration = Registration::where('user_id', $request->user()->id)
            ->where('event_id', $id)
            ->first();

        if (!$registration) {
            return response([
                'message' => 'Not registered'
            ], 400);
        } else {
            return response([
                'status' => $registration->status
            ]);
        }

    }

}