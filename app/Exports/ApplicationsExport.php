<?php

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ApplicationsExport implements FromQuery
{
    use Exportable;

    private $query;

    public function __construct($query)
    {
        $this->query = $query;
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

