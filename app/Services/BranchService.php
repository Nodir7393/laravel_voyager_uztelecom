<?php

namespace App\Services;

use App\Enums\ApplicationMagicNumber;
use App\Enums\ApplicationStatusEnum;
use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\ApplicationSigners;
use App\Models\Branch;
use App\Models\StatusExtended;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BranchService
{
    /**
     * filial id si cache dan olinadi va shunga tegishli bo'lgan
     * Role lar chiqib keladi.
     **/
    public function getData($id)
    {
        $query = DB::table('roles')->whereRaw('json_contains(branch_id, \'["' . $id . '"]\')')->get();
        return Datatables::of($query)
            ->editColumn('branch_id', function ($query) {
                $all = json_decode($query->branch_id);
                $branch = $all ? Branch::find($all)->pluck('name')->toArray() : [];
                return $branch;
            })
            ->addColumn('action', function ($row) {
                $data['edit'] = "/admin/roles/{$row->id}/edit";
                $data['destroy'] = route("voyager.roles.destroy", $row->id);
                return json_encode(['link' => $this->createBlockAction($data,$row)]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Zayavkalarni filialiga qarab chiqarish
     *
     * users tablitsadagi select_branch_id columni value sini olib
     * shu branch_id ga tegishli bolgan zayavkalarni ciqarb beradi
     **/
    public function ajax_branch($id, object $user)
    {
        $data = Application::where('status','!=','draft')->where('branch_id', $id)->where('name', '!=', 'null')->get();
        return Datatables::of($data)
            ->editColumn('is_more_than_limit', function ($query) {
                return $query->is_more_than_limit == ApplicationMagicNumber::one ? __('Компанию') : __('Филиал');
            })
            ->editColumn('branch_initiator_id', function ($query) {
                return $query->branch->name;
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
            ->editColumn('date', function ($query) {
                return $query->date ? with(new Carbon($query->date))->format('d.m.Y'): '';
            })
            ->editColumn('delivery_date', function ($query) {
                return $query->delivery_date ? with(new Carbon($query->delivery_date))->format('d.m.Y') : with(new Carbon($query->update_at = Carbon::now()->addMonth()))->format('d.m.Y');
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
                $status = $query->status;
                $color = setting("color.{$status}");
                if ($query->performer_status !== null) {
                    $a = StatusExtended::find($query->performer_status);
                    $status = $a->name;
                    $color = $a->color;
                }
                return json_encode(['backgroundColor' => $color, 'app' => $this->translateStatus($status), 'color' => $color ? 'white' : 'black']);
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($user) {

                if ($user->id === $row->user_id || $user->hasPermission(PermissionEnum::Warehouse) || $row->performer_role_id === $user->role_id) {
                    $data['edit'] = route('site.applications.edit', $row->id);
                }

                $data['show'] = route('site.applications.show', $row->id);

                if ($row->user_id === $user->id) {
                    $data['destroy'] = route('site.applications.destroy', $row->id);
                }

                if (($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Canceled) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Refused) || ($row->user_id === $user->id && $row->status === ApplicationStatusEnum::Rejected)) {
                    $data['clone'] = route('site.applications.clone', $row->id);
                }
                return json_encode(['link' => $this->createBlockAction($data,$row)]);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    private function translateStatus($status)
    {
        switch ($status) {
            case 'new':
                return __('new');
                break;
            case "in_process":
                return __('in_process');
                break;
            case "overdue":
                return __('overdue');
                break;
            case "refused":
                return __('refused');
                break;
            case "agreed":
                return __('agreed');
                break;
            case "rejected":
                return __('rejected');
                break;
            case "distributed":
                return __('distributed');
                break;
            case "canceled":
                return __('canceled');
                break;
            default:
                return $status;
        }
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
            $block .= "</br>" . $this->getLinkHtmlBladeDestroy($row);
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
        return "<a target='_blank'  style='background-color: #000080;  color: white' href='" . route('site.applications.show', $row->id) . "' class='m-1 col edit btn btn-sm'> " . __('show') . " </a>";
    }

    private function getLinkHtmlBladeDestroy($row)
    {
        $alert_word = __('Вы уверены?');
        $alert = "onclick='return confirm(`{$alert_word}`)'";
        return "<a href='" . route('site.applications.destroy', $row->id) . "' ${alert} class='m-1 col edit btn btn-sm btn-danger' > " . __('destroy') . " </a>";
    }

    private function getLinkHtmlBladeClone($row)
    {
        return "<a href='" . route('site.applications.clone', $row->id) . "' class='m-1 col edit btn btn-sm btn-secondary'> " . __('edit') . "</a>";
    }
}
