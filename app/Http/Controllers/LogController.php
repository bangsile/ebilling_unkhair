<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $logContent = File::get($logPath);

            echo "<pre>";
            echo $logContent;
            echo "</pre>";
            exit;
        } catch (\Throwable $th) {
            echo 'File does not exist ' . $file;
            exit(0);
        }
    }
}
