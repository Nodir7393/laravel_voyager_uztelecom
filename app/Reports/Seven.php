<?php

namespace App\Reports;

use App\Enums\ApplicationMagicNumber;
use App\Models\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

class Seven extends DefaultValueBinder implements WithStyles, FromCollection, WithHeadings,WithCustomStartCell,WithEvents
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
        $this->query = Application::where('status','!=','draft')->where('name', '!=', null)->select('id', 'name', 'supplier_name', 'supplier_inn', 'contract_number', 'contract_date', 'contract_price', 'currency', 'lot_number', 'type_of_purchase_id', 'contract_info', 'country_produced_id', 'purchase_basis');
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = $this->query->get();
        $branches = $this->query->select('branch_id')->get();
        for($i = 0;$i<count($query);$i++)
        {
            $query[$i]->name = $branches[$i]->branch_id ? $branches[$i]->branch->name:"";
            $query[$i]->type_of_purchase_id = $query[$i]->type_of_purchase_id ? $query[$i]->purchase->name:'';
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
            'Источник финансирование',
            'Наименование доставщика',
            'Стир доставщика',
            'Номер договора',
            'Дата договора',
            'Сумма договора',
            'Валюта',
            'Номер и дата лота размещенных на специальном информационном портале о государственных закупках',
            'Тип закупки',
            'Предмет закупки',
            'Страна происхождения товаров (услуг)',
            'Основание: Закон о государственных закупках / другие решения',
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '7 - Плановый';
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
                __('Источник финансирование') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Наименование доставщика') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Стир доставщика') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Номер договора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Дата договора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Сумма договора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Валюта') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Номер и дата лота размещенных на специальном информационном портале о государственных закупках') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Тип закупки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Предмет закупки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Страна происхождения товаров (услуг)') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Основание: Закон о государственных закупках / другие решения') => [
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
            ['data' => 'supplier_name', 'name' => 'supplier_name'],
            ['data' => 'supplier_inn', 'name' => 'supplier_inn'],
            ['data' => 'contract_number', 'name' => 'contract_number'],
            ['data' => 'contract_date', 'name' => 'contract_date'],
            ['data' => 'contract_price', 'name' => 'contract_price'],
            ['data' => 'currency', 'name' => 'currency'],
            ['data' => 'lot_number', 'name' => 'lot_number'],
            ['data' => 'type_of_purchase', 'name' => 'type_of_purchase'],
            ['data' => 'contract_info', 'name' => 'contract_info'],
            ['data' => 'country_produced_id', 'name' => 'country_produced_id'],
            ['data' => 'purchase_basis', 'name' => 'purchase_basis'],
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
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(70);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(100);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
