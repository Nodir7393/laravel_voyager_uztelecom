<?php

namespace App\Jobs;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $profileRequest;

    public function __construct(ProfileRequest $profileRequest)
    {
        $this->profileRequest=$profileRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $user = User::find(Auth::id());
            $user->phone = $this->profileRequest->phone;
            $user->email = $this->profileRequest->email;
            $user->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $user;
    }
}
