<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LabExport implements FromView
{
    protected $labs;

    public function __construct($labs)
    {
        $this->labs = $labs;
    }

    public function view(): View
    {
        return view('exports.lab', ['labs' => $this->labs]);
    }
}
