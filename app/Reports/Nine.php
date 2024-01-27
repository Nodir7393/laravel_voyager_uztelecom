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

class Nine extends DefaultValueBinder implements FromCollection,WithEvents,WithHeadings,WithCustomStartCell,WithStyles
{
    const EShop_id = 1;
    const NationalEshop_id = 2;
    const ElectronicAuction_id = 3;
    const CooperationPortal_id = 4;
    const TenderPlatform_id = 5;
    const ExchangeTrading_id = 6;
    const Competition_id = 7;
    const Tender_id = 8;
    const Selection_id = 9;
    const SoleSupplier_id = 10;
    const Direct_id = 11;
    const Shaffof_id = 27;
    const SelectionOffers_id = 28;

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
            $this->query = Branch::query()->select('id','name','inn');
        }
        else{
            $this->query = Branch::query()->select('id','name','inn')->where('id',auth()->user()->branch_id);
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
            'Договоры' => 'D1',
            'Через электронный магазин (E-shop)' => 'F1',
            'Через национальный магазин' => 'H1',
            'Через электронный аукцион' => 'J1',
            'Через кооперационный портал' => 'L1',
            'Через платформы "Шаффоф қурилиш"' => 'N1',
            'Через электронные биржевые торги на специальных торговых площадках' => 'P1',
            'Через конкурс(выбор)' => 'R1',
            'Через тендер' => 'T1',
            'Выбор наиболее приемлемых предложений' => 'V1',
            'С едиными поставщиками' => 'X1',
            'Прямые (ПП-3988 и др. ПП, УП, РП)' => 'Z1',
        ];
        foreach($data as $value=>$item){
            $sheet->setCellValue($item, $value);
        }
        $sheet->getStyle('1:2')->getFont()->setBold(true);
        return $sheet;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array
     */
    public function collection(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array
    {
        $query = $this->query->get();
        for($i = 0;$i<count($query);$i++)
        {
            //supplier_inn
            $query[$i]->inn = $this->get_9($query[$i])->pluck('supplier_inn')->toArray();
            $query[$i]->contract_count = $this->get_9($query[$i])->where('contract_price','!=', null)->get()->count();
            $query[$i]->contract_sum = $this->get_summa($this->get_9($query[$i])->pluck('contract_price')->toArray());
            $query[$i]->eshop_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::EShop_id)->get()->count();
            $query[$i]->eshop_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::EShop_id)->pluck('contract_price')->toArray());
            $query[$i]->nat_eshop_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::NationalEshop_id)->get()->count();
            $query[$i]->nat_eshop_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::NationalEshop_id)->pluck('contract_price')->toArray());
            $query[$i]->auction_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::ElectronicAuction_id)->get()->count();
            $query[$i]->auction_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::ElectronicAuction_id)->pluck('contract_price')->toArray());
            $query[$i]->coop_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::CooperationPortal_id)->get()->count();
            $query[$i]->coop_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::CooperationPortal_id)->pluck('contract_price')->toArray());
            $query[$i]->shaffof_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::Shaffof_id)->get()->count();
            $query[$i]->shaffof_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::Shaffof_id)->pluck('contract_price')->toArray());
            $query[$i]->exchange_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::ExchangeTrading_id)->get()->count();
            $query[$i]->exchange_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::ExchangeTrading_id)->pluck('contract_price')->toArray());
            $query[$i]->konkurs_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::Competition_id)->get()->count();
            $query[$i]->konkurs_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::Competition_id)->pluck('contract_price')->toArray());
            $query[$i]->tender_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::Tender_id)->get()->count();
            $query[$i]->tender_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::Tender_id)->pluck('contract_price')->toArray());
            $query[$i]->offers_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::SelectionOffers_id)->get()->count();
            $query[$i]->offers_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::SelectionOffers_id)->pluck('contract_price')->toArray());
            $query[$i]->sole_supplier_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::SoleSupplier_id)->get()->count();
            $query[$i]->sole_supplier_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::SoleSupplier_id)->pluck('contract_price')->toArray());
            $query[$i]->direct_count = $this->get_9($query[$i])->where('type_of_purchase_id', self::Direct_id)->get()->count();
            $query[$i]->direct_sum = $this->get_summa($this->get_9($query[$i])->where('type_of_purchase_id', self::Direct_id)->pluck('contract_price')->toArray());
        }
        return $query;
    }

    /**
     * @param $branch
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function get_9($branch): \Illuminate\Database\Eloquent\Builder
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
    private function get_summa($applications): string
    {
        $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
        return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('ID'),
            __('Наименование заказчика'),
            __('СТИР'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
            __('Количество'),
            __('Сумма'),
        ];
    }

    /**
     * @return string
     */
    public static function title() : string
    {
        return '9 - Ойлик харидлар илова плановый';
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
                __('Наименование заказчика') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('СТИР') => [
                    'rowspan' => 2,
                    'colspan' => 0,
                ],
                __('Договоры') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через электронный магазин (E-shop)') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через национальный магазин') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через электронный аукцион') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через кооперационный портал') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через платформы "Шаффоф қурилиш"') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через электронные биржевые торги на специальных торговых площадках') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через конкурс(выбор)') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Через тендер') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Выбор наиболее приемлемых предложений') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('С едиными поставщиками') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
                __('Прямые (ПП-3988 и др. ПП, УП, РП)') => [
                    'rowspan' => 0,
                    'colspan' => 2,
                ],
            ],
            [
                'Количество 1' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 1' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 2' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 2' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 3' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 3' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 4' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 4' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 5' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 5' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 6' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 6' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 7' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 7' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 8' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 8' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 9' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 9' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 10' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 10' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 11' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 11' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Количество 12' => [
                    'rowspan' => 0,
                    'colspan' => 0,
                ],
                'Сумма 12' => [
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
            ['data' => 'supplier_inn', 'name' => 'supplier_inn'],
            ['data' => 'contract_count', 'name' => 'contract_count'],
            ['data' => 'contract_sum', 'name' => 'contract_sum'],
            ['data' => 'eshop_count', 'name' => 'eshop_count'],
            ['data' => 'eshop_sum', 'name' => 'eshop_sum'],
            ['data' => 'nat_eshop_count', 'name' => 'nat_eshop_count'],
            ['data' => 'nat_eshop_sum', 'name' => 'nat_eshop_sum'],
            ['data' => 'auction_count', 'name' => 'auction_count'],
            ['data' => 'auction_sum', 'name' => 'auction_sum'],
            ['data' => 'coop_count', 'name' => 'coop_count'],
            ['data' => 'coop_sum', 'name' => 'coop_sum'],
            ['data' => 'shaffof_count', 'name' => 'shaffof_count'],
            ['data' => 'shaffof_sum', 'name' => 'shaffof_sum'],
            ['data' => 'exchange_count', 'name' => 'exchange_count'],
            ['data' => 'exchange_sum', 'name' => 'exchange_sum'],
            ['data' => 'konkurs_count', 'name' => 'konkurs_count'],
            ['data' => 'konkurs_sum', 'name' => 'konkurs_sum'],
            ['data' => 'tender_count', 'name' => 'tender_count'],
            ['data' => 'tender_sum', 'name' => 'tender_sum'],
            ['data' => 'offers_count', 'name' => 'offers_count'],
            ['data' => 'offers_sum', 'name' => 'offers_sum'],
            ['data' => 'sole_supplier_count', 'name' => 'sole_supplier_count'],
            ['data' => 'sole_supplier_sum', 'name' => 'sole_supplier_sum'],
            ['data' => 'direct_count', 'name' => 'direct_count'],
            ['data' => 'direct_sum', 'name' => 'direct_sum']
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
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('U')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('W')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(40);

                $event->sheet->mergeCells('D1:E1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('F1:G1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('H1:I1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('J1:K1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('L1:M1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('N1:O1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('P1:Q1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('R1:S1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('T1:U1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('V1:W1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('X1:Y1', Worksheet::MERGE_CELL_CONTENT_MERGE);
                $event->sheet->mergeCells('Z1:AA1', Worksheet::MERGE_CELL_CONTENT_MERGE);

                $event->sheet->getDelegate()->getStyle('1')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
