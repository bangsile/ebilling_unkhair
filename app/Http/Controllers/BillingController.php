<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingMahasiswa;
use App\Models\DosenHasBilling;
use App\Models\JenisBayar;
use App\Models\TahunPembayaran;
use App\Services\EcollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Yajra\DataTables\DataTables;

class BillingController extends Controller
{
    protected $ecollService;
    public function __construct(EcollService $ecollService)
    {
        $this->ecollService = $ecollService;
    }

    public function billing_pembayaran()
    {
        $billings = Billing::all();
        return view('pages.billing.billing-pembayaran', ["billings" => $billings]);
    }

    public function create_billing()
    {
        $jenis_bayar = JenisBayar::all();
        return view('pages.billing.tambah-billing', ['jenis_bayar' => $jenis_bayar]);
    }

    public function store_billing(Request $request)
    {
        $request->validate([
            'jenis_bayar' => 'required',
            'nama' => 'required',
            'nominal' => 'required|numeric',
        ]);
        $jenis_bayar = JenisBayar::where('kode', $request->jenis_bayar)->first();


        $data = [
            "nama" => $request->nama,
            "jenis_bayar" => $jenis_bayar->keterangan,
            "nominal" => $request->nominal,
            "deskripsi" => "Pembayaran {$jenis_bayar->keterangan}",
        ];


        try {
            $response = [];
            switch ($jenis_bayar->bank) {
                case 'btn':
                    $response = $this->ecollService->createVaBTN($data);
                    break;
                case 'bni':
                    $response = $this->ecollService->createVaBNI($data);
                    break;
                default:
                    throw new Exception("Respon failed salah bank");
            }

            if (!$response['res']) {
                throw new Exception("Respon failed");
            }

            Billing::create([
                'trx_id' => $response['data']['trx_id'],
                'no_va' => $response['data']['no_va'],
                'nama_bank' => strtoupper($jenis_bayar->bank),
                'nama' => $request->nama,
                'jenis_bayar' => $request->jenis_bayar,
                'nominal' => $request->nominal,
                'tgl_expire' => $response['data']['expired'],
                'detail' => isset($data['detail']) ? json_encode($data['detail']) : json_encode([])
            ]);
        } catch (\Throwable $e) {
            // dd($e->getMessage());
            //throw $th;
            return redirect()->route('billing')->withErrors(['billing' => 'Gagal Membuat Billing']);
            // return redirect()->route('billing.pembayaran')->withErrors(['billing' => $e->getMessage()]);
        }

        return redirect()->route('billing.pembayaran')->with('success', 'Berhasil Membuat Billing');
    }


    public function billing_dosen()
    {
        return view('pages.billing.billing-dosen');
    }

    public function create_billing_dosen()
    {
        return view('pages.billing.tambah-billing-dosen');
    }

    public function store_billing_dosen(Request $request)
    {

        $request->validate([
            'jenis_bayar' => 'required',
            'nominal' => 'required|numeric',
        ]);
        $jenis_bayar = JenisBayar::where('kode', 'fee-dosen')->first();

        $data = [
            "nama" => Auth::user()->name,
            "jenis_bayar" => $jenis_bayar->keterangan,
            "nominal" => $request->nominal,
            "deskripsi" => "Pembayaran {$jenis_bayar->keterangan}",
            "detail" => [
                "nama_kegiatan" => $request->nama_kegiatan,
                "tgl_kegiatan" => $request->tgl_kegiatan
            ]
        ];

        try {
            $response = [];
            switch ($jenis_bayar->bank) {
                case 'btn':
                    $response = $this->ecollService->createVaBTN($data);
                    break;
                case 'bni':
                    $response = $this->ecollService->createVaBNI($data);
                    break;
                default:
                    throw new Exception("Respon failed salah bank");
            }

            if (!$response['res']) {
                throw new Exception("Respon failed");
            }

            $result = Billing::create([
                'trx_id' => $response['data']['trx_id'],
                'no_va' => $response['data']['no_va'],
                'nama_bank' => $jenis_bayar->bank,
                'nama' => $request->nama,
                'jenis_bayar' => 'fee-dosen',
                'nominal' => $request->nominal,
                'tgl_expire' => $response['data']['expired'],
                'detail' => json_encode($data['detail']),
            ]);
            DosenHasBilling::create([
                'billing_id' => $result->id,
                'user_dosen_id' => Auth::user()->id,
            ]);
        } catch (\Throwable $e) {
            // dd($e->getMessage());
            //throw $th;
            return redirect()->route('billing.dosen')->withErrors(['billing' => 'Gagal Membuat Billing']);
            // return redirect()->route('billing.dosen')->withErrors(['billing' => $e->getMessage()]);
        }

        return redirect()->route('billing.dosen')->with('success', 'Berhasil Membuat Billing');
    }
}
