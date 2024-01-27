<?php

namespace App\Services;

use App\Http\Controllers\EimzoController;
use App\Models\Branch;
use App\Models\Department;
use Illuminate\Support\Arr;
use App\Models\User;
use Teamprodev\Eimzo\Jobs\NotifyOperatorJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EriService {
    public function makeParams($data) {
        $fullname_parts = $this->parseFullname($data['eri_fullname']);
        // #TODO: check table columns
        return [
            'username' => $data['eri_sn'],
            'fullname' => $data['eri_fullname'],
            'firstname' => $fullname_parts['firstname'],
            'lastname' => $fullname_parts['lastname'],
            'midname' => $fullname_parts['midname'],
            'pinfl' => $data['eri_pinfl'],
            // 'inn' => $data['eri_inn'],
            'passport' => null,
            'passport_expire_date' => null,
            'phone' => null,
            'address' => null,
            'password' => Hash::make(uniqid()),
            'auth_type' => 'eri',
            'status' => 1,
            'user_type' => 'Jismoniy shaxs'
        ];
    }

    public function authorizeUser($params)
    {
        // CREATE NEW OR UPDATE EXISTING USER
        $user = User::where(
            ['pinfl' => $params['pinfl']],
            $params
        )->first();
        if($user === null)
        {
            $controller = new EimzoController();
            return $controller->register($params);
        }
        // AUTHORIZE USER
        Auth::login($user);
        return redirect()->route('site.applications.index');
    }

    public function change_key($params)
    {
        // CREATE NEW OR UPDATE EXISTING USER
        $user = User::where(
            ['pinfl' => $params['pinfl']],
            $params
        )->first();
        if($user === null)
        {
            $user = auth()->user();
            $user->update($params);
            return redirect()->route('site.applications.index');
        }
        return redirect()->back()->with('error', __('Bu Kalitga tegishli user bazada bor'));
    }

    protected function parseFullname($fullname) {
        $fullname_parts = explode(' ', $fullname);

        $data = [];
        $data['lastname'] = $fullname_parts[0];
        $data['firstname'] = $fullname_parts[1];
        $data['midname'] = "";
        $data['midname'] .= isset($fullname_parts[2]) ? $fullname_parts[2] : "";
        $data['midname'] .= isset($fullname_parts[3]) ? " " . $fullname_parts[3] : "";

        return $data;
    }
}
