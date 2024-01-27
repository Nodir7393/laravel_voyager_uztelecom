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
use Maatwebsite\Excel\Concerns\WithColumnWidths;
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

class TwoTwo extends DefaultValueBinder implements WithEvents,FromCollection,WithHeadings,WithCustomStartCell,WithStyles
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
        return 'A3';
    }
    /**
     * @return Worksheet
     */
    public function styles(Worksheet $sheet): Worksheet
    {
        $data = [
            '1 - Квартал ' => 'F1',
            '2 - Квартал' => 'L1',
            '3 - Квартал' => 'R1',
            '4 - Квартал' => 'X1',

            'товар 1' => 'D2',
            'работа 1' => 'F2',
            'услуга 1' => 'H2',

            'товар 2' => 'J2',
            'работа 2' => 'L2',
            'услуга 2' => 'N2',

            'товар 3' => 'P2',
            'работа 3' => 'R2',
            'услуга 3' => 'T2',

            'товар 4' => 'V2',
            'работа 4' => 'X2',
            'услуга 4' => 'Z2',
        ];
        foreach($data as $value=>$item){
            $sheet->setCellValue($item, $value);
        }
        $sheet->getStyle('1:3')->getFont()->setBold(true);
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
            $query[$i]->tovar_1= $this->get_2_2($query[$i],"01", "03", ApplicationMagicNumber::one, null);
            $query[$i]->tovar_1_nds = $this->get_2_2($query[$i],'01', '03', ApplicationMagicNumber::one, '!=');
            $query[$i]->rabota_1 = $this->get_2_2($query[$i],"01", "03", ApplicationMagicNumber::two, null);
            $query[$i]->rabota_1_nds= $this->get_2_2($query[$i],"01", "03", ApplicationMagicNumber::two, '!=');
            $query[$i]->usluga_1 = $this->get_2_2($query[$i],"01", "03", ApplicationMagicNumber::three, null);
            $query[$i]->usluga_1_nds = $this->get_2_2($query[$i],"01", "03", ApplicationMagicNumber::three, '!=');
            $query[$i]->tovar_2 = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::one, null);
            $query[$i]->tovar_2_nds = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::one, '!=');
            $query[$i]->rabota_2 = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::two, null);
            $query[$i]->rabota_2_nds = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::two, '!=');
            $query[$i]->usluga_2 = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::three, null);
            $query[$i]->usluga_2_nds = $this->get_2_2($query[$i],"04", "06", ApplicationMagicNumber::three, '!=');
            $query[$i]->tovar_3 = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::one, null);
            $query[$i]->tovar_3_nds = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::one, '!=');
            $query[$i]->rabota_3 = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::two, null);
            $query[$i]->rabota_3_nds = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::two, '!=');
            $query[$i]->usluga_3 = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::three, null);
            $query[$i]->usluga_3_nds = $this->get_2_2($query[$i],"07", "09", ApplicationMagicNumber::three, '!=');
            $query[$i]->tovar_4 = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::one, null);
            $query[$i]->tovar_4_nds = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::one, '!=');
            $query[$i]->rabota_4 = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::two, null);
            $query[$i]->rabota_4_nds = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::two, '!=');
            $query[$i]->usluga_4 = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::three, null);
            $query[$i]->usluga_4_nds = $this->get_2_2($query[$i],"10", "12", ApplicationMagicNumber::three, '!=');
        }
        return $query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('ID'),
            __('Филиал'),
            __('Без НДС 1'),
            __('С НДС 1'),
            __('Без НДС 1 '),
            __('С НДС 1 '),
            __('Без НДС 1  '),
            __('С НДС 1  '),
            __('Без НДС 2'),
            __('С НДС 2'),
            __('Без НДС 2 '),
            __('С НДС 2 '),
            __('Без НДС 2  '),
            __('С НДС 2  '),
            __('Без НДС 3'),
            __('С НДС 3'),
            __('Без НДС 3 '),
            __('С НДС 3 '),
            __('Без НДС 3  '),
            __('С НДС 3  '),
            __('Без НДС 4'),
            __('С НДС 4'),
            __('Без НДС 4 '),
            __('С НДС 4 '),
            __('Без НДС 4  '),
            __('С НДС 4  '),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '2 - Отчет квартальный плановый';
    }

    /**
     * @param $branch
     * @param $startMonth
     * @param $endMonth
     * @param $subject
     * @param $withNds
     * @return string
     */
    private function get_2_2($branch, $startMonth, $endMonth, $subject, $withNds)
    {
        $start_date = $this->startDate ? "$this->startDate-$startMonth-01 00:00" : "2022-$startMonth-01";
        $end_date = $this->endDate ? "$this->endDate-$endMonth-31 23:59" : "2022-$endMonth-31";

        $applications = self::core()
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('branch_id', $branch->id)
            ->where('subject', $subject)
            ->where('with_nds', $withNds)
            ->pluck('planned_price')
            ->toArray();

        $result = array_sum(preg_replace('/[^0-9]/', '', $applications));
        $return = $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
        return $return;
    }

    /**
     * @return array
     */
    public static function headers()
    {
        return [
            [
                __('ID') => [
                    'rowspan' => 3,
                    'colspan' => 0,
                ],
                __('Филиал') => [
                    'rowspan' => 3,
                    'colspan' => 0,
                ],
                __('1 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 6,
                ],
                __('2 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 6,
                ],
                __('3 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 6,
                ],
                __('4 - Квартал') => [
                    'rowspan' => 0,
                    'colspan' => 6,
                ],
            ],

            [
                __('товар 1') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('работа 1') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('услуга 1') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],

                __('товар 2') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('работа 2') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('услуга 2') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],

                __('товар 3') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('работа 3') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('услуга 3') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],

                __('товар 4') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('работа 4') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('услуга 4') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
            ],

            [
                __('Без НДС 1') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 1') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 1 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 1 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 1  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 1  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('Без НДС 2') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 2') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 2 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 2 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 2  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 2  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('Без НДС 3') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 3') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 3 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 3 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 3  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 3  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('Без НДС 4') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 4') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 4 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 4 ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Без НДС 4  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС 4  ') => [
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
            ['data' => 'tovar_1', 'name' => 'tovar_1'],
            ['data' => 'tovar_1_nds', 'name' => 'tovar_1_nds'],
            ['data' => 'rabota_1', 'name' => 'rabota_1'],
            ['data' => 'rabota_1_nds', 'name' => 'rabota_1_nds'],
            ['data' => 'usluga_1', 'name' => 'usluga_1'],
            ['data' => 'usluga_1_nds', 'name' => 'usluga_1_nds'],
            ['data' => 'tovar_2', 'name' => 'tovar_2'],
            ['data' => 'tovar_2_nds', 'name' => 'tovar_2_nds'],
            ['data' => 'rabota_2', 'name' => 'rabota_2'],
            ['data' => 'rabota_2_nds', 'name' => 'rabota_2_nds'],
            ['data' => 'usluga_2', 'name' => 'usluga_2'],
            ['data' => 'usluga_2_nds', 'name' => 'usluga_2_nds'],
            ['data' => 'tovar_3', 'name' => 'tovar_3'],
            ['data' => 'tovar_3_nds', 'name' => 'tovar_3_nds'],
            ['data' => 'rabota_3', 'name' => 'rabota_3'],
            ['data' => 'rabota_3_nds', 'name' => 'rabota_3_nds'],
            ['data' => 'usluga_3', 'name' => 'usluga_3'],
            ['data' => 'usluga_3_nds', 'name' => 'usluga_3_nds'],
            ['data' => 'tovar_4', 'name' => 'tovar_4'],
            ['data' => 'tovar_4_nds', 'name' => 'tovar_4_nds'],
            ['data' => 'rabota_4', 'name' => 'rabota_4'],
            ['data' => 'rabota_4_nds', 'name' => 'rabota_4_nds'],
            ['data' => 'usluga_4', 'name' => 'usluga_4'],
            ['data' => 'usluga_4_nds', 'name' => 'usluga_4_nds'],
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
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(15);

                $event->sheet->mergeCells('C1:H1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('I1:N1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('O1:T1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('U1:Z1', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('C2:D2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('E2:F2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('G2:H2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('I2:J2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('K2:L2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('M2:N2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('O2:P2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('O2:P2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('Q2:R2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('S2:T2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('U2:V2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('W2:X2', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('Y2:Z2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->getDelegate()->getStyle('1:2')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
