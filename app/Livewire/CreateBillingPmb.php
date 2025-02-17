<?php

namespace App\Livewire;

use App\Models\Billing;
use Livewire\Component;

class CreateBillingPmb extends Component
{
    public $nama;
    public $nominal;

    public function render()
    {
        return view('livewire.create-billing-pmb');
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string',
            'nominal' => 'required|numeric|integer'
        ]);


        $api_key = env('API_KEY_ECOLL');
        $api_url = env('API_URL_ECOLL');

        $params = [
            'demo' => false,
            'expired_va' => 5, // expired_va
            'apikey' => $api_key,
            'kode_payment' => '004',
            'jenis_payment' => 'Pembayaran PMB',
            'prefix_trx' => 'PMB',
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => 'Pembayaran PMB Tahun ' . date('Y'),
        ];

        // dd($params);
        $response = json_decode(post_data("{$api_url}/btn/createva.php", $params), TRUE);

        // dd($response);

        if (!$response['response']) {
            $this->dispatch('alert', type: 'error', title: 'Oppss!|', message: $response['message']);
        } else {
            $bank = $response['data'];
            $message = 'Berhasil Membuat Virtual Account BANK BTN';
            Billing::updateOrCreate(
                [
                    'trx_id' => $bank['trx_id'],
                    'no_va' => $bank['va']
                ],
                [
                    'trx_id' => $bank['trx_id'],
                    'no_va' => $bank['va'],
                    'nama_bank' => 'BTN',
                    'nama' => $this->nama,
                    'jenis_bayar' => 'pmb',
                    'nominal' => $this->nominal,
                    'tgl_expire' => $bank['expired_va'],
                    'lunas' => 0,
                    'detail' => json_encode([
                        "tahun" => date('Y'),
                        "jalur" => '-',
                        "gelombang" => '-',
                        "deskripsi" => $params['deskripsi'],
                    ]),
                ]
            );

            $this->dispatch('alert', type: 'success', title: 'Successfully', message: $message);

            sleep(5);

            return $this->redirect(route('billing-pmb.index'));
        }
    }
}
