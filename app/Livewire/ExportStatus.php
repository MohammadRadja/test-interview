<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Export;

class ExportStatus extends Component
{
    protected $listeners = ['refreshExportStatus' => '$refresh'];

    public function render()
    {
        return view('livewire.export-status', [
            'exports' => Export::with('user')->latest()->get(),
        ]);
    }
}
