<?php

namespace App\Livewire;

use App\Models\JenisBayar;
use Livewire\Component;
use Livewire\WithPagination;

class JenisBayarTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $jenis_bayar = JenisBayar::search(trim($this->search))->orderBy('created_at', 'desc')->paginate(5);
        return view('livewire.jenis-bayar-table', ['jenis_bayar' => $jenis_bayar]);
    }
}
