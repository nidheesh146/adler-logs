<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MAAStockExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item) {
            // Convert dates to string and check against threshold
            $manufacturing_date = ($item->manufacturing_date < '1990-01-01') ? 'NA' : $item->manufacturing_date;
            $expiry_date = ($item->expiry_date < '1990-01-01') ? 'NA' : $item->expiry_date;
    
            return [
                $item->sku_code,
                $item->batch_no,
                $item->category_name,
                $item->new_category_name,
                $item->quantity,
                $item->firm_name,
                $manufacturing_date,
                $expiry_date,
                $item->location_name,
            ];
        });
    }
    

    public function headings(): array
    {
        return [
            'SKU Code',
            'Batch No',
            'Business Category',
            'Product Category',
            'Quantity',
            'Customer Name',
            'Manufacturing Date',
            'Expiry Date',
            'Location'
        ];
    }
}
