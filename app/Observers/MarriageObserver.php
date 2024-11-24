<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Marriage;
use Illuminate\Support\Facades\Mail;
use App\Mail\MarriageCongratulations;

class MarriageObserver
{
    /**
     * Handle the Marriage "created" event.
     */
    public function created(Marriage $marriage): void
    {
        // Update marital status for both spouses
        User::whereIn('id', [$marriage->spouse1_id, $marriage->spouse2_id])
            ->update(['marital_status' => 'married']);

        // Send congratulatory emails
        $spouse1 = User::find($marriage->spouse1_id);
        $spouse2 = User::find($marriage->spouse2_id);

        if ($spouse1) {
           // Mail::to($spouse1->email)->send(new MarriageCongratulations($spouse1));
        }

        if ($spouse2) {
           // Mail::to($spouse2->email)->send(new MarriageCongratulations($spouse2));
        }
    
    }

    /**
     * Handle the Marriage "updated" event.
     */
    public function updated(Marriage $marriage): void
    {
        //
    }

    /**
     * Handle the Marriage "deleted" event.
     */
    public function deleted(Marriage $marriage): void
    {
        $marriage->spouse1->update(['marital_status' => 'single']);
        $marriage->spouse2->update(['marital_status' => 'single']);
    }

    /**
     * Handle the Marriage "restored" event.
     */
    public function restored(Marriage $marriage): void
    {
        //
    }

    /**
     * Handle the Marriage "force deleted" event.
     */
    public function forceDeleted(Marriage $marriage): void
    {
        //
    }
}
