<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sale::with(['customer', 'user'])->get()->map(function ($sale, $index) {
            return [
                'No' => $index + 1,
                'Date' => $sale->created_at->format('Y-m-d H:i:s'),
                'Customer' => $sale->customer->name ?? '-',
                'User' => $sale->user->name,
                'Total Price' => $sale->total_price,
            ];
        });
    }

    public function headings(): array
    {
        return ["No", "Date", "Customer", "User", "Total Price"];
    }
}
