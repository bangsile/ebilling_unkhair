<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingUkt;
use App\Models\DosenHasBilling;
use App\Models\JenisBayar;
use App\Services\EcollService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    protected $ecollService;
    public function __construct(EcollService $ecollService)
    {
        $this->ecollService = $ecollService;
    }
    public function semua_billing()
    {
        $billings = Billing::all();
        return view('pages.billing.semua-billing',["billings" => $billings]);
    }
    public function create_billing()
    {
        $jenis_bayar = JenisBayar::all();
        return view('pages.billing.tambah-billing', ['jenis_bayar' => $jenis_bayar]);
    }
    public function store_billing(Request $request)
    {
        // dd($request->all());
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
            if($jenis_bayar->bank == 'btn') {
                $response = $this->ecollService->createVaBTN($data);
                if (!$response['res']) {
                    throw new Exception("Respon failed");
                }
            } elseif ($jenis_bayar->bank == 'bni') {
                $response = $this->ecollService->createVaBNI($data);
                if (!$response['res']) {
                    throw new Exception("Respon failed");
                }
            }
            else {
                throw new Exception("Respon failed salah bank");
            }
            // dd($response);

            $result = Billing::create([
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
            dd($e->getMessage());
            //throw $th;
            // return redirect()->route('billing.dosen')->withErrors(['billing' => 'Gagal Membuat Billing']);
            return redirect()->route('billing')->withErrors(['billing' => $e->getMessage()]);
        }

        return redirect()->route('billing')->with('success', 'Berhasil Membuat Billing');
    }
    public function billing_ukt()
    {
        $billings = BillingUkt::all();
        return view('pages.billing.billing-ukt',["billings" => $billings]);
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

        // dd($data);

        try {
            $response = [];
            if($jenis_bayar->bank == 'btn') {
                $response = $this->ecollService->createVaBTN($data);
                if (!$response['res']) {
                    throw new Exception("Respon failed");
                }
            } elseif ($jenis_bayar->bank == 'bni') {
                $response = $this->ecollService->createVaBNI($data);
                if (!$response['res']) {
                    throw new Exception("Respon failed");
                }
            }
            else {
                throw new Exception("Respon failed salah bank");
            }
            // dd($response);

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
            dd($e->getMessage());
            //throw $th;
            // return redirect()->route('billing.dosen')->withErrors(['billing' => 'Gagal Membuat Billing']);
            return redirect()->route('billing.dosen')->withErrors(['billing' => $e->getMessage()]);
        }

        return redirect()->route('billing.dosen')->with('success', 'Berhasil Membuat Billing');
    }
}
