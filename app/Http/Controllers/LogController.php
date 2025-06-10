<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\BillingMahasiswa;
use App\Models\LogJob;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{
    public function index()
    {
        $files = NULL;
        try {
            $logPath = storage_path("logs");
            $files = File::files($logPath);

            foreach ($files as $file) {
                if ($file->getFilename() != 'laravel.log') {
                    $fileList[] = [
                        'name' => $file->getFilename(),
                        'size' => round($file->getSize() / 1024, 2) . ' KB',
                        'last_modified' => $file->getMTime(), // Timestamp UNIX
                        'last_modified_human' => date('Y-m-d H:i:s', $file->getMTime())
                    ];
                }
            }

            // Urutkan berdasarkan last_modified DESCENDING (terbaru ke terlama)
            usort($fileList, function ($a, $b) {
                return $b['last_modified'] - $a['last_modified'];
            });

            $files = $fileList;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            exit(0);
        }

        $data = [
            'judul' => 'Log Aplikasi',
            'fileList' => $files
        ];

        return view('pages.log.index', $data);
    }

    public function lihat($file = NULL)
    {
        if (!$file) {
            return;
        }

        try {
            $logPath = storage_path("logs/" . $file);
            $publicPath = public_path("log_temp.log");

            if (File::exists($logPath)) {
                File::copy($logPath, $publicPath);
            }

            $logContent = File::get($publicPath);
            echo "<pre>";
            echo $logContent;
            echo "</pre>";
            exit;
        } catch (\Throwable $th) {
            echo 'File does not exist ' . $file;
            exit(0);
        }
    }

    public function ecoll()
    {
        $tahun_akademik = TahunPembayaran::first();
        $log_ecoll = LogJob::where('job_result', 'Failed, Billing Not Found')
            ->orderBy('created_at', 'DESC')->get();

        $result = [];
        $no = 1;
        foreach ($log_ecoll as $row) {
            $check_ecoll = json_decode(get_data(str_curl(env('API_URL_ECOLL') . '/cekva.php', ['trx_id' => $row->trx_id])), true);
            if ($check_ecoll['response']) {
                $data = $check_ecoll['data'][0];
                $bank = 'BTN';
                $nama = trim($data['nama']);
                $nominal = trim($data['nominal']);

                if (trim($data['metode']) == 'Virtual Account (VA) BNI') {
                    $bank = 'BNI';
                    $nama = trim(str_replace('RPL 062 UNKHAIR OPS PENERIMAAN-', '', $nama));
                }

                $result[] = [
                    'index' => $no,
                    'nama' => $nama,
                    'nominal' => $nominal,
                    'bank' => $bank,
                    'created_at' => tgl_indo($row->created_at)
                ];
                $no++;
            }
        }

        $str = [];
        foreach ($result as $row) {
            $billingukt = BillingMahasiswa::where('nama_bank', $row['bank'])
                ->where('nama', $row['nama'])
                ->where('nominal', $row['nominal'])
                ->where('lunas', 0)
                ->where('tahun_akademik', $tahun_akademik?->tahun_akademik)
                ->first();
            if ($billingukt) {
                $str[] = [
                    'npm' => $billingukt->no_identitas,
                    'nama' => $billingukt->nama,
                    'nominal' => $billingukt->nominal,
                    'tahun_akademik' => $billingukt->tahun_akademik,
                    'lunas' => $billingukt->lunas ? 'Y' : 'N'
                ];
            }
        }

        echo "<pre>";
        print_r($str);
        echo "</pre>";
    }

    public function log_ecoll(Request $request)
    {
        $msg = $request->get('msg');
        $prefix_trx = $request->get('trx');
        if ($msg) {
            abort(404, 'Parameter wajib di isi');
        }

        $tahun_akademik = TahunPembayaran::first();
        $log_ecoll = LogJob::where('job_result', $msg);
        if ($prefix_trx) {
            $log_ecoll->where('trx_id', 'like', $prefix_trx . '%');
        }
        $log_ecoll->orderBy('created_at', 'DESC');

        $res_log_ecoll = $log_ecoll->get();

        $result = [];
        $no = 1;
        foreach ($res_log_ecoll as $row) {
            $check_ecoll = json_decode(get_data(str_curl(env('API_URL_ECOLL') . '/cekva.php', ['trx_id' => $row->trx_id])), true);
            if ($check_ecoll['response']) {
                $data = $check_ecoll['data'][0];
                $bank = 'BTN';
                $nama = trim($data['nama']);
                $nominal = trim($data['nominal']);

                if (trim($data['metode']) == 'Virtual Account (VA) BNI') {
                    $bank = 'BNI';
                    $nama = trim(str_replace('RPL 062 UNKHAIR OPS PENERIMAAN-', '', $nama));
                }

                $result[] = [
                    'index' => $no,
                    'nama' => $nama,
                    'nominal' => $nominal,
                    'bank' => $bank,
                    'created_at' => tgl_indo($row->created_at)
                ];
                $no++;
            }
        }

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

    public function failed_set_lunas_ukt()
    {
        $tahun_akademik = TahunPembayaran::first();
        $billingukt = BillingMahasiswa::join('history_banks', 'billing_mahasiswas.trx_id', '=', 'history_banks.trx_id')
            ->select(['billing_mahasiswas.*', 'history_banks.created_at AS created_at_history_bank'])
            ->where('billing_mahasiswas.lunas', 0)
            ->where('billing_mahasiswas.tahun_akademik', $tahun_akademik?->tahun_akademik)
            ->get();

        $result = [];
        foreach ($billingukt as $row) {
            $check_ecoll = json_decode(get_data(str_curl(env('API_URL_ECOLL') . '/cekva.php', ['trx_id' => $row->trx_id])), true);
            if ($check_ecoll['response']) {
                $result[] = [
                    'trx_id' => $row->trx_id,
                    'va' => $row->no_va,
                    'npm' => $row->no_identitas,
                    'nama' => $row->nama,
                    'ukt' => $row->kategori_ukt,
                    'nominal' => formatRupiah($row->nominal),
                    'prodi' => $row->kode_prodi . ' - ' . $row->nama_prodi,
                    'tahun_akademik' => $row->tahun_akademik,
                    'lunas' => $row->lunas ? 'Y' : 'N',
                    'created_at_history_bank' => $row->created_at_history_bank
                ];
            }
        }

        $data = [
            'judul' => 'Log Gagal Pelunasan UKT ' . $tahun_akademik?->tahun_akademik,
            'result' => $result
        ];

        return view('pages.log.failed-pelunasan-ukt', $data);
    }

    public function error_logjob($prefix_trx = 'PMB')
    {
        $billings = Billing::where('trx_id', 'like', $prefix_trx . '%')->where('job_result', 'Attempt to read property "trx_id" on null')
            ->get();

        $result = [];
        foreach ($billings as $row) {
            $check_ecoll = json_decode(get_data(str_curl(env('API_URL_ECOLL') . '/cekva.php', ['trx_id' => $row->trx_id])), true);
            if ($check_ecoll['response']) {
                $result[] = [
                    'trx_id' => $row->trx_id,
                    'va' => $row->no_va,
                    'data_ecoll' => $check_ecoll
                ];
            }
        }

        dd($result);
    }

    public function set_lunas_ukt(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi

        $trx_id = $request->trx_id;
        $created_at_history_bank = $request->created_at_history_bank;
        $billing = BillingMahasiswa::where('trx_id', $trx_id)->first();

        $billing->update([
            'lunas' => 1,
            'updated_at' => $created_at_history_bank
        ]);

        DB::commit(); // Commit perubahan ke database

        // create log
        $user = auth()->user()->name;
        $aksi = "Set Lunas";
        $thn_akademik = $billing->tahun_akademik;
        $data = $billing->no_identitas . ' - ' . $billing->nama . ' - ' . formatRupiah($billing->nominal);

        $log = sprintf(
            "%-15s | %-20s | %-7s | %s",
            $aksi,
            $user,
            $thn_akademik,
            $data
        );
        Log::channel('monthly')->info($log);


        return redirect()->back()->with('success', 'Berhasil Update Billing Lunas');
    }
}
