<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Registration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\MarkAbsentJob;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $category = $request->query('category');

        $query = Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select([
                'events.id',
                'events.judul',
                'events.deskripsi',
                'events.date',
                'events.foto_event',
                'events.pembicara',
                'events.foto_pembicara',
                'events.role',
                'categories.kategori as category_name'
            ]);

        if ($category) {
            $query->where('events.kategori_id', $category);
            return response($query->get());
        } else {
            return response([
                'trending' => Event::whereRaw("CONCAT(date, ' ', start_time) >= NOW()")->count(),
                'category' => Category::count(),
                'events' => $query->get()
            ]);
        }


    }

    public function sertifikasi()
    {
        return response(Event::join('categories', 'events.kategori_id', '=', 'categories.id')
            ->select('events.*', 'categories.kategori as category_name')
            ->where('kategori_id', 5)
            ->get()->makeHidden(['user_id', 'created_at', 'updated_at', 'kategori_id', 'tempat', 'available_slot', 'tempat', 'start_time', 'end_time']));
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
                'message' => 'Event tidak ditemukan',
            ], 404);
        }
    }

    public function myevents(Request $request)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $events = Event::where('user_id', $user->id)
                ->select('id', 'judul', 'foto_event', \DB::raw('DATE(updated_at) as uploaded'), 'kategori_id')
                ->get();
            return response($events);
        } else {
            $events = Event::join('registrations', 'events.id', '=', 'registrations.event_id')
                ->where('registrations.user_id', $user->id)
                ->select(
                    'events.id',
                    'events.judul',
                    'events.foto_event',
                    \DB::raw('DATE(registrations.created_at) as join_date'),
                    'registrations.status'
                )
                ->get();
            return response($events);
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
                'message' => 'Tidak diizinkan'
            ], 403);

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
                'message' => 'Tidak diizinkan'
            ], 403);
        } else if (!$kode) {
            return response([
                'message' => 'Anda tidak terdaftar pada event ini'
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
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if ($event->available_slot <= 0) {
            return response([
                'message' => 'Event sudah penuh'
            ], 400);
        }

        if ($request->user()->is_admin) {
            return response([
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        if (date('Y-m-d', strtotime($event->date . ' -1 day')) <= date('Y-m-d')) {
            return response([
                'message' => 'Pendaftaran telah ditutup'
            ], 400);
        }

        $registration = Registration::where('event_id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($registration) {

            if ($registration->status == 'registered' || $registration->status == 'attended') {
                return response([
                    'message' => 'Sudah terdaftar'
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
            'message' => 'Berhasil mendaftar'
        ]);
    }

    public function cancel(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if ($event->user_id == $request->user()->id) {
            return response([
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        $registration = Registration::where('event_id', $id)->where('user_id', $request->user()->id)->first();

        if (!$registration) {
            return response([
                'message' => 'Belum terdaftar'
            ], 400);
        }

        if ($registration->status == 'cancelled') {
            return response([
                'message' => 'Sudah dibatalkan'
            ], 400);
        }

        $registration->update([
            'status' => 'cancelled'
        ]);

        $event->update([
            'available_slot' => $event->available_slot + 1
        ]);

        return response([
            'message' => 'Berhasil dibatalkan'
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

        $foto_event = null;
        $foto_pembicara = null;

        if ($request->hasFile('event_img')) {
            try {
                $eventImage = $request->file('event_img');
                $eventImageName = pathinfo($eventImage->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $eventImage->getClientOriginalExtension();
                $eventImagePath = $eventImage->storeAs('events', $eventImageName, 'public');
                $foto_event = $eventImagePath;
            } catch (\Exception $error) {
                return response([
                    'message' => 'Gagal mengunggah gambar event: ' . $error->getMessage(),
                ], 500);
            }
        }

        if ($request->hasFile('speaker_img')) {
            try {
                $speakerImage = $request->file('speaker_img');
                $speakerImageName = pathinfo($speakerImage->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $speakerImage->getClientOriginalExtension();
                $speakerImagePath = $speakerImage->storeAs('speakers', $speakerImageName, 'public');
                $foto_pembicara = $speakerImagePath;
            } catch (\Exception $error) {
                return response([
                    'message' => 'Gagal mengunggah gambar pembicara: ' . $error->getMessage(),
                ], 500);
            }
        }

        $event = Event::create([
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

        // Schedule MarkAbsentJob to run at the event's end time
        $eventEnd = \Carbon\Carbon::parse($event->date . ' ' . $event->end_time);
        MarkAbsentJob::dispatch($event->id)->delay($eventEnd);

        return response([
            'message' => 'Event berhasil dibuat'
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if ($event->user_id != $request->user()->id) {
            return response([
                'message' => 'Tidak diizinkan'
            ], 403);
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
                if (Storage::disk('public')->exists($event->foto_event)) {
                    Storage::disk('public')->delete($event->foto_event);
                }
            }

            try {
                $eventImage = $request->file('event_img');
                $eventImageName = pathinfo($eventImage->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $eventImage->getClientOriginalExtension();
                $eventImagePath = $eventImage->storeAs('events', $eventImageName, 'public');
                $foto_event = $eventImagePath;

                $event->update([
                    'foto_event' => $foto_event,
                ]);
            } catch (\Exception $error) {
                return response([
                    'message' => 'Gagal mengunggah gambar event: ' . $error->getMessage(),
                ], 500);
            }
        }

        if ($request->hasFile('speaker_img')) {
            if ($event->foto_pembicara) {
                if (Storage::disk('public')->exists($event->foto_pembicara)) {
                    Storage::disk('public')->delete($event->foto_pembicara);
                }
            }

            try {
                $speakerImage = $request->file('speaker_img');
                $speakerImageName = pathinfo($speakerImage->getClientOriginalName(), PATHINFO_FILENAME) . '-' . Str::random(16) . '.' . $speakerImage->getClientOriginalExtension();
                $speakerImagePath = $speakerImage->storeAs('speakers', $speakerImageName, 'public');
                $foto_pembicara = $speakerImagePath;

                $event->update([
                    'foto_pembicara' => $foto_pembicara,
                ]);
            } catch (\Exception $error) {
                return response([
                    'message' => 'Gagal mengunggah gambar pembicara: ' . $error->getMessage(),
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
            'message' => 'Event berhasil diperbarui'
        ]);
    }

    public function delete(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if ($event->user_id != $request->user()->id) {
            return response([
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        if ($event->foto_event) {
            if (Storage::disk('public')->exists($event->foto_event)) {
                Storage::disk('public')->delete($event->foto_event);
            }
        }

        if ($event->foto_pembicara) {
            if (Storage::disk('public')->exists($event->foto_pembicara)) {
                Storage::disk('public')->delete($event->foto_pembicara);
            }
        }

        $event->delete();

        return response([
            'message' => 'Event berhasil dihapus'
        ]);
    }

    public function checkin(Request $request, $id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response([
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Tidak diizinkan'
            ], 403);
        }

        $registration = Registration::where('id', $request->kode)
            ->where('event_id', $id)
            ->first();

        if (!$registration) {
            return response([
                'message' => 'Kode tidak valid'
            ], 400);
        }

        if ($registration->status == 'cancelled') {
            return response([
                'message' => 'Pendaftaran sudah dibatalkan'
            ], 400);
        }

        if ($registration->status == 'absent') {
            return response([
                'message' => 'Anda sudah melewatkan event ini'
            ], 400);
        }

        if ($registration->status == 'attended') {
            return response([
                'message' => 'Anda sudah pernah check-in'
            ], 400);
        }

        $registration->update([
            'status' => 'attended'
        ]);

        return response([
            'message' => 'Berhasil check-in',
            'name' => $registration->user->fullname
        ]);

    }

    public function status(Request $request, $id)
    {

        $registration = Registration::where('user_id', $request->user()->id)
            ->where('event_id', $id)
            ->first();

        if (!$registration) {
            return response([
                'message' => 'Tidak terdaftar'
            ], 400);
        } else {
            return response([
                'status' => $registration->status
            ]);
        }

    }

}