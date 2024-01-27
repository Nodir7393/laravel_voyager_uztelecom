<?php


namespace App\Services;


use App\Enums\ApplicationMagicNumber;
use App\Enums\ApplicationStatusEnum;
use App\Enums\PermissionEnum;
use App\Events\Notify;
use App\Models\Application;
use App\Models\Branch;
use App\Models\Country;
use App\Models\Notification;
use App\Models\PermissionRole;
use App\Models\Position;
use App\Models\Purchase;
use App\Models\Resource;
use App\Models\Roles;
use App\Models\SignedDocs;
use App\Models\StatusExtended;
use App\Models\Subject;
use App\Models\User;
use App\Models\Warehouse;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class ApplicationService
{
    /**
     * Permissionlarga qarab Applicationlar chiqishi
     * @throws Exception
     */
    final public function index_getData(object $user) : JsonResponse
    {
        if ($user->hasPermission(PermissionEnum::Company_Leader) || $user->hasPermission(PermissionEnum::Branch_Leader)) {
            $a = 'branch_initiator_id';
            $b = [$user->branch_id];
        } else {
            $a = 'department_initiator_id';
            $b = [$user->department_id];
        }
        $query = Application::query()->orWhere('user_id', $user->id);
        $application = $query->where('draft', '!=', ApplicationMagicNumber::one)->where('planned_price', '!=', null)->whereIn($a, $b);
        $optional_signers = $user->hasPermission(PermissionEnum::Add_Company_Signer) || $user->hasPermission(PermissionEnum::Add_Company_Signer);
        $required_signers = $user->hasPermission(PermissionEnum::Company_Signer) || $user->hasPermission(PermissionEnum::Branch_Signer);
        $leaders_in_signer =
            (($user->hasPermission(PermissionEnum::Branch_Leader) && $required_signers) || ($user->hasPermission(PermissionEnum::Branch_Leader) && $optional_signers))
            ||
            (($user->hasPermission(PermissionEnum::Company_Leader) && $required_signers) || ($user->hasPermission(PermissionEnum::Company_Leader) && $optional_signers));
        switch (true) {
            case $user->hasPermission(PermissionEnum::Purchasing_Management_Center) || $leaders_in_signer:
                if ($user->hasPermission(PermissionEnum::Purchasing_Management_Center)) {
                    $query->where('draft', '!=', ApplicationMagicNumber::one)
                        ->where('planned_price', '!=', null)
                        ->orWhere('signers', 'like', "%$user->role_id%")
                        ->get();
                }elseif($leaders_in_signer){
                    $query = Application::query()->where('draft', '!=', ApplicationMagicNumber::one)
                        ->where('planned_price', '!=', null)
                        ->where('branch_id', $user->branch_id)
                        ->OrWhere('signers', 'like', "%$user->role_id%")
                        ->OrWhere('user_id', $user->id)
                        ->where('draft', '!=', ApplicationMagicNumber::one)
                        ->get();
                }
                break;
            case $user->hasPermission(PermissionEnum::Company_Signer) || $user->hasPermission(PermissionEnum::Add_Company_Signer) || $user->hasPermission(PermissionEnum::Branch_Signer) || $user->hasPermission(PermissionEnum::Add_Branch_Signer):
                $signedDocs = SignedDocs::where('role_id', $user->role_id)->pluck('application_id')->toArray();
                $query = Application::query()->whereIn('id',$signedDocs)
                    ->where('planned_price', '!=', null)
                    ->orWhere('performer_role_id', $user->role->id)
                    ->OrWhere('user_id', $user->id)
                    ->where('draft', '!=', ApplicationMagicNumber::one)
                    ->get();
                break;
            case $user->hasPermission(PermissionEnum::Warehouse) :
                $query->where('branch_id', $user->branch_id)->where('show_leader', ApplicationMagicNumber::two)->get();
                break;
            case $user->hasPermission(PermissionEnum::Branch_Performer) :
            case $user->hasPermission(PermissionEnum::Company_Performer) :
                 $query = Application::query()->where('performer_role_id', $user->role_id)->Orwhere('user_id', $user->id)->where('draft', '!=', ApplicationMagicNumber::one)->get();
                break;
            default :
                $query = $application->get();
        }
        return Datatables::of($query)
            ->editColumn('is_more_than_limit', function ($query) {
                return (int)$query->is_more_than_limit === ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('user_id', function ($query) {
                $user = Cache::get('users')->find($query->user_id);
                return Cache::get('branches')->find($user->branch_id)->name;
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return Cache::get('branches')->find($query->branch_id)->name;
            })
            ->editColumn('updated_at', function ($query) {
                return with(new Carbon($query->updated_at))->format('d.m.Y');
            })
            ->editColumn('date', function ($query) {
                return $query->date ? with(new Carbon($query->date))->format('d.m.Y'): '';
            })
            ->editColumn('delivery_date', function ($query) {
                return $query->delivery_date ? with(new Carbon($query->delivery_date))->format('d.m.Y') : with(new Carbon($query->update_at = Carbon::now()->addMonth()))->format('d.m.Y');
            })
            ->editColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->editColumn('with_nds', function ($query) {
                return $query->with_nds ? 'С НДС' : 'Без НДС';
            })
            ->addColumn('planned_price_curr', function ($query) {
                $planned_price = $query->planned_price ?? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ');
                return $planned_price;
            })
            ->editColumn('status', function ($query) {
                /*
                 *  Voyager admin paneldan status ranglarini olish va chiqarish
                 */
                $status_extended = StatusExtended::find($query->performer_status);
                $status = match (true) {
                    $query->status === ApplicationStatusEnum::Order_Arrived => 'товар прибыл',
                    $query->status === ApplicationStatusEnum::Order_Delivered => 'товар доставлен',
                    $query->performer_status !== null => $status_extended->name,
                    default => $query->status
                };
                $color_status_if = ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Arrived) || ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Delivered);
                $color = $color_status_if ? $status_extended->color : setting("color.$status");

                return json_encode(['backgroundColor' => $color, 'app' => $this->translateStatus($status), 'color' => $color ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user) {
                $user_get = Cache::get('users')->find($user->id);
                if ($user_get->id === $row->user_id || $user_get->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user_get->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user_get->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused && $row->status !== ApplicationStatusEnum::Rejected) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
    final public function my_applications_getData(object $user) : JsonResponse
    {
        $query = Application::where('user_id',$user->id)->whereNotNull('planned_price')->get();

        return Datatables::of($query)
            ->editColumn('is_more_than_limit', function ($query) {
                return (int)$query->is_more_than_limit === ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('user_id', function ($query) {
                $user = Cache::get('users')->find($query->user_id);
                return Cache::get('branches')->find($user->branch_id)->name;
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return Cache::get('branches')->find($query->branch_id)->name;
            })
            ->editColumn('updated_at', function ($query) {
                return with(new Carbon($query->updated_at))->format('d.m.Y');
            })
            ->editColumn('date', function ($query) {
                return $query->date ? with(new Carbon($query->date))->format('d.m.Y'): '';
            })
            ->editColumn('delivery_date', function ($query) {
                return $query->delivery_date ?? with(new Carbon($query->delivery_date))->format('d.m.Y');
            })
            ->editColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->addColumn('planned_price_curr', function ($query) {
                $planned_price = $query->planned_price ?? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ');
                return $planned_price;
            })
            ->editColumn('with_nds', function ($query) {
                return $query->with_nds ? 'С НДС' : 'Без НДС';
            })
            ->editColumn('status', function ($query) {
                /*
                 *  Voyager admin paneldan status ranglarini olish va chiqarish
                 */
                $status_extended = StatusExtended::find($query->performer_status);
                $status = match (true) {
                    $query->status === ApplicationStatusEnum::Order_Arrived => 'товар прибыл',
                    $query->status === ApplicationStatusEnum::Order_Delivered => 'товар доставлен',
                    $query->performer_status !== null => $status_extended->name,
                    default => $query->status
                };
                $color_status_if = ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Arrived) || ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Delivered);
                $color = $color_status_if ? $status_extended->color : setting("color.$status");

                return json_encode(['backgroundColor' => $color, 'app' => $this->translateStatus($status), 'color' => $color ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user) {
                $user_get = Cache::get('users')->find($user->id);
                if ($user_get->id === $row->user_id || $user_get->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user_get->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user_get->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused && $row->status !== ApplicationStatusEnum::Rejected) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user_get->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * User tanlagan statusdagi Applicationlarni chiqarish
     * @throws Exception
     */
    final public function status_table(object $user) : JsonResponse
    {
        $status = setting('admin.show_status');
        $application = Application::where('draft', '!=', ApplicationMagicNumber::one)->where('planned_price', '!=', null)->whereIn('branch_initiator_id', [$user->branch_id]);
        $signedDocs = SignedDocs::where('role_id', $user->role_id)->pluck('application_id')->toArray();
        switch (true)
        {
            case $user->hasPermission(PermissionEnum::Purchasing_Management_Center):
                $query = Application::where('draft', '!=', ApplicationMagicNumber::one)->where('planned_price', '!=', null)->where('status', $status)->get();
                break;
            case $user->hasPermission(PermissionEnum::Branch_Leader) && $status === ApplicationStatusEnum::Distributed:
            case $user->hasPermission(PermissionEnum::Company_Leader) && $status === ApplicationStatusEnum::Distributed:
                $query = $application->orWhere('user_id', $user->id)->get();
                break;
            default:
                $query = Application::whereIn('id',$signedDocs)
                    ->where('planned_price', '!=', null)
                    ->where('status', $status)
                    ->orWhere('user_id', $user->id)
                    ->where('status', $status)
                    ->where('name', '!=', null)
                    ->get();
        }
        return Datatables::of($query)
            ->editColumn('is_more_than_limit', function ($query) {
                return $query->is_more_than_limit === ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return Cache::get('branches')->find($query->branch_id)->name;
            })
            ->addIndexColumn()
            ->editColumn('user_id', function ($query) {
                $user = Cache::get('users')->find($query->user_id);
                return Cache::get('branches')->find($user->branch_id)->name;
            })
            ->editColumn('role_id', function ($docs) {
                return $docs->role ? $docs->role->display_name : "";
            })
            ->editColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->editColumn('delivery_date', function ($query) {
                return $query->updated_at ? with(new Carbon($query->delivery_date))->format('d.m.Y') : '';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? with(new Carbon($data->updated_at))->format('d.m.Y') : '';
            })
            ->addColumn('planned_price_curr', function ($query) {
                $planned_price = !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
                return $planned_price;
            })
            ->editColumn('with_nds', function ($query) {
                return $query->with_nds ? 'С НДС' : 'Без НДС';
            })
            ->editColumn('status', function ($query) {
                /*
                 *  Voyager admin paneldan status ranglarini olish va chiqarish
                 */

                return json_encode(['backgroundColor' => setting("color.$query->status"), 'app' => $this->translateStatus($query->status), 'color' => setting("color.$query->status") ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user) {
                $user_get = Cache::get('users')->find($user->id);
                if ($user->id === $row->user_id || $user->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user_get->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused && $row->status !== ApplicationStatusEnum::Rejected) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * User tanlagan Performer_Statusga qarab Applicationlar show bo'lishi
     * @throws Exception
     */
    final public function performer_status(object $user,string $status) : JsonResponse
    {
        $signedDocs = SignedDocs::where('role_id', $user->role_id)->pluck('application_id')->toArray();
        switch (true)
        {
            case $user->hasPermission(PermissionEnum::Purchasing_Management_Center):
                $query = Application::where('draft', '!=', ApplicationMagicNumber::one)->where('planned_price', '!=', null)->where('performer_status', $status)->get();
                break;
            case $user->hasPermission(PermissionEnum::Branch_Leader):
            case $user->hasPermission(PermissionEnum::Company_Leader):
                $query = Application::where('branch_leader_user_id',$user->id)->orWhere('user_id', $user->id)
                ->where('performer_status', $status)->get();
                break;
            default:
                $query = Application::whereIn('id',$signedDocs)
                    ->where('planned_price', '!=', null)
                    ->where('performer_status', $status)
                    ->orWhere('user_id', $user->id)
                    ->where('performer_status', $status)
                    ->orWhere('performer_role_id', $user->role_id)
                    ->where('performer_status', $status)
                    ->where('name', '!=', null)
                    ->get();
        }
        return Datatables::of($query)
            ->editColumn('is_more_than_limit', function ($query) {
                return $query->is_more_than_limit == ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return Cache::get('branches')->find($query->branch_id)->name;
            })
            ->addIndexColumn()
            ->editColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->editColumn('user_id', function ($query) {
                $user = Cache::get('users')->find($query->user_id);
                return Cache::get('branches')->find($user->branch_id)->name;
            })
            ->editColumn('delivery_date', function ($query) {
                return $query->delivery_date ? with(new Carbon($query->delivery_date))->format('d.m.Y') : '';
            })
            ->addColumn('planned_price_curr', function ($query) {
                $planned_price = !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
                return $planned_price;
            })
            ->editColumn('with_nds', function ($query) {
                return $query->with_nds ? 'С НДС' : 'Без НДС';
            })
            ->editColumn('status', function ($query) {
                /*
                 *  Voyager admin paneldan status ranglarini olish va chiqarish
                 */
                $status_extended = StatusExtended::find($query->performer_status);
                $status = match (true) {
                    $query->status === ApplicationStatusEnum::Order_Arrived => 'товар прибыл',
                    $query->status === ApplicationStatusEnum::Order_Delivered => 'товар доставлен',
                    $query->performer_status !== null => $status_extended->name,
                    default => $query->status
                };
                $color_status_if = ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Arrived) || ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Delivered);
                $color = $color_status_if ? $status_extended->color : setting("color.$status");

                return json_encode(['backgroundColor' => $color, 'app' => $this->translateStatus($status), 'color' => $color ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user){
                if ($user->id === $row->user_id || $user->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    /**
     * Application Clone(Nusxalash)
     * @param int $id
     * @return RedirectResponse
     */
    final public function clone(int $id, object $user) : RedirectResponse
    {
        $clone = Application::findOrFail($id);
        $application = $clone->replicate();
        $application->signers = null;
        $application->status = ApplicationStatusEnum::New;
        $application->save();
        Log::info("$user->name cloned his Application",[
            'user_id' => $user->id,
            'user_name' => $user->name,
            'application_user_id' => $application->user_id,
            'application_status' => $application->status,
            'application_performer_status' => $application->performer_status,
            'application_branch_id' => $application->branch_id,
            'application_branch_name' => $application->branch->name,
            'application_id' => $application->id,
        ]);
        return redirect()->back();
    }

    /**
     * @param object $data
     * @return JsonResponse
     * @throws Exception
     */
    final public function SignedDocs(object $data, object $user) : JsonResponse
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('user_id', function ($docs) {
                return $docs->user ? $docs->user->name : "";
            })
            ->editColumn('role_id', function ($docs) {
                return $docs->role ? $docs->role->display_name : "";
            })
            ->editColumn('updated_at', function ($query) {
                return $query->updated_at ? with(new Carbon($query->updated_at))->format('d.m.Y') : '';
            })
            ->editColumn('status', function ($status) {
                $status_agreed = __('agreed');
                $status_rejected = __('rejected');
                $status_not_signed = __('Не подписан');

                match ($status->status) {
                    0 => $status_signer = $status_rejected,
                    1 => $status_signer = $status_agreed,
                    default => $status_signer = $status_not_signed,
                };
                return $status_signer;
            })
            ->addColumn('action', function ($row) use ($user) {
                $data = [];
                $branch = Cache::get('branches')->find($row->application->branch_initiator_id);
                if ($row->application->user_id === $user->id && !in_array($row->role_id, json_decode($branch->signers))) {
                    $data['destroy'] = route('site.applications.delete.signedocs', [$row->id,$row->application->id]);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     *
     * Function  SignedDocsDelete
     *
     * @param object $signedDocs
     * @param object $application
     * @return  bool
     */
    final public function SignedDocsDelete(object $signedDocs, object $application) : bool
    {
        $signers = json_decode($application->signers);
        $array = array_diff($signers,array($signedDocs->role_id));
        foreach ($array as $signer)
        {
            $signers2[] = $signer;
        }
        $application->signers = $signers2;
        $application->save();
        return $signedDocs->delete();
    }

    /**
     * Application Create
     * @param object $user auth user
     * @return RedirectResponse
     */
    final public function create(object $user) : RedirectResponse
    {
        $application = new Application();
        $application->user_id = $user->id;
        $application->user_role_id = $user->role_id;
        $application->branch_initiator_id = $user->branch_id;
        $application->branch_id = $user->branch_id;
        $application->department_initiator_id = $user->department_id;
        $application->status = ApplicationStatusEnum::New;
        $result = $application->save();
        Log::info("$user->name created Application",[
            'user_id' => $user->id,
            'user_name' => $user->name,
            'branch_id' => $application->branch_id,
            'branch_name' => $application->branch->name,
            'result' => $result,
            'application_id' => $application->id,
        ]);
        return redirect()->route('site.applications.edit', $application->id);
    }

    /**
     * Draft(Chernovik) Applicationlarni chiqazish
     * @param object $user
     * @return JsonResponse
     * @throws Exception
     */
    final public function show_draft_getData(object $user) : JsonResponse
    {
        $data = Application::where('user_id', $user->id)
            ->whereDraft(ApplicationMagicNumber::one);
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? with(new Carbon($data->created_at))->format('d.m.Y') : '';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? with(new Carbon($data->updated_at))->format('d.m.Y') : '';
            })
            ->addColumn('action', function ($row) use ($user) {
                if ($user->id === $row->user_id || $user->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Image upload
     * @param object $request
     * @param object $application
     * @return bool
     */
    final public function uploadImage(object $request,object $application) : bool
    {
        $file_basis = json_decode($application->file_basis);
        $file_tech_spec = json_decode($application->file_tech_spec);
        $other_files = json_decode($application->other_files);
        $performer_file = json_decode($application->performer_file);
        if ($request->hasFile('file_basis')) {
            $fileName = time() . '_' . $request->file_basis->getClientOriginalName();
            $filePath = $request->file('file_basis')
                ->move(public_path("storage/uploads/"), $fileName);

            $file_basis[] = $fileName;
        }
        if ($request->hasFile('file_tech_spec')) {
            $fileName = time() . '_' . $request->file_tech_spec->getClientOriginalName();
            $filePath = $request->file('file_tech_spec')
                ->move(public_path("storage/uploads/"), $fileName);

            $file_tech_spec[] = $fileName;
        }
        if ($request->hasFile('other_files')) {
            $fileName = time() . '_' . $request->other_files->getClientOriginalName();
            $filePath = $request->file('other_files')
                ->move(public_path("storage/uploads/"), $fileName);

            $other_files[] = $fileName;
        }
        if ($request->hasFile('performer_file')) {
            $fileName = time() . '_' . $request->performer_file->getClientOriginalName();
            $filePath = $request->file('performer_file')
                ->move(public_path("storage/uploads/"), $fileName);

            $performer_file[] = $fileName;
        }

        $application->file_basis = json_encode($file_basis);
        $application->performer_file = json_encode($performer_file);
        $application->file_tech_spec = json_encode($file_tech_spec);
        $application->other_files = json_encode($other_files);
        $application->update();
        return true;
    }

    /**
     *
     * Function  show
     * @param object $application
     * @param object $user
     * @return  array
     */
    final public function show(object $application, object $user) : array
    {
        $access = SignedDocs::where('role_id', $user->role_id)->whereNull('status')->where('application_id', $application->id)->first();
        $check = SignedDocs::where('role_id', $user->role_id)->where('application_id', $application->id)->first();
        $signedDocs = $application->signedDocs()->get();
        $file_basis = json_decode($application->file_basis);
        $file_tech_spec = json_decode($application->file_tech_spec);
        $other_files = json_decode($application->other_files);
        $performer_file = json_decode($application->performer_file);
        $same_role_user_ids = User::where('role_id', $user->role_id)->get()->pluck('id')->toArray();
        $products_id = [];
        if ($application->resource_id !== null)
            foreach (json_decode($application->resource_id) as $product_id) {
                $products_id[] = Resource::find($product_id)->name;
            }
        /*
         * @var id
         * @var application branch_initiator_id bo'yicha role larni oladi
         *
         * foreach da shu role lardan
         * Permissionni Company_Performer va Branch_Performer bo'lganlarini ovotti
         */

        $id = DB::table('roles')->whereRaw('json_contains(branch_id, \'["' . $application->branch_id . '"]\')')->pluck('id')->toArray();

        foreach ($id as $role) {
            $role_company[] = PermissionRole::where('role_id', $role)->where('permission_id', ApplicationMagicNumber::Company_Performer)->pluck('role_id');

            $role_company = array_diff($role_company, ['[]']);

            $branch = PermissionRole::where('role_id', $role)->where('permission_id', ApplicationMagicNumber::Branch_Performer)->pluck('role_id');

            $role_branch[] = $branch;
            $role_branch = array_diff($role_branch, ['[]']);
        }
        $performers_company = $id ? Roles::find($role_company)->pluck('display_name', 'id') : [];
        $performers_branch = $id ? Roles::find($role_branch)->pluck('display_name', 'id') : [];
        $access_comment = Position::find($user->position_id);
        $subjects = Subject::all();
        $purchases = Purchase::all();
        $branch_name = Branch::find($application->user->branch_id);
        $branch = Branch::all()->pluck('name', 'id');
        $perms['CompanyLeader'] = $user->hasPermission(PermissionEnum::Company_Leader) && (int)$application->show_leader === ApplicationMagicNumber::one;
        $perms['BranchLeader'] = $user->hasPermission(PermissionEnum::Branch_Leader) && (int)$application->show_leader === ApplicationMagicNumber::one;
        $perms['ChangePerformer'] = $user->hasPermission(PermissionEnum::Branch_Leader) && (int)$application->show_leader === ApplicationMagicNumber::two;
        $perms['PerformerComment'] = $application->performer_role_id === $user->role_id && (int)$user->leader === ApplicationMagicNumber::zero;
        $perms['NumberChange'] = $user->hasPermission(PermissionEnum::Number_Change) && !$user->hasPermission(PermissionEnum::Plan_Budget) && !$user->hasPermission(PermissionEnum::Plan_Business);
        $perms['Plan'] = $user->hasPermission(PermissionEnum::Plan_Business) && $check;
        $perms['PerformerLeader'] = $application->performer_role_id === $user->role_id && $user->leader === ApplicationMagicNumber::one;
        $perms['Signers'] = (($access && $user->hasPermission(PermissionEnum::Company_Signer || PermissionEnum::Add_Company_Signer || PermissionEnum::Branch_Signer || PermissionEnum::Add_Branch_Signer || PermissionEnum::Company_Performer || PermissionEnum::Branch_Performer)) || ($access && (int)$user->role_id === ApplicationMagicNumber::Director && $application->show_director === ApplicationMagicNumber::one)) && (int)$application->draft !== ApplicationMagicNumber::one;
        $status = match (true) {
            $application->status === ApplicationStatusEnum::Order_Arrived => 'товар прибыл',
            $application->status === ApplicationStatusEnum::Order_Delivered => 'товар доставлен',
            $application->performer_status !== null => StatusExtended::find($application->performer_status)->name,
            default => $application->status
        };
        $color_status_if = ($application->performer_status !== null && $application->status !== ApplicationStatusEnum::Order_Arrived) || ($application->performer_status !== null && $application->status !== ApplicationStatusEnum::Order_Delivered);
        $color_status = $color_status_if ? StatusExtended::find($application->performer_status)->color : setting("color.$status");

        $application_user_role = Roles::find($application->user_role_id);
        Log::info("$user->name opened the Application",[
            'user_id' => $user->id,
            'application_user_id' => $application->user_id,
            'application_status' => $application->status,
            'application_performer_status' => $application->performer_status,
            'user_name' => $user->name,
            'application_branch_id' => $application->branch_id,
            'application_branch_name' => $application->branch->name,
            'application_id' => $application->id,
        ]);
        return ['products_id' => $products_id, 'performer_file' => $performer_file, 'perms' => $perms, 'access_comment' => $access_comment, 'performers_company' => $performers_company, 'performers_branch' => $performers_branch, 'file_basis' => $file_basis, 'file_tech_spec' => $file_tech_spec, 'other_files' => $other_files, 'user' => $user, 'application' => $application, 'branch' => $branch, 'signedDocs' => $signedDocs, 'same_role_user_ids' => $same_role_user_ids, 'access' => $access, 'subjects' => $subjects, 'purchases' => $purchases, 'branch_name' => $branch_name, 'check' => $check, 'status' => $status, 'color_status' => $color_status, 'application_user_role' => $application_user_role];
    }

    /**
     *
     * Function  edit
     * @param object $application
     * @param object $user
     * @return  array
     */
    final public function edit(object $application, object $user) : array
    {
        $status_extented = StatusExtended::pluck('name', 'id')->toArray();
        $countries = ['0' => 'Select country'];
        $countries[] = Country::pluck('country_name', 'country_alpha3_code')->toArray();
        $select = Resource::pluck('name', 'id');
        $performer_file = json_decode($application->performer_file);
        $branch_signer = json_decode($application->branch->add_signers);
        $addsigner = Branch::find(ApplicationMagicNumber::Company);
        $company_signer = json_decode($addsigner->add_signers);
        $products_id = [];
        if ($application->resource_id !== null)
            foreach (json_decode($application->resource_id) as $product_id) {
                $products_id[] = Resource::find($product_id)->name;
            }
        return [
            'products_id' => $products_id,
            'application' => $application,
            'purchase' => Purchase::all()->pluck('name', 'id'),
            'subject' => Subject::all(),
            'branch' => Branch::all()->pluck('name', 'id'),
            'status_extented' => $status_extented,
            'countries' => $countries,
            'component' => $this->checkComponentsInclude($application, $user),
            'products' => $select,
            'warehouse' => Warehouse::where('application_id', $application->id)->first(),
            'performer_file' => $performer_file,
            'user' => $user,
            'company_signers' => $company_signer ? Roles::find($company_signer)->sortBy('index')->pluck('display_name', 'id')->toArray() : null,
            'branch_signers' => $branch_signer ? Roles::find($branch_signer)->sortBy('index')->pluck('display_name', 'id')->toArray() : null,
        ];
    }

    /**
     *
     * Function  update
     * @param object $application
     * @param object $request
     * @param object $user
     * @return  RedirectResponse
     */
    final public function update(object $application, object $request, object $user) : RedirectResponse
    {
        $now = Carbon::now();
        $data = $request->validated();
        if (isset($data['performer_status'])) {
            $application->performer_user_id = $user->id;
            $application->status = ApplicationStatusEnum::Extended;
        }
        if (isset($data['performer_leader_comment'])) {
            $data['performer_leader_comment_date'] = $now->toDateTimeString();
            $data['performer_leader_user_id'] = $user->id;
        }
        if (isset($data['performer_comment'])) {
            $data['performer_comment_date'] = $now->toDateTimeString();
            $data['performer_user_id'] = $user->id;
        }
        if (isset($data['performer_role_id'])) {
            $data['performer_received_date'] = $now->toDateTimeString();
            $data['status'] = ApplicationStatusEnum::Distributed;
            $data['show_leader'] = ApplicationMagicNumber::two;
            $data['branch_leader_user_id'] = $user->id;
        }
        if(isset($data['show_leader']) && (int)$data['show_leader'] === 3)
        {
            $data['show_leader'] = ApplicationMagicNumber::one;
            $data['branch_leader_comment'] = null;
            $data['performer_role_id'] = null;
            $data['performer_user_id'] = null;
            $data['performer_received_date'] = null;
            $data['performer_comment'] = null;
            $data['performer_status'] = null;
            $data['status'] = (int)$application->is_more_than_limit === ApplicationMagicNumber::one ? ApplicationStatusEnum::Agreed : ApplicationStatusEnum::In_Process;
        }
        $result = $application->update($data);
        Log::info("$user->name updated Application",[
            'user_id' => $user->id,
            'application_user_id' => $application->user_id,
            'application_status' => $application->status,
            'application_performer_status' => $application->performer_status,
            'user_name' => $user->name,
            'branch_id' => $application->branch_id,
            'branch_name' => $application->branch->name,
            'result' => $result,
            'application_id' => $application->id,
        ]);
        if ($result)
            return redirect()->route('site.applications.show', $application->id);

        return redirect()->back()->with('danger', trans('site.application_failed'));
    }

    /**
     *
     * Function  edit_update
     * @param object $application Application Data
     * @param object $request ApplicationRequest
     * @param object $user auth()->user()
     * @return  RedirectResponse
     */
    final public function edit_update(object $application, object $request, object $user) : RedirectResponse
    {
        $data = $request->validated();
        /** @var string $roles create qilgan userning filialidagi Required Podpisantlar */
        $roles = ($application->branch_signers->signers);
        if (isset($data['signers'])) {
            $data['signers'] = $this->signers($data, $application, $roles);
            $this->createSignedDocs(json_decode($data['signers']), $application);
            $message = "$application->id " . "$application->name " . setting('admin.application_created');
            $this->sendNotifications(json_decode($roles), $application, $message);
        } elseif (!isset($data['signers']) && $application->signers === null) {
            $this->createSignedDocs(json_decode($roles), $application);
            $message = "$application->id " . "$application->name " . setting('admin.application_created');
            $this->sendNotifications(json_decode($roles), $application, $message);
            $data['signers'] = $roles;
        }
        if (isset($data['resource_id'])) {
            /** @var array $explode Product */
            $explode = explode(',', $data['resource_id']);
            $data['resource_id'] = json_encode($explode);
        }
        if ((int)$data['draft'] === ApplicationMagicNumber::one) {
            $data['status'] = ApplicationStatusEnum::Draft;
        } elseif ((int)$data['draft'] === ApplicationMagicNumber::zero && $application->status === ApplicationStatusEnum::Draft) {
            $data['status'] = ApplicationStatusEnum::New;
        } else {
            $data['status'] = $application->status;
        }
        /** @var bool $result */
        $result = $application->update($data);
        Log::info("$user->name updated his Application",[
            'user_id' => $user->id,
            'application_user_id' => $application->user_id,
            'application_status' => $application->status,
            'application_performer_status' => $application->performer_status,
            'user_name' => $user->name,
            'branch_id' => $application->branch_id,
            'branch_name' => $application->branch->name,
            'result' => $result,
            'application_id' => $application->id,
        ]);
        if ($result)
            return redirect()->route('site.applications.show', $application->id);

        return redirect()->back()->with('danger', trans('site.application_failed'));
    }

    /**
     *
     * Function  signers
     * @param array $data
     * @param object $application
     * @param string $roles
     */
    final protected function signers(array $data, object $application, string $roles)
    {
        $data['signers'] = $data['signers'] ?? [];
        /** @var array $array Application Signers */
        /** @var string $roles Application Create qilgan User filialidagi Signerlar */
        $array = $roles ? array_merge(json_decode($roles),$data['signers']) : $data['signers'];
        $data['signers'] = json_encode($array);
        return $data['signers'];
    }
    final protected function createSignedDocs(array $array,object $application) : bool
    {
        foreach ($array as $signers) {
            /** @var int $signers Role ID */
            $signer = SignedDocs::where('application_id', $application->id)->where('role_id', $signers)->first();
            $docs = new SignedDocs();
            $docs->role_id = $signers;
            $docs->role_index = Roles::find($signers)->index;
            $docs->application_id = $application->id;
            $docs->table_name = "applications";
            $signer !== null || $docs->save();
        }
        return true;
    }

    /**
     *
     * Function  is_more_than_limit
     * @param object $application
     * @param object $request
     * @return  bool
     */
    final public function is_more_than_limit(object $application, object $request, object $user) : bool
    {
        $application->is_more_than_limit = $request->is_more_than_limit;
        $application->signers = null;
        $branch_id = $user->branch_id;
        if ($request->is_more_than_limit == ApplicationMagicNumber::one) {
            $application->branch_initiator_id = ApplicationMagicNumber::Company;
        } else {
            $application->branch_initiator_id = $branch_id;
        }

        if ($request->is_more_than_limit == ApplicationMagicNumber::one) {
            $application->branch_id = ApplicationMagicNumber::Company;
        } else {
            $application->branch_id = $branch_id;
        }
        SignedDocs::where('application_id', $application->id)->delete();
        return $application->save();
    }

    public static function getNotifications()
    {
        $signedDocs = SignedDocs::where('role_id', auth()->user()->role_id)->whereNull('status')->pluck('application_id')->toArray();
        /** @var object $data  Podpis qoyilishi kerak bo'lgan zayavkalar*/
        $data = Notification::whereIn('application_id',$signedDocs)
            ->where('is_read', 0)
            ->where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get();
//        Notification::where('is_read', 0)
//            ->where('user_id', auth()->id())
//            ->with('application')
//            ->orderBy('id', 'desc')
//            ->get();
        return $data;

    }

    public function sendNotifications($array, $application, $message)
    {
        if ($array !== null) {
            $user_ids = User::query()->whereIn('role_id', $array)->pluck('id')->toArray();
            foreach ($user_ids as $user_id) {
                $notification = Notification::query()->firstOrCreate(['user_id' => $user_id, 'application_id' => $application->id, 'message' => $message]);
                if ($notification->wasRecentlyCreated) {
                    $diff = now()->diffInMinutes($application->created_at);
                    $data = [
                        'id' => $application->id,
                        'time' => $diff === ApplicationMagicNumber::zero ? 'recently' : $diff
                    ];
                        broadcast(new Notify(json_encode($data, $assoc = true), $user_id))->toOthers();     // notification
                }
            }
        }

    }

    /**
     * @param object $user
     * @return JsonResponse
     * @throws Exception
     */
    final public function to_sign_data(object $user) : JsonResponse
    {
        /** @var array $signedDocs Podpis qo'yilishi kerak bo'lgan zayavkalarning ID lari */
        $signedDocs = SignedDocs::where('role_id', $user->role_id)->whereNull('status')->pluck('application_id')->toArray();
        /** @var object $data  Podpis qoyilishi kerak bo'lgan zayavkalar*/
        $data = Application::find($signedDocs);
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('user_id', function ($query) {
                $user = Cache::get('users')->find($query->user_id);
                return Cache::get('branches')->find($user->branch_id)->name;
            })
            ->editColumn('is_more_than_limit', function ($query) {
                return $query->is_more_than_limit === ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return Cache::get('branches')->find($query->branch_id)->name;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? with(new Carbon($data->created_at))->format('d.m.Y') : '';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? with(new Carbon($data->updated_at))->format('d.m.Y') : '';
            })
            ->addColumn('planned_price_curr', function ($query) {
                $planned_price = !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
                return $planned_price;
            })
            ->editColumn('with_nds', function ($query) {
                return $query->with_nds ? 'С НДС' : 'Без НДС';
            })
            ->editColumn('status', function ($query) {
                /*
                 *  Voyager admin paneldan status ranglarini olish va chiqarish
                 */
                $status_extended = StatusExtended::find($query->performer_status);
                $status = match (true) {
                    $query->status === ApplicationStatusEnum::Order_Arrived => 'товар прибыл',
                    $query->status === ApplicationStatusEnum::Order_Delivered => 'товар доставлен',
                    $query->performer_status !== null => $status_extended->name,
                    default => $query->status
                };
                $color_status_if = ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Arrived) || ($query->performer_status !== null && $query->status !== ApplicationStatusEnum::Order_Delivered);
                $color = $color_status_if ? $status_extended->color : setting("color.$status");

                return json_encode(['backgroundColor' => $color, 'app' => $this->translateStatus($status), 'color' => $color ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user){
                if ($user->id === $row->user_id || $user->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user->id && (int)$row->show_director !== ApplicationMagicNumber::two && (int)$row->show_leader !== ApplicationMagicNumber::two && $row->status !== ApplicationStatusEnum::Canceled && $row->status !== ApplicationStatusEnum::Refused) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    private function checkComponentsInclude($application, $user)
    {
        $component = array();
        if ($application->user_id == $user->id && $application->show_leader != Application::NOT_DISTRIBUTED) {
            $component[] = "site.applications.form_edit";
        }
        if ($application->performer_role_id == $user->role_id) {
            $component[] = "site.applications.performer";
        }
        if (($user->hasPermission(PermissionEnum::Warehouse) && $application->show_leader == ApplicationMagicNumber::two) ||
            ($user->hasPermission(PermissionEnum::Warehouse) && $application->status == ApplicationStatusEnum::Order_Delivered) ||
            ($user->hasPermission(PermissionEnum::Warehouse) && $application->status == ApplicationStatusEnum::Order_Arrived)) {
            $component[] = "site.applications.warehouse";
        }
        return $component;
    }

    private function translateStatus(string $status) : string
    {
        return match ($status) {
            'new' => __('new'),
            "in_process" => __('in_process'),
            "overdue" => __('overdue'),
            "refused" => __('refused'),
            "agreed" => __('agreed'),
            "rejected" => __('rejected'),
            "distributed" => __('distributed'),
            "canceled" => __('canceled'),
            default => $status,
        };
    }

    private function createBlockAction($data, $row): string
    {
        $block = '';
        if (!empty($data['show'])) {
            $block .= $this->getLinkHtmlBladeShow($row);
        }
        if (!empty($data['edit'])) {
            $block .= "</br>" . $this->getLinkHtmlBladeEdit($row);
        }
        if (!empty($data['destroy'])) {
            $block .= "</br>" . $this->getLinkHtmlBladeDestroy($data,$row);
        }
        if (!empty($data['clone'])) {
            $block .= "</br>" . $this->getLinkHtmlBladeClone($row);
        }
        return $block;
    }

    private function getLinkHtmlBladeEdit($row)
    {
        return "<a target='_blank' href='" . route("site.applications.edit", $row->id) . "' class='m-1 col edit btn btn-sm btn-secondary'> " . __('edit') . "</a>";
    }

    private function getLinkHtmlBladeShow($row)
    {
        return "<a target='_blank' style='background-color: #000080; color: white' href='" . route('site.applications.show', $row->id) . "' class='m-1 col edit btn btn-sm'> " . __('show') . " </a>";
    }

    private function getLinkHtmlBladeDestroy($data,$row)
    {
        $alert_word = __('Вы уверены?');
        $alert = "onclick='return confirm(`$alert_word`)'";
        return "<a href='" . $data['destroy'] . "' ${alert} class='m-1 col edit btn btn-sm btn-danger' > " . __('destroy') . " </a>";
    }

    private function getLinkHtmlBladeClone($row)
    {
        return "<a href='" . route('site.applications.clone', $row->id) . "' class='m-1 col edit btn btn-sm btn-secondary'> " . __('clone') . "</a>";
    }


    // удаление не нужных подписантов
    private function deleteNullSigners($data, $application, $roles)
    {
        $text = explode("[", $roles);
        $text = explode("]", (string)$text[1]);
        $text = explode(",", (string)$text[0]);
        $application_signers = SignedDocs::where('application_id', $application->id)->get();
        if (!isset($data['signers'])) {
            $data['signers'] = [];
        }
        $text = array_merge($text, $data['signers']);
        foreach ($text as $signer) {
            $application_signers_new[] = (int)$signer;
        }
        if (count($application_signers) > 0) {
            foreach ($application_signers as $signer) {
                $application_signers_old[] = (int)$signer->role_id;
            }
            /** @var array $not_signers  zayavka signers dan udalit qilinishi kerak bo'lgan Role ID lar*/
            $not_signers = array_diff($application_signers_old,$application_signers_new);
            if (count($not_signers) > 0) {
                foreach ($not_signers as $signer) {
                    /** @var int $signer  Udalit qilinayotgan Role ID*/
                    SignedDocs::where('application_id', $application->id)->where('role_id', $signer)->delete();
                }
                $application->signers = json_encode($not_signers);
            }
        }
    }

    public function selectStatusApplication($application, $data)
    {
        $count_signers = SignedDocs::where('application_id', $application->id)->whereNotNull('data')->where('deleted_at', null)->count();
//        dd($application,$data);
        if ($data['draft'] === "1") {
            return ApplicationStatusEnum::Draft;
        }
        if ($count_signers === 0) {
            return ApplicationStatusEnum::New;
        }
        return $application->status;
    }

    /**
     *
     * Function  restore_signers
     * @return  bool
     */
    public function restore_signers() :bool
    {
        /** @var object $applications Signers Null yoki Signers o'chib ketgan Applicationlar*/
        $applications = Application::where('signers', null)->get();
        foreach ($applications as $application) {
            /** @var object $roles  @var object $applicationga tegishli bo'lgan signerlarning Role ID lari*/
            $roles = SignedDocs::where('application_id', $application->id)->pluck('role_id')->toArray();
            $application->signers = json_encode($roles);
            $application->save();
        }
        return true;
    }
}
