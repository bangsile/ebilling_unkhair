<?php

namespace App\Livewire\Billing;

use App\Models\Billing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BillingDosenTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $dosenId = Auth::user()->id;
        $billings = Billing::search(trim($this->search))->whereHas('dosenHasBilling', function ($query) use ($dosenId) {
            $query->where('user_dosen_id', $dosenId);
        })->orderBy('created_at', 'desc')->paginate(5);
        return view('livewire.billing.billing-dosen-table', [
            'billings' => $billings
        ]);
    }
    // public function render()
    // {
    //     return view('livewire.billing.billing-dosen-table');
    // }
}
