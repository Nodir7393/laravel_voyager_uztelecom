<?php

namespace App\Reports;

use App\Enums\ApplicationMagicNumber;
use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\Branch;
use App\Models\Resource;
use Illuminate\Support\Carbon;
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

class Eight extends DefaultValueBinder implements FromCollection,WithEvents,WithHeadings,WithCustomStartCell,WithStyles
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
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        if(auth()->user()->hasPermission(PermissionEnum::Purchasing_Management_Center))
        {
            if($this->startDate === null){
                $this->query = self::core()->select('id', 'branch_id','number', 'planned_price', 'performer_received_date', 'user_id', 'resource_id', 'type_of_purchase_id', 'contract_number', 'supplier_name', 'contract_price', 'performer_user_id', 'created_at');
            }else{
                $this->query = self::core()->select('id', 'branch_id','number', 'planned_price', 'performer_received_date', 'user_id', 'resource_id', 'type_of_purchase_id', 'contract_number', 'supplier_name', 'contract_price', 'performer_user_id', 'created_at')->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }
        }else{
            $this->query = self::core()->select('id', 'branch_id','number', 'planned_price', 'performer_received_date', 'user_id', 'resource_id', 'type_of_purchase_id', 'contract_number', 'supplier_name', 'contract_price', 'performer_user_id', 'created_at')->where('branch_id',auth()->user()->branch_id)->where('draft','!=',ApplicationMagicNumber::one);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function core(): \Illuminate\Database\Eloquent\Builder
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
            'Информация о заявке ' => 'D1',
            'Договор' => 'J1',
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
    public function collection(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array
    {
        $query = $this->query->get();
        for($i = 0;$i<count($query);$i++)
        {
            $query[$i]->user_id = isset($query[$i]->user->name) ? $query[$i]->user->name : 'User Deleted';
            $query[$i]->created_at = $query[$i]->created_at ? with(new Carbon($query[$i]->created_at))->format('d-m-Y') : '';
            $query[$i]->branch_id = $query[$i]->branch->name;
            $query[$i]->type_of_purchase_id = $query[$i]->type_of_purchase->name ?? [];
            //number_and_date_of_app
            $query[$i]->number = "{$query[$i]->number}  {$query[$i]->date}";
            $query[$i]->planned_price = !Str::contains($query[$i]->planned_price, ' ') ? number_format($query[$i]->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query[$i]->planned_price;
            //product
            $query[$i]->resource_id = $this->get_product($query[$i]);
            $query[$i]->performer_user_id = $query[$i]->performer->name ?? $query[$i]->performer_user_id;
        }
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     */
    private function get_product($query)
    {
        $product = json_decode($query->resource_id,true);
        $names = collect($product);
        $ucnames = $names->map(function($item, $key) {
            return Resource::find($item)->name;
        });
        return json_encode($ucnames,JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('ID'),
            __('Филиал'),
            __('Номер и дата заявки'),
            __('Планируемый бюджет закупки (сум)'),
            __('Дата получения отделом'),
            __('Инициатор'),
            __('Наименование товара'),
            __('Вид закупки'),
            __('Номер договора'),
            __('Поставщик'),
            __('Сумма'),
            __('Исполнитель'),
            __('Дата Создания'),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '8 - Отчет по видам закупки';
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
                __('Информация о заявке') => [
                    'rowspan' => 0,
                    'colspan' => 4,
                ],
                __('Наименование товара') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Вид закупки') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Договор') => [
                    'rowspan' => 0,
                    'colspan' => 3,
                ],
                __('Исполнитель') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Дата Создания') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
            ],
            [
                __('Номер и дата заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Планируемый бюджет закупки (сум)') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Дата получения отделом') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Инициатор') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Номер договора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Поставщик') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Сумма') => [
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
            ['data' => 'filial', 'name' => 'filial'],
            ['data' => 'number_and_date_of_app', 'name' => 'number_and_date_of_app'],
            ['data' => 'planned_price', 'name' => 'planned_price'],
            ['data' => 'performer_received_date', 'name' => 'performer_received_date'],
            ['data' => 'initiator', 'name' => 'initiator'],
            ['data' => 'product', 'name' => 'product'],
            ['data' => 'type_of_purchase', 'name' => 'type_of_purchase'],
            ['data' => 'contract_number', 'name' => 'contract_number'],
            ['data' => 'supplier_name', 'name' => 'supplier_name'],
            ['data' => 'contract_price', 'name' => 'contract_price'],
            ['data' => 'performer_user_id', 'name' => 'performer_user_id'],
            ['data' => 'created_at', 'name' => 'created_at'],
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(50);

                $event->sheet->mergeCells('C1:E1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('I1:K1', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
