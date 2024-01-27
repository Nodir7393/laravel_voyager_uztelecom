<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Notification;
use App\Models\SignedDocs;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Yajra\DataTables\DataTables;


class ApplicationServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create()
    {
        $application = new Application();
        $application->user_id = 1;
        $application->branch_initiator_id = 1;
        $application->department_initiator_id = 1;
        $application->is_more_than_limit = 0;
        $application->status = ApplicationStatusEnum::New;
        $data = $application->save();
        $this->assertTrue($data);
    }
    public function test_send_notification()
    {
            $user_id = 111111;
            $notification = new Notification();
            $notification->user_id = $user_id;
            $notification->application_id = 1;
            $notification->message = 'test';
            $this->assertTrue(true);
    }
    public function test_clone()
    {
        $clone = Application::find(1);
        $application = $clone->replicate();
        $application->signers = null;
        $application->status = ApplicationStatusEnum::New;
        $application->save();
    }
    public function test_SignedDocs()
    {
        $data = SignedDocs::where('application_id',Application::first()->id);
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('user_id', function($docs) {
                return $docs->user ? $docs->user->name:"";
            })
            ->editColumn('role_id', function($docs) {
                return $docs->role ? $docs->role->display_name:"";
            })
            ->editColumn('updated_at', function ($query) {
                return $query->updated_at ? with(new Carbon($query->updated_at))->format('d.m.Y') : '';;
            })
            ->editColumn('status', function ($status){
                $status_agreed = __('Согласована');
                $status_rejected = __('Отклонена');
                $status_not_signed = __('Не подписан');

                match($status->status)
                {
                    1 => $status_signer = $status_agreed,
                    0 => $status_signer = $status_rejected,
                    default => $status_signer = $status_not_signed,
                };
                return $status_signer;
            })
            ->make(true);
    }
    public function test_status_table()
    {
        $data = Application::where('status', 'Выполнено в полном объёме')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('user_id', function($docs) {
                return $docs->user ? $docs->user->name:"";
            })
            ->editColumn('role_id', function($docs) {
                return $docs->role ? $docs->role->display_name:"";
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? with(new Carbon($data->created_at))->format('d.m.Y') : '';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? with(new Carbon($data->updated_at))->format('d.m.Y') : '';
            })
            ->make(true);
    }
    public function test_show_draft()
    {
        $data = Application::where('user_id', 1)
            ->whereDraft("1");

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? with(new Carbon($data->created_at))->format('d.m.Y') : '';
            })
            ->editColumn('updated_at', function ($data) {
                return $data->updated_at ? with(new Carbon($data->updated_at))->format('d.m.Y') : '';
            })
            ->make(true);
    }
    public function test_update()
    {
        $application = Application::find(1);
        if($application->draft == 1)
            $application->status = ApplicationStatusEnum::Draft;

        $application->update();
    }
}
