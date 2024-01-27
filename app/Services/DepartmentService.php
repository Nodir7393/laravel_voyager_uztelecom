<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class DepartmentService
{
    /**
     * Barcha Department ya'ni Otdellarni chiqazish.
     **/
    public function getData($query)
    {
        return Datatables::of($query)
            ->editColumn('branch', function ($query) {
                $name = Branch::where('id', $query->branch_id)->get()->pluck('name')->toArray();
                return $name;
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at ? with(new Carbon($data->created_at))->format('d.m.Y') : '';
            })
            ->addColumn('action', function ($row) {
                $data['edit'] = "/admin/departments/{$row->id}/edit";
                $data['destroy'] = route("voyager.departments.destroy", $row->id);
                return json_encode(['link' => $this->createBlockAction($data, $row)]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    private function createBlockAction($data, $row): string
    {
        $block = '';
        if (!empty($data['edit'])) {
            $block .= "</br>" . $this->getLinkHtmlBladeEdit($row);
        }
        if (!empty($data['destroy'])) {
            $block .= "</br>" . $this->getLinkHtmlBladeDestroy($row);
        }
        return $block;
    }

    private function getLinkHtmlBladeEdit($row)
    {
        return "<a href='/admin/departments/{$row->id}/edit' class='m-1 col edit btn btn-sm btn-secondary'> " . __('edit') . "</a>";
    }

    private function getLinkHtmlBladeDestroy($row)
    {
        $alert_word = __('Вы уверены?');
        $alert = "onclick='return confirm(`{$alert_word}`)'";
        return "<a href='" . route("voyager.departments.destroy", $row->id) . "' ${alert} class='m-1 col edit btn btn-sm btn-danger' > " . __('destroy') . " </a>";
    }
}
