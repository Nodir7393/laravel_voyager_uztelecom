<?php

namespace App\Services;

use App\Enums\ApplicationMagicNumber;
use App\Enums\ApplicationStatusEnum;
use App\Enums\PermissionEnum;
use App\Exports\ApplicationExport;
use App\Models\Application;
use App\Models\Resource;
use App\Models\StatusExtended;
use App\Models\User;
use App\Reports\ALL;
use App\Reports\One;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Facades\Excel;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\FastExcel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Exception;
use Sentry;

class ReportExportService
{
    public function export($model, object $request, object $user)
    {
        $title = $model::title();
        Log::info("Export $user->name",[
            'user_id' => $user->id,
            'user_name' => $user->name,
            'title' => $title,
        ]);
        return Excel::download(new $model($request->startDate,$request->endDate), "$title.xlsx");
    }
}
