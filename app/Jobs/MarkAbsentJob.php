<?php

namespace App\Jobs;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkAbsentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $eventId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Registration::where('event_id', $this->eventId)
            ->where('status', 'registered')
            ->update(['status' => 'absent']);
    }
}
