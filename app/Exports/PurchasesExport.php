<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping
{
    private $counter = 1;

    public function collection()
    {
        return Purchase::with(['supplier', 'user'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Supplier',
            'User',
            'Total Price'
        ];
    }

    public function map($purchase): array
    {
        return [
            $this->counter++,
            $purchase->created_at->format('Y-m-d H:i:s'),
            $purchase->supplier->name ?? '-',
            $purchase->user->name ?? '-',
            $purchase->total_price,
        ];
    }
}

