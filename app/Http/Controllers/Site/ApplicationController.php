<?php

namespace App\Http\Controllers\Site;

use App\Enums\ApplicationMagicNumber;
use App\Enums\PermissionEnum;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\StatusExtended;
use App\Services\ApplicationService;
use App\Models\SignedDocs;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon as Date;
use Illuminate\Support\Facades\File;
use App\Enums\ApplicationStatusEnum;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    private ApplicationService $service;

    /**
     * @var ApplicationService
     */

    public function __construct(ApplicationService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    /**
     *
     * Function  show_status
     * @param string $status
     * @return  View
     */
    final public function show_status(string $status) : View
    {
        $voyager = Setting::where('key','admin.show_status')->first();
        $voyager->value = $status;
        $voyager->save();
        return view('site.applications.status');
    }

    /**
     * Performer Statusda bo'lgan Applicationlarni ko'rsatish
     *
     * @return View
     */
    final public function performer_status_get() : View
    {
        $status = StatusExtended::pluck('name','id')->toArray();
        return view('site.applications.performer_status',compact('status'));
    }

    /**
     *
     * Function  performer_status_post
     * @param Request $req
     * @return  RedirectResponse
     */
    final public function performer_status_post(Request $req) : RedirectResponse
    {
        $voyager = Setting::where('key','admin.performer_status_get')->first();
        $voyager->value = $req->input('performer_status_get');
        $voyager->save();
        return redirect()->route('site.applications.performer_status_get');
    }

    /**
     *
     * Function  status_table
     * @return  JsonResponse
     * @throws Exception
     */
    final public function status_table() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->status_table($user);
    }

    /**
     * @throws Exception
     */
    final public function performer_status() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        $status = setting('admin.performer_status_get');
        return $this->service->performer_status($user,$status);
    }

    /**
     * All Application
     *
     * Hamma Applicationlar(Zayavkalar)
     *
     * @return View
     */
    final public function index() : View
    {
        $user = auth()->user();
        return view('site.applications.index',['user' => $user]);
    }

    /**
     *
     * Function  index_getData
     * @return  JsonResponse
     * @throws Exception
     */
    final public function index_getData() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->index_getData($user);
    }
    /**
     * O'zi yaratgan Applicationlar(Zayavkalar)
     *
     * @return View
     */
    final public function my_applications() : View
    {
        $user = auth()->user();
        return view('site.applications.my_applications',['user' => $user]);
    }

    /**
     *
     * Function  my_applications_getData
     * @return  JsonResponse
     * @throws Exception
     */
    final public function my_applications_getData() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->my_applications_getData($user);
    }

    /**
     * Application Clone(Nusxalash)
     *
     * @param int $id
     * @return RedirectResponse
     */
    final public function clone(int $id) : RedirectResponse
    {
        $this->middleware('application_clone');
        return $this->service->clone($id,auth()->user());
    }

    /**
     * Application Show
     *
     * @param Application $application
     * @param bool $view
     * @return View
     */
    final public function show(Application $application, bool $view = false) : View
    {
        if (isset($view)) {
            Notification::query()
                ->where('application_id', $application->id)
                ->where('user_id', auth()->id())
                ->update(['is_read' => ApplicationMagicNumber::one]);
        }
        $compact = $this->service->show($application, auth()->user());
        return view('site.applications.show', $compact);
    }

    /**
     * @throws Exception
     * @var int $application ga tegishli bolgan SignedDocs
     */
    final public function SignedDocs(int $application) : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        $data = SignedDocs::where('application_id',$application);
        return $this->service->SignedDocs($data, $user);
    }

    /**
     * $application ga tegishli bolgan SignedDocs ni o'chirish
     * @param SignedDocs $signedocs_id
     * @param Application $application_id
     * @return RedirectResponse
     */
    final public function SignedDocsDelete(SignedDocs $signedocs_id,Application $application_id) : RedirectResponse
    {
        $this->service->SignedDocsDelete($signedocs_id,$application_id);
        return redirect()->route('site.applications.show',$application_id->id);
    }

    /**
     * Application Image Upload
     *
     * @param Request $request
     * @param Application $application
     * @return bool
     */
    final public function uploadImage(Request $request, Application $application) : bool
    {
        return $this->service->uploadImage($request,$application);
    }

    /**
     * Application Create
     *
     * @return RedirectResponse
     */
    final public function create() : RedirectResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->create($user);
    }

    /**
     * Application Edit
     *
     * @param Application $application
     * @return View|RedirectResponse
     */
    final public function edit(Application $application) : View | RedirectResponse
    {
        /** @var object $user*/
        $user = auth()->user();
//        if(((($application->performer_role_id !== null) && ($application->performer_role_id !== auth()->user()->role_id)) && $user->hasPermission(PermissionEnum::Warehouse)) || $application->show_leader === 2)
//        {
//            abort(405,__("Вам нельзя изменить заявку,ибо заявка уже подписана!"));
//        }
        if ($user->id !== $application->user_id && !$user->hasPermission(PermissionEnum::Warehouse) && !$user->hasPermission(PermissionEnum::Company_Performer) && !$user->hasPermission(PermissionEnum::Branch_Performer)) {
            return redirect()->route('site.applications.index');
        }
        $compact = $this->service->edit($application,$user);
        return view('site.applications.edit', $compact);

    }

    /**
     * Application Update
     *
     * @param Application $application
     * @param ApplicationRequest $request
     * @return RedirectResponse
     */
    final public function update(Application $application, ApplicationRequest $request) : RedirectResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->update($application,$request,$user);
    }
    final public function edit_update(Application $application, ApplicationRequest $request) : RedirectResponse
    {
        return $this->service->edit_update($application,$request,auth()->user());
    }
    /**
     * Chernovik bo'lgan applicationlarni ko'rish
    */
    final public function show_draft() : View
    {
        return view('site.applications.draft');
    }

    /**
     *
     * Function  show_draft_getData
     * @return  JsonResponse
     * @throws Exception
     */
    final public function show_draft_getData() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->show_draft_getData($user);
    }

    /**
     * soft delete post
     *
     * Function destroy
     * @param Application $application
     * @return RedirectResponse
     */
    final public function destroy(Application $application) : RedirectResponse
    {
        $application->notifications()->get()->map(function($notification){
            return $notification->delete();
        });
        $application->delete();
        return redirect()->route('site.applications.index');
    }

    /**
     * application is_more_than_limit update
     *
     * application is_more_than_limit update qilinishi
     * kelayotgan request ga qarab company yoki filialga ketishi
     * agar request 1 kelsa bu company dan ketadi va application branch_initiator_id 9 ga ozgaradi.
     * Chunki 9 id bu Company AK UZBEKTELECOM
     *
     * Kelayotgan request 0 bo'lsa unda shu applicationni create qilgan userni filiali branch_initiator_id ga tushadi.
     *
     * @param Application $application
     * @param Request $request
     * @return RedirectResponse
     */
    final public function is_more_than_limit(Application $application,Request $request) : RedirectResponse
    {
        /**@var object $user*/
        $user = auth()->user();
        $this->service->is_more_than_limit($application, $request, $user);
        return redirect()->back();
    }

    /**
     *
     * Function  to_sign
     * @return  View
     */
    final public function to_sign() : View
    {
        return view('site.applications.to_sign');
    }

    /**
     *
     * Function  to_sign_data
     * @return  JsonResponse
     * @throws Exception
     */
    final public function to_sign_data() : JsonResponse
    {
        /** @var object $user*/
        $user = auth()->user();
        return $this->service->to_sign_data($user);
    }

    /**
     *   File delete
     *
     * @param Request $request
     * @param Application $application
     * @param string $column
     * @return RedirectResponse
     */
    final public function file_delete(Request $request, Application $application, string $column) : RedirectResponse
    {
        $file = json_decode($application->$column, true);
        $delete = array_diff($file,[$request->input('file')]);
        $application->$column = $delete;
        $application->save();
        $file = public_path() . "/storage/uploads/{$request->input('file')}";
        $file_ext = File::extension($file);
        $file_rename = str_replace($file_ext, '', $request->input('file'));
        File::move($file, public_path() . "/storage/backups/$file_rename" . Date::now()->format('Y-m-d-H-i-s') . '.' . $file_ext);
        return redirect()->back();
    }

    /**
     *
     * Function  change_status
     * @return  bool
     */
    final public function change_status() : bool
    {
        $applications = Application::where('performer_role_id','!=')->where('status',ApplicationStatusEnum::In_Process)->get();
        foreach ($applications as $application) {
            $application->status = ApplicationStatusEnum::Distributed;
            $application->show_leader = ApplicationMagicNumber::two;
            $application->save();
        }

        $signed_docs = Application::where('draft','!=',ApplicationMagicNumber::one)->get();
        foreach($signed_docs as $docs) {
            $signedDocsId = SignedDocs::where('application_id',$docs->id)->get();

            $agreedUsers = $signedDocsId->where('status', ApplicationMagicNumber::one)->map(function ($doc) {
                return $doc->role_id;
            });
            $canceledUsers = $signedDocsId->where('status', ApplicationMagicNumber::zero)->whereNotNull('status')->map(function ($doc) {
                return $doc->role_id;
            });
            $roles_need_sign = json_decode($docs->signers);
            switch (true){
                case in_array(ApplicationMagicNumber::Director, $agreedUsers->toArray()) && $docs->status === ApplicationStatusEnum::In_Process:
                    $docs->status = ApplicationStatusEnum::Agreed;
                    $docs->show_director = ApplicationMagicNumber::two;
                    $docs->show_leader = ApplicationMagicNumber::one;
                    break;
                case count(array_diff($roles_need_sign, $agreedUsers->toArray())) === ApplicationMagicNumber::one && $docs->is_more_than_limit === ApplicationMagicNumber::one && $docs->show_leader === null && $docs->status === ApplicationStatusEnum::In_Process :
                    $docs->show_director = ApplicationMagicNumber::one;
                    $docs->status = ApplicationStatusEnum::In_Process;
                    break;
                case  array_diff($roles_need_sign, $agreedUsers->toArray()) === null && $docs->is_more_than_limit !== ApplicationMagicNumber::one && $docs->show_leader === null && $docs->status === ApplicationStatusEnum::In_Process :
                    $docs->show_leader = ApplicationMagicNumber::one;
                    $docs->status = ApplicationStatusEnum::In_Process;
                    break;
                case array_diff($roles_need_sign, $agreedUsers->toArray()) !== null && $docs->show_leader === ApplicationMagicNumber::one && $docs->status === ApplicationStatusEnum::In_Process :
                    $docs->show_leader = null;
                    $docs->performer_role_id = null;
                    $docs->performer_received_date = null;
                    $docs->performer_comment = null;
                    $docs->performer_comment_date = null;
                    break;
                case  in_array(ApplicationMagicNumber::Director, $canceledUsers->toArray()) && $docs->show_leader === null :
                    $docs->status = ApplicationStatusEnum::Rejected;
                    break;
            }
            return $docs->save();
        }
        return true;
    }

    public function daterangepicker(Request $request): Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $applications = Application::whereBetween('created_at', [$request->startDate, $request->endDate])->get();
        return view('site.daterangepicker', ['applications' => $applications]);
    }
}
