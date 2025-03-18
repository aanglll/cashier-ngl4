<?php

namespace App\Exports;

use App\Models\Purchase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection, WithHeadings
{
    protected $filter;
    protected $startDate;
    protected $endDate;

    public function __construct($filter, $startDate = null, $endDate = null)
    {
        $this->filter = $filter;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Tentukan tanggal berdasarkan filter
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        switch ($this->filter) {
            case 'yesterday':
                $startDate = Carbon::now()->subDay()->startOfDay();
                $endDate = Carbon::now()->subDay()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                break;
            case 'time_span':
                if ($this->startDate && $this->endDate) {
                    $startDate = Carbon::parse($this->startDate);
                    $endDate = Carbon::parse($this->endDate);
                }
                break;
        }

        // Query data berdasarkan filter
        return Purchase::whereBetween('created_at', [$startDate, $endDate])
            ->with(['supplier', 'user'])
            ->get()
            ->map(function ($purchase, $index) {
                return [
                    'No' => $index + 1,
                    'Date' => $purchase->created_at->format('Y-m-d H:i:s'),
                    'Supplier' => $purchase->supplier->name ?? '-',
                    'User' => $purchase->user->name,
                    'Total Price' => $purchase->total_price,
                ];
            });
    }

    public function headings(): array
    {
        return ['No', 'Date', 'Supplier', 'User', 'Total Price'];
    }
}
