<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiExport implements FromView,ShouldAutoSize
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    use Exportable;

    public function view() : View
    {
        $data = $this->data;

        return view('export.transaksi', compact('data'));
    }
}
