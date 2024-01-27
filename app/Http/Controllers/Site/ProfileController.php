<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Jobs\UpdateProfileJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    final public function index() : View
    {
        $user = auth()->user();
        return view('site.profile.profile',['user' => $user]);
    }

    /**
     *
     * Function  other
     * @param User $id
     * @return  View
     */
    final public function other($id) : View
    {
        $user = User::withTrashed()->find($id);
        return view('site.profile.other',['user' => $user]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $profileRequest)
    {
        try {
            $this->dispatchNow(new UpdateProfileJob($profileRequest));
            return redirect()->route('site.profile.index')->with('success', trans('site.application_success'));
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('danger', trans('site.application_failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Change Eimzo Key
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    final public function change_key(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('site.profile.change_key');
    }
}
