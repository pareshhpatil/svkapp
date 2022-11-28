<?php

namespace App\Exports;

use App\Model\Gst;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GstExport implements FromArray, WithHeadings
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return [
            'Supplier',
            'GST Invoice Number',
            'GST Request Date',
            'GST Taxable Amount',
            'GST Tax value',
            'GST Total Amount',
            'Difference',
            'Purchase Invoice Number',
            'Purchase request Date',
            'Purchase Taxable Value',
            'Purchase Tax Value',
            'Purchase Total Amount',
            'Status',
        ];
    }
}
