<?php

namespace App\Http\Controllers;

use App\Models\BillingMahasiswa;
use App\Models\LogJob;
use App\Models\TahunPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
                ->where('nominal', $nominal)
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
}
