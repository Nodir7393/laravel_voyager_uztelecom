<?php


namespace App\Services;

use App\Enums\ApplicationMagicNumber;
use App\Enums\ApplicationStatusEnum;
use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\Branch;
use App\Models\ReportDate;
use App\Models\Resource;
use App\Models\StatusExtended;
use App\Models\User;
use App\Reports\One;
use http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ReportService
{
    const EShop_id = 1;
    const NationalEshop_id = 2;
    const ElectronicAuction_id = 3;
    const CooperationPortal_id = 4;
    const TenderPlatform_id = 5;
    const ExchangeTrading_id = 6;
    const Competition_id = 7;
    const Tender_id = 8;
    const Selection_id = 9;
    const SoleSupplier_id = 10;
    const Direct_id = 11;
    const Shaffof_id = 27;
    const SelectionOffers_id = 28;

    public function application_query()
    {
        $application =  Application::query()->where('status','!=','draft')->where('name', '!=', null);
        return $this->query = $application;
    }
    private function get_5($branch, $startDate, $endDate)
    {
        return $this->application_query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('branch_id', $branch->id)
            ->whereNotNull('contract_price')
            ->where('status', 'extended');
    }

    private function get_2($branch, $request, $subject, $startMonth, $endMonth)
    {
        $start_date = $request->startDate ? "$request->startDate-$startMonth-01" : "2022-$startMonth-01";
        $end_date = $request->endDate ? "$request->endDate-$endMonth-31" : "2022-$endMonth-31";

        $applications = $this->application_query()
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('branch_id', $branch->id)
            ->where('subject', $subject)
            ->where('status', 'extended')
            ->pluck('planned_price')
            ->toArray();
        $result = array_sum(preg_replace('/[^0-9]/', '', $applications));
        return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
    }

    private function get_2_2($branch, $request, $startMonth, $endMonth, $subject, $withNds)
    {
        $start_date = $request->startDate ? "$request->startDate-$startMonth-01 00:00" : "2022-$startMonth-01";
        $end_date = $request->endDate ? "$request->endDate-$endMonth-31 23:59" : "2022-$endMonth-31";

        Log::info($request);
        Log::info($start_date);
        Log::info($end_date);

        $applications = $this->application_query()
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('branch_id', $branch->id)
            ->where('subject', $subject)
            ->where('with_nds', $withNds)
            ->pluck('planned_price')
            ->toArray();

        $result = array_sum(preg_replace('/[^0-9]/', '', $applications));
        $return =$result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';

        Log::info($return);
        return $return;
    }




    public function report_1(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::query()->where('id', $user->branch_id)->get();
        }
        return Datatables::of($query)
            ->addColumn('count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->get();
                return count($applications);
            })
            ->addColumn('tovar', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::one)->get();
                return count($applications);
            })
            ->addColumn('rabota', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::two)->get();
                return count($applications);
            })
            ->addColumn('usluga', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::three)->get();
                return count($applications);
            })
            ->addColumn('summa', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('with_nds', '=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('nds', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('with_nds', '!=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->make(true);
    }
    public function report_2(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::query()->where('id',$user->branch_id)->get();
        }
        return Datatables::of($query)
            ->addColumn('tovar_1', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::one, '01', '03');
            })
            ->addColumn('rabota_1', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::two, '01', '03');
            })
            ->addColumn('usluga_1', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::three, '01', '03');
            })
            ->addColumn('tovar_2', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::one, '04', '06');
            })
            ->addColumn('rabota_2', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::two, '04', '06');
            })
            ->addColumn('usluga_2', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::three, '04', '06');
            })
            ->addColumn('tovar_3', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::one, '07', '09');
            })
            ->addColumn('rabota_3', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::two, '07', '09');
            })
            ->addColumn('usluga_3', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::three, '07', '09');
            })
            ->addColumn('tovar_4', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::one, '10', '12');
            })
            ->addColumn('rabota_4', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::two, '10', '12');
            })
            ->addColumn('usluga_4', function($branch) use ($request){
                return $this->get_2($branch, $request, ApplicationMagicNumber::three, '10', '12');
            })
            ->make(true);
    }
    public function report_2_2(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::query()->where('id',$user->branch_id)->get();
        }
        return Datatables::of($query)
            ->addColumn('tovar_1', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "01", "03", ApplicationMagicNumber::one, null);
            })
            ->addColumn('tovar_1_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, '01', '03', ApplicationMagicNumber::one, '!=');
            })
            ->addColumn('rabota_1', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "01", "03", ApplicationMagicNumber::two, null);
            })
            ->addColumn('rabota_1_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "01", "03", ApplicationMagicNumber::two, '!=');
            })
            ->addColumn('usluga_1', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "01", "03", ApplicationMagicNumber::three, null);
            })
            ->addColumn('usluga_1_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "01", "03", ApplicationMagicNumber::three, '!=');
            })
            ->addColumn('tovar_2', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::one, null);
            })
            ->addColumn('tovar_2_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::one, '!=');
            })
            ->addColumn('rabota_2', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::two, null);
            })
            ->addColumn('rabota_2_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::two, '!=');
            })
            ->addColumn('usluga_2', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::three, null);
            })
            ->addColumn('usluga_2_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "04", "06", ApplicationMagicNumber::three, '!=');
            })
            ->addColumn('tovar_3', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::one, null);
            })
            ->addColumn('tovar_3_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::one, '!=');
            })
            ->addColumn('rabota_3', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::two, null);
            })
            ->addColumn('rabota_3_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::two, '!=');
            })
            ->addColumn('usluga_3', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::three, null);
            })
            ->addColumn('usluga_3_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "07", "09", ApplicationMagicNumber::three, '!=');
            })
            ->addColumn('tovar_4', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::one, null);
            })
            ->addColumn('tovar_4_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::one, '!=');
            })
            ->addColumn('rabota_4', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::two, null);
            })
            ->addColumn('rabota_4_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::two, '!=');
            })
            ->addColumn('usluga_4', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::three, null);
            })
            ->addColumn('usluga_4_nds', function($branch) use ($request) {
                return $this->get_2_2($branch, $request, "10", "12", ApplicationMagicNumber::three, '!=');
            })
            ->make(true);
    }
    public function report_3(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::find($user->branch_id);
        }
        return Datatables::of($query)
            ->addColumn('tovar_1', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::one)->where('with_nds','=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('tovar_1_nds', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::one)->where('with_nds','!=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })

            ->addColumn('rabota_1', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::two)->where('with_nds','=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('rabota_1_nds', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::two)->where('with_nds','!=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('usluga_1', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::three)->where('with_nds','=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('usluga_1_nds', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('status', 'extended')->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::three)->where('with_nds','!=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->make(true);
    }

    public function report_4(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            if($request->startDate === null){
                $query = $this->application_query();
            }else{
                $query = $this->application_query()->whereBetween('created_at', [$request->startDate, $request->endDate]);
            }
        }else{
            $query = $this->application_query()->where('branch_id',$user->branch_id)->where('draft','!=',ApplicationMagicNumber::one)->get();
        }


        return Datatables::of($query)




            ->editColumn('branch_id', function($application)
            {
                return $application->branch_id ? $application->branch->name:"";
            })
            ->editColumn('performer_user_id', function($application)
            {
                return $application->performer->name ?? $application->performer_user_id;
            })
            ->editColumn('department_initiator_id', function($application)
            {
                return $application->department_initiator_id ? $application->department->name:"";
            })
            ->addColumn('phone', function($application)
            {
                return $application->user->phone ? $application->user->phone:"Not Phone Number";
            })
            ->editColumn('user_id', function($application)
            {
                return $application->user->name;
            })
            ->editColumn('type_of_purchase_id', function($application)
            {
                return $application->type_of_purchase_id ? $application->purchase->name:'';
            })
            ->editColumn('subject', function($application)
            {
                return $application->subject ? $application->subjects->name:'';
            })
            ->addColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->editColumn('with_nds', function($application)
            {
                return $application->with_nds ?'Да':'Нет';
            })
            ->editColumn('status', function ($query) {
                /*
                     *  Voyager admin paneldan status ranglarini olish va chiqarish
                     */

                $status = $query->status;
                $color = setting("color.{$status}");
                if ($query->performer_status !== null) {
                    $a = StatusExtended::find($query->performer_status);
                    $status = $a->name;
                    $color = $a->color;
                }
                return json_encode(['backgroundColor' => $color,'app' => $this->translateStatus($status),'color' => $color ? 'white':'black']);
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function report_5(object $request, object $user)
    {

        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::query()->where('id',$user->branch_id)->get();
        }

        return Datatables::of($query)
            ->addColumn('count', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                return $this->get_5($branch, $start_date, $end_date)->get()->count();
            })
            ->addColumn('summa', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->get_5($branch, $start_date, $end_date)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('count_1', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                return $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::one)->get()->count();
            })
            ->addColumn('summa_1', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::one)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('count_2', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                return $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::two)->get()->count();
            })
            ->addColumn('summa_2', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::two)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('count_3', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                return $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::three)->get()->count();
            })
            ->addColumn('summa_3', function($branch) use ($request){
                $start_date = $request->startDate ?? '2022-08-01';
                $end_date = $request->endDate ?? '2025-12-31';

                $applications = $this->get_5($branch, $start_date, $end_date)->where('subject', ApplicationMagicNumber::three)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->make(true);
    }

    public function report_6(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {

            if($request->startDate === null){
                $query = $this->application_query();
            }else{
                $query = $this->application_query()->whereBetween('created_at', [$request->startDate, $request->endDate]);
            }
        }else{
            $query = $this->application_query()->where('branch_id',$user->branch_id)->where('draft','!=',ApplicationMagicNumber::one)->get();
        }
        return Datatables::of($query)
            ->addColumn('name', function($application){
                return $application->branch_id ? $application->branch->name:"";
            })
            ->addColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->make(true);

    }

    public function report_7(object $request, object $user){
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {

            if($request->startDate === null){
                $query = $this->application_query();
            }else{
                $query = $this->application_query()->whereBetween('created_at', [$request->startDate, $request->endDate]);
            }
        }else{
            $query = $this->application_query()->where('branch_id',$user->branch_id)->where('draft','!=',ApplicationMagicNumber::one)->get();
        }
        return Datatables::of($query)
            ->addColumn('name', function($branch){
                return Branch::query()->where('id', $branch->branch_id)->get()->pluck('name')->toArray();
            })
            ->addColumn('type_of_purchase', function($branch){
                return $branch->type_of_purchase_id ? $branch->purchase->name:'';
            })

            ->make(true);
    }
    public function report_8(object $request, object $user){
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {

            if($request->startDate === null){
                $query = $this->application_query();
            }else{
                $query = $this->application_query()->whereBetween('created_at', [$request->startDate, $request->endDate]);
            }
        }else{
            $query = $this->application_query()->where('branch_id',$user->branch_id)->where('draft','!=',ApplicationMagicNumber::one)->get();
        }
        return Datatables::of($query)
            ->addColumn('initiator', function($branch){
                return User::query()->where('id', $branch->user_id)->get()->pluck('name')->toArray();
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at ? with(new Carbon($query->created_at))->format('d-m-Y') : '';
            })
            ->addColumn('filial', function($branch){
                return Branch::query()->where('id', $branch->branch_id)->get()->pluck('name')->toArray();
            })
            ->addColumn('type_of_purchase', function($branch){
                return Purchase::query()->where('id', $branch->type_of_purchase_id)->get()->pluck('name')->toArray();
            })->addColumn('number_and_date_of_app', function($branch){
                return "{$branch->number } {$branch->date }";
            })
            ->addColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->addColumn('product', function($application){
                $product = json_decode($application->resource_id,true);
                $names = collect($product);
                $ucnames = $names->map(function($item, $key) {
                    return Resource::find($item)->name;
                });
                return json_decode($ucnames);
            })
            ->editColumn('performer_user_id', function($application){
                return $application->performer->name ?? $application->performer_user_id;
            })
            ->make(true);
    }

    public function report_9(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $query = Branch::query();
        }
        else{
            $query = Branch::query()->where('id',$user->branch_id)->get();
        }

        return Datatables::of($query)
            ->addColumn('supplier_inn', function($branch){
                return $this->application_query()->where('branch_id', $branch->id)->pluck('supplier_inn')->toArray();
            })
            ->addColumn('contract_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('contract_price','!=', null)->get();
                return count($applications);
            })
            ->addColumn('contract_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('eshop_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::EShop_id)->get();
                return count($applications);
            })
            ->addColumn('eshop_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::EShop_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('nat_eshop_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::NationalEshop_id)->get();
                return count($applications);
            })
            ->addColumn('nat_eshop_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::NationalEshop_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('auction_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::ElectronicAuction_id)->get();
                return count($applications);
            })
            ->addColumn('auction_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::ElectronicAuction_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('shaffof_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Shaffof_id)->get();
                return count($applications);
            })
            ->addColumn('shaffof_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Shaffof_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('tender_platform_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::TenderPlatform_id)->get();
                return count($applications);
            })
            ->addColumn('tender_platform_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::TenderPlatform_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('exchange_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::ExchangeTrading_id)->get();
                return count($applications);
            })
            ->addColumn('exchange_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::ExchangeTrading_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('konkurs_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Competition_id)->get();
                return count($applications);
            })
            ->addColumn('konkurs_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Competition_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('tender_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Tender_id)->get();
                return count($applications);
            })
            ->addColumn('tender_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Tender_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('offers_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::SelectionOffers_id)->get();
                return count($applications);
            })
            ->addColumn('offers_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::SelectionOffers_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('sole_supplier_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::SoleSupplier_id)->get();
                return count($applications);
            })
            ->addColumn('sole_supplier_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::SoleSupplier_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('direct_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Direct_id)->get();
                return count($applications);
            })
            ->addColumn('direct_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::Direct_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->addColumn('coop_count', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::CooperationPortal_id)->get();
                return count($applications);
            })
            ->addColumn('coop_sum', function($branch){
                $applications = $this->application_query()->where('branch_id', $branch->id)->where('type_of_purchase_id', self::CooperationPortal_id)->pluck('contract_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->make(true);
    }

    public function report_10(object $request, object $user)
    {
        if($user->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $operator = '!=';
            $b = null;
        }else{
            $operator = '==';
            $b = $user->branch_id;
        }
        $this->a = 'branch_id';
        $this->operator = $operator;
        $this->b = $b;
        $status = StatusExtended::query();
        return Datatables::of($status)
            ->addColumn('january', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-01-01" : "2022-01-01";
                $end_date = $request->endDate ? "$request->endDate-02-01" : "2022-02-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('february', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-02-01" : "2022-02-01";
                $end_date = $request->endDate ? "$request->endDate-03-01" : "2022-03-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('march', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-03-01" : "2022-03-01";
                $end_date = $request->endDate ? "$request->endDate-04-01" : "2022-04-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('april', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-04-01" : "2022-04-01";
                $end_date = $request->endDate ? "$request->endDate-05-01" : "2022-05-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('may', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-05-01" : "2022-05-01";
                $end_date = $request->endDate ? "$request->endDate-06-01" : "2022-06-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('june', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-06-01" : "2022-06-01";
                $end_date = $request->endDate ? "$request->endDate-07-01" : "2022-07-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('july', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-07-01" : "2022-07-01";
                $end_date = $request->endDate ? "$request->endDate-08-01" : "2022-08-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('august', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-08-01" : "2022-08-01";
                $end_date = $request->endDate ? "$request->endDate-09-01" : "2022-09-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('september', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-09-01" : "2022-09-01";
                $end_date = $request->endDate ? "$request->endDate-10-01" : "2022-10-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('october', function($status) use ($request){
                $start_date = $request->startDate ? "$request->startDate-10-01" : "2022-10-01";
                $end_date = $request->endDate ? "$request->endDate-11-01" : "2022-11-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('november', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-11-01" : "2022-11-01";
                $end_date = $request->endDate ? "$request->endDate-12-01" : "2022-12-01";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('december', function($status) use($request){
                $start_date = $request->startDate ? "$request->startDate-12-01" : "2022-12-01";
                $end_date = $request->endDate ? "$request->endDate-12-31" : "2022-12-31";

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->whereBetween('created_at',[$start_date,$end_date])->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
            ->addColumn('all', function($status){

                $applications = Application::where('draft','!=',ApplicationMagicNumber::one)->where($this->a,$this->operator,$this->b)->where('performer_status', $status->id)->get();
                return count($applications);
            })
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
}
