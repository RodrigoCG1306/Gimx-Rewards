<?php

namespace App\Exports;

use App\Models\Sales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesTemplate implements WithHeadings
{
    public function headings(): array
    {
        return [
            'date',
            'agent',
            'email',
            'amount',
            'product',
            'company',
            'award',
        ];
    }
}
