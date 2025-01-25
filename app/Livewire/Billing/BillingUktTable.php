<?php

namespace App\Livewire\Billing;

use App\Models\BillingUkt;
use Livewire\Component;
use Livewire\WithPagination;

class BillingUktTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $billings = BillingUkt::search(trim($this->search))->orderBy('created_at', 'desc')->paginate(5);
        return view('livewire.billing.billing-ukt-table', ["billings" => $billings]);
    }
}
