<?php

namespace App\Exports;

use Google\Service\Dfareporting\Invoice;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
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

class ApplicationExport extends DefaultValueBinder implements FromQuery, WithHeadings,WithCustomStartCell,WithStyles
{
    use Exportable;

    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }
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
            'TEST ' => 'A1',
            'TEST' => 'B1',
            'TEST  ' => 'C1',
        ];
        foreach($data as $value=>$item){
            $sheet->setCellValue($item, $value);
        }
        return $sheet;
    }

    public function query()
    {
        return $this->query->select(
            'id',
            'supplier_name',
            'supplier_inn',
            'contract_number',
            'contract_date',
            'contract_price',
            'currency',
            'lot_number',
            'contract_info',
            'country_produced_id',
            'purchase_basis'
        )
            ->with(['branch', 'type_of_purchase'])
            ->whereHas('branch')
            ->whereHas('type_of_purchase');
    }

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

}
