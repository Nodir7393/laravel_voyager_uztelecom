<?php

namespace App\Reports;

use App\Enums\ApplicationMagicNumber;
use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\StatusExtended;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Four extends DefaultValueBinder implements FromCollection,WithColumnFormatting,WithHeadings,WithCustomStartCell,WithStyles,WithEvents
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
                $this->query = self::core()->select('id', 'branch_id', 'number', 'date', 'user_id', 'user_role_id', 'department_initiator_id', 'type_of_purchase_id', 'name', 'subject', 'expire_warranty_date', 'planned_price', 'with_nds', 'currency', 'supplier_name', 'contract_price', 'delivery_date', 'status', 'performer_leader_user_id', 'performer_user_id', 'info_business_plan', 'info_purchase_plan', 'purchase_basis', 'basis');
            }else{
                $this->query = self::core()->select('id', 'branch_id', 'number', 'date', 'user_id', 'user_role_id', 'department_initiator_id', 'type_of_purchase_id', 'name', 'subject', 'expire_warranty_date', 'planned_price', 'with_nds', 'currency', 'supplier_name', 'contract_price', 'delivery_date', 'status', 'performer_leader_user_id', 'performer_user_id', 'info_business_plan', 'info_purchase_plan', 'purchase_basis', 'basis')->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }
        }else{
            $this->query = self::core()->select('id', 'branch_id', 'number', 'date', 'user_id', 'user_role_id', 'department_initiator_id', 'type_of_purchase_id', 'name', 'subject', 'expire_warranty_date', 'planned_price', 'with_nds', 'currency', 'supplier_name', 'contract_price', 'delivery_date', 'status', 'performer_leader_user_id', 'performer_user_id', 'info_business_plan', 'info_purchase_plan', 'purchase_basis', 'basis')->where('branch_id',auth()->user()->branch_id)->where('draft','!=',ApplicationMagicNumber::one);
        }
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
            $query[$i]->branch_id = $query[$i]->branch_id ? $query[$i]->branch->name:"";
            //phone
            $query[$i]->user_role_id = isset($query[$i]->user->phone) ? $query[$i]->user->phone:"Not Phone Number";
            $query[$i]->performer_user_id = $query[$i]->performer->name ?? $query[$i]->performer_user_id;
            $query[$i]->department_initiator_id = $query[$i]->department_initiator_id ? $query[$i]->department->name:"";
            $query[$i]->user_id = $query[$i]->user->name;
            $query[$i]->type_of_purchase_id = $query[$i]->type_of_purchase_id ? $query[$i]->purchase->name:'';
            $query[$i]->subject = $query[$i]->subject ? $query[$i]->subjects->name:'';
            $query[$i]->planned_price = !Str::contains($query[$i]->planned_price, ' ') ? number_format($query[$i]->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query[$i]->planned_price;
            $query[$i]->with_nds = $query[$i]->with_nds ?'Да':'Нет';
            $query[$i]->status = $query[$i]->status ? $this->translateStatus($query[$i]->status) : StatusExtended::find($query[$i]->performer_status)->name;
        }
        return $query;
    }

    /**
     * @return string[]
     */
    public function columnFormats(): array
    {
        return [
            'F' =>  "0",
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('ID'),
            __('Филиал'),
            __('номер заявки'),
            __('дата заявки'),
            __('ФИО инициатора'),
            __('Контактный телефон инициатора'),
            __('отдел инициатора'),
            __('вид закупки'),
            __('Наименование предмета закупки(товар, работа, услуги)'),
            __('Предмет закупки (товар,работа,услуга)'),
            __('Гарантийный срок качества товара (работ, услуг)'),
            __('сумма заявки'),
            __('С НДС'),
            __('Валюта'),
            __('Наименование поставщика'),
            __('сумма договора'),
            __('Махсулот келишининг муддати'),
            __('Статус'),
            __('Начальник Исполнителя заявки'),
            __('Исполнитель заявки'),
            __('Бюджетни режалаштириш булими. Маълумот'),
            __('Харидлар режасида мавжудлиги буича маълумот'),
            __('Цель закупки'),
            __('Основание(план закупок, рапорт, расспорежение руководства)'),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '4 - Отчет заявки по статусам';
    }

    /**
     * @param $status
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
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
                __('Филиал')  => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('номер заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('дата заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('ФИО инициатора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Контактный телефон инициатора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('отдел инициатора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('вид закупки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Наименование предмета закупки(товар, работа, услуги)') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Предмет закупки (товар,работа,услуга)') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Гарантийный срок качества товара (работ, услуг)') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('сумма заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('С НДС') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Валюта') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Наименование поставщика') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('сумма договора') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Махсулот келишининг муддати') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Статус') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Начальник Исполнителя заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Исполнитель заявки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Бюджетни режалаштириш булими. Маълумот') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Харидлар режасида мавжудлиги буича маълумот') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Цель закупки') => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                __('Основание(план закупок, рапорт, расспорежение руководства)') => [
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
            ['data' => 'branch_id', 'name' => 'branch_id'],
            ['data' => 'number', 'name' => 'number'],
            ['data' => 'date', 'name' => 'date'],
            ['data' => 'user_id', 'name' => 'user_id'],
            ['data' => 'phone', 'name' => 'phone'],
            ['data' => 'department_initiator_id', 'name' => 'department_initiator_id'],
            ['data' => 'type_of_purchase_id', 'name' => 'type_of_purchase_id'],
            ['data' => 'name', 'name' => 'name'],
            ['data' => 'subject', 'name' => 'subject'],
            ['data' => 'expire_warranty_date', 'name' => 'expire_warranty_date'],
            ['data' => 'planned_price', 'name' => 'planned_price'],
            ['data' => 'with_nds', 'name' => 'with_nds'],
            ['data' => 'currency', 'name' => 'currency'],
            ['data' => 'supplier_name', 'name' => 'supplier_name'],
            ['data' => 'contract_price', 'name' => 'contract_price'],
            ['data' => 'delivery_date', 'name' => 'delivery_date'],
            ['data' => 'status', 'name' => 'status','render' => 'function (data, type, row) {
                                var details = JSON.parse(row.status).backgroundColor;
                                var color = JSON.parse(row.status).color;
                                var app = JSON.parse(row.status).app;
                                return `<button style="background-color: ${details};color:${color};" class="btn btn-sm">` + app + `</button>`;
                            }'],
            ['data' => 'performer_leader_user_id', 'name' => 'performer_leader_user_id'],
            ['data' => 'performer_user_id', 'name' => 'performer_user_id'],
            ['data' => 'info_business_plan', 'name' => 'info_business_plan'],
            ['data' => 'info_purchase_plan', 'name' => 'info_purchase_plan'],
            ['data' => 'purchase_basis', 'name' => 'purchase_basis'],
            ['data' => 'basis', 'name' => 'basis'],
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
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('X')->setWidth(100);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
