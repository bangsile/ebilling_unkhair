<?php

namespace App\Livewire;

use App\Models\Billing;
use App\Models\DosenHasBilling;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateBillingDosen extends Component
{
    public $nama;
    public $nominal;
    public $deskripsi;

    public function mount()
    {
        $user = Auth::user();
        $this->nama = $user->name;
    }
    public function save()
    {
        $this->validate([
            'nama' => 'required|string',
            'nominal' => 'required|numeric|integer|min:1',
            'deskripsi' => 'required|string'
        ]);


        $api_key = env('API_KEY_ECOLL');
        $api_url = env('API_URL_ECOLL');

        $params = [
            'demo' => false,
            'expired_va' => 5, // expired_va
            'apikey' => $api_key,
            'kode_payment' => '012',
            'jenis_payment' => 'Pembayaran Fee Dosen',
            'prefix_trx' => 'FDS',
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => 'Pembayaran Fee Dosen - ' . $this->deskripsi,
        ];

        $response = json_decode(post_data("{$api_url}/btn/createva.php", $params), TRUE);

        if (!$response['response']) {
            $this->dispatch('alert', type: 'error', title: 'Oppss!', message: $response['pesan']);
        } else {
            $bank = $response['data'];
            $message = 'Berhasil Membuat Virtual Account BANK BTN';
            $billing = Billing::create(
                [
                    'trx_id' => $bank['trx_id'],
                    'no_va' => $bank['va'],
                    'nama_bank' => 'BTN',
                    'nama' => $this->nama,
                    'jenis_bayar' => 'fee-dosen',
                    'nominal' => $this->nominal,
                    'tgl_expire' => $bank['expired_va'],
                    'lunas' => 0,
                    'detail' => json_encode([
                        "tahun" => date('Y'),
                        "deskripsi" => $params['deskripsi'],
                    ]),
                ]
            );

            DosenHasBilling::create([
                'user_dosen_id' => Auth::user()->id,
                'billing_id' => $billing->id
            ]);

            $this->dispatch('alert', type: 'success', title: 'Successfully', message: $message);

            sleep(5);

            return $this->redirect(route('billing-dosen.index'));
        }
    }
    public function render()
    {
        return view('livewire.create-billing-dosen');
    }
}
