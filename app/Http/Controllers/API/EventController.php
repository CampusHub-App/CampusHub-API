<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Registration;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Support\Str;

class EventController extends Controller
{

    protected $s3;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function index(Request $request)
    {
        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No events available',
            ]);
        }

    }

    public function webinar()
    {

        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 1)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No webinars available',
            ]);
        }

    }

    public function seminar()
    {

        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 2)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No seminar available',
            ]);
        }

    }

    public function kuliahtamu()
    {

        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 3)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No kuliah tamu available',
            ]);
        }

    }

    public function workshop()
    {

        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 4)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No workshop available',
            ]);
        }

    }

    public function sertifikasi()
    {

        $events = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 5)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']);

        if ($events) {
            return response($events);
        } else {
            return response([
                'message' => 'No sertifikasi available',
            ]);
        }

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
            return response($event->makeHidden(['user_id', 'kategori_id', 'created_at', 'updated_at']));
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
            $events = Event::where('user_id', $user->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if (!$events) {
                return response([
                    'message' => 'You have not uploaded any events',
                ]);
            }
        } else {
            $events = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.user_id', $user->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(registrations.created_at) as join_date'))
                ->get();

            if (!$events) {
                return response([
                    'message' => 'You have not registered to any events',
                ]);
            }
        }

        return response($events);
    }

    public function mywebinar(Request $request)
    {

        if ($request->user()->is_admin) {

            $event = Event::where('kategori_id', 1)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not uploaded any webinars',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function myseminar(Request $request)
    {

        if ($request->user()->is_admin) {

            $event = Event::where('kategori_id', 2)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not uploaded any seminars',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function mykuliahtamu(Request $request)
    {

        if ($request->user()->is_admin) {

            $event = Event::where('kategori_id', 3)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not uploaded any kuliah tamu',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function myworkshop(Request $request)
    {

        if ($request->user()->is_admin) {

            $event = Event::where('kategori_id', 4)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not uploaded any workshops',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function mysertifikasi(Request $request)
    {

        if ($request->user()->is_admin) {

            $event = Event::where('kategori_id', 5)
                ->where('user_id', $request->user()->id)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(events.updated_at) as uploaded'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not uploaded any sertifikasi',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function registered(Request $request)
    {

        if (!$request->user()->is_admin) {

            $event = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.user_id', $request->user()->id)
                ->where('registrations.is_cancelled', false)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(registrations.created_at) as join_date'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not registered to any events',
                ]);
            }

        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

    }

    public function cancelled(Request $request)
    {

        if (!$request->user()->is_admin) {

            $event = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.user_id', $request->user()->id)
                ->where('registrations.is_cancelled', true)
                ->select('events.id', 'events.judul', 'events.foto_event', \DB::raw('DATE(registrations.updated_at) as cancel_date'))
                ->get();

            if ($event) {
                return response($event);
            } else {
                return response([
                    'message' => 'You have not cancelled any events',
                ]);
            }

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

            $participants = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.event_id', $id)
                ->join('users', 'registrations.user_id', '=', 'users.id')
                ->select('users.fullname', 'users.photo', \DB::raw('DATE(registrations.created_at) as join_date'))
                ->get();

            if ($participants) {
                return response($participants);
            } else {
                return response([
                    'message' => 'No participants yet',
                ]);
            }

        } else {

            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function attend(Request $request, $id)
    {

        if ($request->user()->is_admin && Event::where('id', $id)->where('user_id', $request->user()->id)->exists()) {

            $participants = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.event_id', $id)
                ->where('registrations.is_cancelled', false)
                ->join('users', 'registrations.user_id', '=', 'users.id')
                ->select('users.fullname', 'users.photo', \DB::raw('DATE(registrations.created_at) as join_date'))
                ->get();

            if ($participants) {
                return response($participants);
            } else {
                return response([
                    'message' => 'No registered participants yet',
                ]);
            }

        } else {

            return response([
                'message' => 'Unauthorized'
            ], 401);

        }
    }

    public function absent(Request $request, $id)
    {

        if ($request->user()->is_admin && Event::where('id', $id)->where('user_id', $request->user()->id)->exists()) {

            $participants = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.event_id', $id)
                ->where('registrations.is_cancelled', true)
                ->join('users', 'registrations.user_id', '=', 'users.id')
                ->select('users.fullname', 'users.photo', \DB::raw('DATE(registrations.created_at) as join_date'))
                ->get();

            if ($participants) {
                return response($participants);
            } else {
                return response([
                    'message' => 'No cancelled participants yet',
                ]);
            }

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
            ->where('is_cancelled', false)
            ->select('id as kode_unik')
            ->first();

        if ($request->user()->is_admin) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        } else if (!$kode) {
            return response([
                'message' => 'You are not registered to this event'
            ], 400);
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

        $registration = Registration::where('event_id', $id)
        ->where('user_id', $request->user()->id)
        ->first();

        if ($registration->is_cancelled) {
            return response([
                'message' => 'You have cancelled your registration'
            ], 400);
        }

        if ($registration) {
            return response([
                'message' => 'Already registered'
            ], 400);
        }

        Registration::create([
            'event_id' => $id,
            'user_id' => $request->user()->id,
        ]);

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

        if ($registration->is_cancelled) {
            return response([
                'message' => 'Already cancelled'
            ], 400);
        }

        $registration->update([
            'is_cancelled' => true
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


        $event = Event::create([
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
            'user_id' => $request->user()->id,
        ]);

        if ($request->hasFile('event_img')) {
            $event_img = $request->file('event_img');

            try {
                $event_result = $this->s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'events/' . pathinfo($event_img->getClientOriginalName(), PATHINFO_FILENAME) . "-" . Str::random(16) . '.' . $event_img->getClientOriginalExtension(),
                    'Body' => $event_img->get(),
                    'ContentType' => $event_img->getMimeType(),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error uploading event image',
                ], 500);
            }

            $event->update([
                'foto_event' => $event_result['ObjectURL']
            ]);

        }

        if ($request->hasFile('speaker_img')) {
            $speaker_img = $request->file('speaker_img');

            try {
                $speaker_result = $this->s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'speakers/' . pathinfo($speaker_img->getClientOriginalName(), PATHINFO_FILENAME) . "-" . Str::random(16) . "." . $speaker_img->getClientOriginalExtension(),
                    'Body' => $speaker_img->get(),
                    'ContentType' => $speaker_img->getMimeType(),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error uploading speaker image',
                ], 500);
            }

            $event->update([
                'foto_pembicara' => $speaker_result['ObjectURL']
            ]);

        }

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

        if ($request->hasFile('event_img')) {

            try {
                $this->s3->deleteObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'events/' . basename($event->foto_event),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error deleting event image',
                ], 500);
            }

            $event_img = $request->file('event_img');

            try {
                $event_result = $this->s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'events/' . pathinfo($event_img->getClientOriginalName(), PATHINFO_FILENAME) . "-" . Str::random(16) . '.' . $event_img->getClientOriginalExtension(),
                    'Body' => $event_img->get(),
                    'ContentType' => $event_img->getMimeType(),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error uploading event image',
                ], 500);
            }

            $event->update([
                'foto_event' => $event_result['ObjectURL']
            ]);

        }

        if ($request->hasFile('speaker_img')) {

            try {
                $this->s3->deleteObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'speakers/' . basename($event->foto_pembicara),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error deleting speaker image',
                ], 500);
            }

            $speaker_img = $request->file('speaker_img');

            try {
                $speaker_result = $this->s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => 'speakers/' . pathinfo($speaker_img->getClientOriginalName(), PATHINFO_FILENAME) . "-" . Str::random(16) . "." . $speaker_img->getClientOriginalExtension(),
                    'Body' => $speaker_img->get(),
                    'ContentType' => $speaker_img->getMimeType(),
                ]);
            } catch (AwsException $error) {
                return response([
                    'message' => 'Error uploading speaker image',
                ], 500);
            }

            $event->update([
                'foto_pembicara' => $speaker_result['ObjectURL']
            ]);

        }

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

        try {
            $this->s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'events/' . basename($event->foto_event),
            ]);
        } catch (AwsException $error) {
            return response([
                'message' => 'Error deleting event image',
            ], 500);
        }

        try {
            $this->s3->deleteObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => 'speakers/' . basename($event->foto_pembicara),
            ]);
        } catch (AwsException $error) {
            return response([
                'message' => 'Error deleting speaker image',
            ], 500);
        }

        $event->delete();

        return response([
            'message' => 'Event deleted successfully'
        ]);

    }

}