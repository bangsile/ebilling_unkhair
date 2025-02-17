<?php

namespace App\Livewire;

use App\Models\BillingMahasiswa;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\TahunPembayaran;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Component;

class CreateBillingUkt extends Component
{
    public $fakultas;

    public $nama_bank = 'BTN';
    public $nominal;
    public $jenis_bayar = 'ukt';
    public $nama;
    public $npm;
    public $angkatan;
    public $prodi;
    public $kategori_ukt;

    public function render()
    {
        $this->fakultas = Fakultas::with([
            'prodi' => function (Builder $query) {
                $query->orderBy('nm_prodi', 'ASC');
            }
        ])->orderBy('nama_fakultas', 'ASC')->get();

        return view('livewire.create-billing-ukt');
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required',
            'npm' => 'required|max:11',
            'prodi' => 'required',
            'angkatan' => 'required',
            'kategori_ukt' => 'required'
        ]);

        // dd($this);

        $api_key = env('API_KEY_ECOLL');
        $api_url = env('API_URL_ECOLL');

        $tahun_akademik = TahunPembayaran::first();

        $params = [
            'demo' => false,
            'expired_va' => 5, // expired_va
            'apikey' => $api_key,
            'kode_payment' => '007',
            'jenis_payment' => 'Pembayaran UKT',
            'prefix_trx' => 'UKT',
            'nama' => $this->nama,
            'nominal' => $this->nominal,
            'deskripsi' => 'Pembayaran UKT Tahun ' . $tahun_akademik->tahun_akademik,
        ];

        // dd($params);
        $response = json_decode(post_data("{$api_url}/btn/createva.php", $params), TRUE);

        // dd($response);

        if (!$response['response']) {
            $this->dispatch('alert', type: 'error', title: 'Oppss!|', message: $response['message']);
        } else {
            $bank = $response['data'];
            $message = 'Berhasil Membuat Virtual Account BANK BTN';

            $prodi = Prodi::with('fakultas')->where('kd_prodi', $this->prodi)->first();

            BillingMahasiswa::updateOrCreate(
                [
                    'trx_id' => $bank['trx_id'],
                    'no_va' => $bank['va']
                ],
                [
                    'trx_id' => $bank['trx_id'],
                    'no_va' => $bank['va'],
                    'nama_bank' => $this->nama_bank,
                    'jenis_bayar' => $this->jenis_bayar,
                    'nominal' => $this->nominal,
                    'tgl_expire' => $bank['expired_va'],
                    'lunas' => 0,
                    'nama' => $this->nama,
                    'no_identitas' => $this->npm,
                    'angkatan' => $this->angkatan,
                    'tahun_akademik' => $tahun_akademik->tahun_akademik,
                    'kode_prodi' => $prodi->kd_prodi,
                    'nama_prodi' => $prodi->nm_prodi,
                    'nama_fakultas' => $prodi->fakultas->nama_fakultas,
                    'kategori_ukt' => $this->kategori_ukt,
                    'jalur' => NULL,
                ]
            );

            $this->dispatch('alert', type: 'success', title: 'Successfully', message: $message);

            sleep(5);

            return $this->redirect(route('billing.ukt'));
        }
    }
}
