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
                    'nama' => $nama,
                    'nominal' => $nominal,
                    'bank' => $bank,
                    'created_at' => tgl_indo($row->created_at)
                ];
            }
        }

        echo json_encode($result);
    }
}
