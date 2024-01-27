<?php

namespace App\Reports;

use App\Enums\ApplicationMagicNumber;
use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\Branch;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class One extends DefaultValueBinder implements WithStyles, FromCollection, WithHeadings,WithCustomStartCell,WithEvents
{
    use Exportable;

    private $startDate;
    private $endDate;

    /**
     * @param $startDate
     * @param $endDate
     */
    public function __construct($startDate, $endDate)
    {
        if(auth()->user()->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            $this->query = Branch::query()->select('id','name');
        }
        else{
            $this->query = Branch::query()->select('id','name')->where('id',auth()->user()->branch_id);
        }
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function core()
    {
        $query =  Application::query()->where('status','!=','draft')->where('name', '!=', null);
        return $query;
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }

    /**
     * @param Worksheet $sheet
     * @return Worksheet
     */
    public function styles(Worksheet $sheet): Worksheet
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        return $sheet;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = $this->query->get();
        for($i = 0;$i<count($query);$i++)
        {
            $query[$i]->count = count(self::core()->where('branch_id', $query[$i]->id)->get());
            $query[$i]->tovar = count(self::core()->where('branch_id', $query[$i]->id)->where('subject',ApplicationMagicNumber::one)->get());
            $query[$i]->rabota = count(self::core()->where('branch_id', $query[$i]->id)->where('subject',ApplicationMagicNumber::two)->get());
            $query[$i]->usluga = count(self::core()->where('branch_id', $query[$i]->id)->where('subject',ApplicationMagicNumber::three)->get());
            $query[$i]->summa = $this->summa($query[$i]);
            $query[$i]->nds = $this->nds($query[$i]);
        }
        return $query;
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'ID',
            'Филиал',
            'Количество заявок',
            'Товар',
            'Работа',
            'Услуга',
            'Сумма без НДС',
            'Сумма с НДС',
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '1 - Отчет общий';
    }

    /**
     * @param $branch
     * @return string
     */
    private function summa($branch)
    {
        $applications = self::core()->where('branch_id', $branch->id)->where('with_nds', '=',null)->pluck('planned_price')->toArray();
        $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
        return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
    }

    /**
     * @param $branch
     * @return string
     */
    private function nds($branch)
    {
        $applications = self::core()->where('branch_id', $branch->id)->where('with_nds', '!=',null)->pluck('planned_price')->toArray();
        $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
        return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
    }

    /**
     * @return array
     */
    public static function headers()
    {
        return [
            [
                __('ID') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Филиал') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Количество заявок') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Товар') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Работа') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Услуга') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Сумма без НДС') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Сумма с НДС') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public static function columns()
    {
        return [
            ['data' => 'id', 'name' => 'id'],
            ['data' => 'name', 'name' => 'name'],
            ['data' => 'count', 'name' => 'count'],
            ['data' => 'tovar', 'name' => 'tovar'],
            ['data' => 'rabota', 'name' => 'rabota'],
            ['data' => 'usluga', 'name' => 'usluga'],
            ['data' => 'summa', 'name' => 'summa'],
            ['data' => 'nds', 'name' => 'nds'],
        ];
    }

    public static function events(): array
    {
        return [];
    }
    public static function options(): array
    {
        return [];
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);


                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
