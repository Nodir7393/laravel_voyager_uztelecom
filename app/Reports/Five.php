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

class Five extends DefaultValueBinder implements WithEvents,FromCollection,WithHeadings,WithCustomStartCell,WithStyles
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
            'Заключенные договора' => 'D1',
            'товар' => 'F1',
            'работа' => 'H1',
            'услуга' => 'J1',
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
            $query[$i]->count = $this->get_5($query[$i])->get()->count();
            $query[$i]->summa = $this->get_summa($this->get_5($query[$i])->pluck('contract_price')->toArray());
            $query[$i]->count_1 = $this->get_5($query[$i])->where('subject', ApplicationMagicNumber::one)->get()->count();
            $query[$i]->summa_1 = $this->get_summa($this->get_5($query[$i])->where('subject', ApplicationMagicNumber::one)->pluck('contract_price')->toArray());
            $query[$i]->count_2 = $this->get_5($query[$i])->where('subject', ApplicationMagicNumber::two)->get()->count();
            $query[$i]->summa_2 = $this->get_summa($this->get_5($query[$i])->where('subject', ApplicationMagicNumber::two)->pluck('contract_price')->toArray());
            $query[$i]->count_3 = $this->get_5($query[$i])->where('subject', ApplicationMagicNumber::three)->get()->count();
            $query[$i]->summa_3 = $this->get_summa($this->get_5($query[$i])->where('subject', ApplicationMagicNumber::three)->pluck('contract_price')->toArray());
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
            __('кол-во'),
            __('сумма'),
            __('кол-во'),
            __('сумма'),
            __('кол-во'),
            __('сумма'),
            __('кол-во'),
            __('сумма'),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '5 - Отчет свод общий';
    }

    /**
     * @param $branch
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function get_5($branch)
    {
        $start_date = $this->startDate ?? null;
        $end_date = $this->endDate ?? null;
        $applications = self::core();
        if($start_date)
            $applications = self::core()
                ->whereBetween('created_at', [$start_date, $end_date]);

        $result = $applications->where('branch_id', $branch->id)
            ->where('status', 'extended')
            ->whereNotNull('contract_price');

        return $result;
    }

    /**
     * @param $applications
     * @return string
     */
    private function get_summa($applications)
    {
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
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Филиал') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Заключенные договора') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('товар') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('работа') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('услуга') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
            ],
            [
                __(' кол-во') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __(' сумма') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('кол-во') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('сумма') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('кол-во ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('сумма ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],

                __('кол-во  ') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('сумма  ') => [
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
            ['data' => 'summa', 'name' => 'summa'],
            ['data' => 'count_1', 'name' => 'count_1'],
            ['data' => 'summa_1', 'name' => 'summa_1'],
            ['data' => 'count_2', 'name' => 'count_2'],
            ['data' => 'summa_2', 'name' => 'summa_2'],
            ['data' => 'count_3', 'name' => 'count_3'],
            ['data' => 'summa_3', 'name' => 'summa_3'],
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);

                $event->sheet->mergeCells('C1:D1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('E1:F1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('G1:H1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('I1:J1', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->mergeCells('Y2:Z2', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->getDelegate()->getStyle('1:2')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
