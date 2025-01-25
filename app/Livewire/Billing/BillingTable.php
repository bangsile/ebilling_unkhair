<?php

namespace App\Livewire\Billing;

use App\Models\Billing;
use Livewire\Component;
use Livewire\WithPagination;

class BillingTable extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $billings = Billing::search(trim($this->search))->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.billing.billing-table', ["billings" => $billings]);
    }
}
