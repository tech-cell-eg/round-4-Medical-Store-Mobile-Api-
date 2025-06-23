<?php

namespace App\Listeners;

use App\Events\CreatedUser;
use App\Models\UserProfile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateUserProfile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    public function handle(CreatedUser $event): void
    {
        UserProfile::create([
            'user_id' => $event->user->id,
            'first_name' => 'Unknown',
            'last_name' => 'Unknown',
        ]);
    }
}
