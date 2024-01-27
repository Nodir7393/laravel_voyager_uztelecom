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
use function React\Promise\all;

class Two extends DefaultValueBinder implements FromCollection,WithHeadings,WithCustomStartCell,WithStyles,WithEvents
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
        return 'A2';
    }
    /**
     * @return Worksheet
     */
    public function styles(Worksheet $sheet): Worksheet
    {
        $data = [
            '1 - Квартал ' => 'D1',
            '2 - Квартал' => 'G1',
            '3 - Квартал' => 'J1',
            '4 - Квартал' => 'M1',
        ];
        foreach($data as $value=>$item){
            $sheet->setCellValue($item, $value);
        }
        $sheet->getStyle('1:2')->getFont()->setBold(true);
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
            $query[$i]->tovar_1 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::one, '01', '03');
            $query[$i]->rabota_1 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::two, '01', '03');
            $query[$i]->usluga_1 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::three, '01', '03');
            $query[$i]->tovar_2 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::one, '04', '06');
            $query[$i]->rabota_2 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::two, '04', '06');
            $query[$i]->usluga_2 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::three, '04', '06');
            $query[$i]->tovar_3 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::one, '07', '09');
            $query[$i]->rabota_3 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::two, '07', '09');
            $query[$i]->usluga_3 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::two, '07', '09');
            $query[$i]->tovar_4 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::one, '10', '12');
            $query[$i]->rabota_4 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::two, '10', '12');
            $query[$i]->usluga_4 = $this->get_2($query[$i], $this->startDate, $this->endDate, ApplicationMagicNumber::three, '10', '12');
        }
        return $query;
    }

    /**
     * @param $branch
     * @param $start_date
     * @param $end_date
     * @param $subject
     * @param $startMonth
     * @param $endMonth
     * @return string
     */
    private function get_2($branch, $start_date, $end_date, $subject, $startMonth, $endMonth)
    {
        $start_date = $start_date ? "$start_date-$startMonth-01" : "2022-$startMonth-01";
        $end_date = $end_date ? "$end_date-$endMonth-31" : "2022-$endMonth-31";

        $applications = self::core()
            ->whereBetween('created_at', [$start_date, $end_date])->where('branch_id', $branch->id)
            ->where('subject', $subject)
            ->where('status', 'extended')
            ->pluck('planned_price')
            ->toArray();
        $result = array_sum(preg_replace('/[^0-9]/', '', $applications));
        return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('ID'),
            __('Филиал'),
            __('товар 1'),
            __('работа 1'),
            __('услуга 1'),
            __('товар 2'),
            __('работа 2'),
            __('услуга 2'),
            __('товар 3'),
            __('работа 3'),
            __('услуга 3'),
            __('товар 4'),
            __('работа 4'),
            __('услуга 4'),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '2 - Отчет квартальный итоговый';
    }

    /**
     * @return array
     */
    public static function headers()
    {
        return [
            [
                __('ID') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Филиал') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('1 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 3,
                ],
                __('2 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 3,
                ],
                __('3 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 3,
                ],
                __('4 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 3,
                ],
            ],

            [
                __('товар 1') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('работа 1') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('услуга 1') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('товар 2') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('работа 2') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('услуга 2') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('товар 3') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('работа 3') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('услуга 3') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('товар 4') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('работа 4') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('услуга 4') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
            ]
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
     * @return array
     */
    public static function columns()
    {
        return [
            ['data' => 'id', 'name' => 'id'],
            ['data' => 'name', 'name' => 'name'],
            ['data' => 'tovar_1', 'name' => 'tovar_1'],
            ['data' => 'rabota_1', 'name' => 'rabota_1'],
            ['data' => 'usluga_1', 'name' => 'usluga_1'],
            ['data' => 'tovar_2', 'name' => 'tovar_2'],
            ['data' => 'rabota_2', 'name' => 'rabota_2'],
            ['data' => 'usluga_2', 'name' => 'usluga_2'],
            ['data' => 'tovar_3', 'name' => 'tovar_3'],
            ['data' => 'rabota_3', 'name' => 'rabota_3'],
            ['data' => 'usluga_3', 'name' => 'usluga_3'],
            ['data' => 'tovar_4', 'name' => 'tovar_4'],
            ['data' => 'rabota_4', 'name' => 'rabota_4'],
            ['data' => 'usluga_4', 'name' => 'usluga_4'],
        ];
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(15);

                $event->sheet->mergeCells('C1:E1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('F1:H1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('I1:K1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('L1:N1', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
