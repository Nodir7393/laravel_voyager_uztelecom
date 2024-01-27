<?php

namespace App\Observers;

use App\Enums\ApplicationMagicNumber;
use App\Models\SignedDocs;
use App\Enums\ApplicationStatusEnum;

class SignDocsObserver
{
    /**
     * Handle the SignedDocs "created" event.
     *
     * @param \App\Models\SignedDocs $signedDocs
     * @return void
     */
    public function created(SignedDocs $signedDocs)
    {
        //
    }

    /**
     * Handle the SignedDocs "updated" event.
     *
     * @param \App\Models\SignedDocs $signedDocs
     * @return void
     */
    public function updated(SignedDocs $signedDocs)
    {



        $allDocs = SignedDocs::where('application_id', $signedDocs->application->id)->get();
        $user = auth()->user();
        $allUsers = $allDocs->where('user_id', '!=', null)->map(function ($doc) {
            $role_id = $doc->user->role_id;
            return $role_id;
        });
        $agreedUsers = $allDocs->where('status', ApplicationMagicNumber::one)->map(function ($doc) {
            if (isset($doc->role_id)) {
                $role_id = $doc->role_id;
                return $role_id;
            }
        });
        $canceledUsers = $allDocs->where('status', ApplicationMagicNumber::zero)->whereNotNull('status')->map(function ($doc) {
            $role_id = $doc->role_id;
            return $role_id;
        });

        $roles_need_sign = json_decode($signedDocs->application->signers);

        if (in_array(7, $agreedUsers->toArray())) {
            $signedDocs->application->status = ApplicationStatusEnum::Agreed;
            $signedDocs->application->show_director = ApplicationMagicNumber::two;
            $signedDocs->application->show_leader = ApplicationMagicNumber::one;
        } elseif (in_array(7, $canceledUsers->toArray())) {
            $signedDocs->application->status = ApplicationStatusEnum::Rejected;
        } elseif ($canceledUsers->toArray() != null) {
            $signedDocs->application->status = ApplicationStatusEnum::Rejected;
            $signedDocs->application->show_leader = ApplicationMagicNumber::zero;
        } elseif ($canceledUsers->toArray() != null) {
            $signedDocs->application->status = ApplicationStatusEnum::Refused;
            $signedDocs->application->show_leader = ApplicationMagicNumber::zero;
        }elseif (count(array_diff($roles_need_sign, $agreedUsers->toArray())) == ApplicationMagicNumber::one && $signedDocs->application->is_more_than_limit == ApplicationMagicNumber::one) {
            $signedDocs->application->show_director = ApplicationMagicNumber::one;
            $signedDocs->application->status = ApplicationStatusEnum::In_Process;
        }elseif(array_diff($roles_need_sign, $agreedUsers->toArray()) == null && $signedDocs->application->is_more_than_limit != ApplicationMagicNumber::one){
            $signedDocs->application->show_leader = ApplicationMagicNumber::one;
            $signedDocs->application->status = ApplicationStatusEnum::In_Process;
        }else {
            $signedDocs->application->status = ApplicationStatusEnum::In_Process;
        }
        $signedDocs->application->update();
    }

    /**
     * Handle the SignedDocs "deleted" event.
     *
     * @param \App\Models\SignedDocs $signedDocs
     * @return void
     */
    public function deleted(SignedDocs $signedDocs)
    {
        //
    }

    /**
     * Handle the SignedDocs "restored" event.
     *
     * @param \App\Models\SignedDocs $signedDocs
     * @return void
     */
    public function restored(SignedDocs $signedDocs)
    {
        //
    }

    /**
     * Handle the SignedDocs "force deleted" event.
     *
     * @param \App\Models\SignedDocs $signedDocs
     * @return void
     */
    public function forceDeleted(SignedDocs $signedDocs)
    {
        //
    }
}
