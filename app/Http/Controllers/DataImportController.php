<?php

namespace App\Http\Controllers;

use App\Imports\BillingUktImport;
use App\Imports\DataImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataImportController extends Controller
{
    public function import_data_ukt_form()
    {
        return view('pages.billing.import-ukt');
    }

    public function import_data_ukt(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        if (!$request->hasFile('file')) {
            return back()->withErrors(['file' => 'File tidak ditemukan.']);
        }

        $file = $request->file('file');

        try {
            $import = new BillingUktImport();
            Excel::import($import, $file);

            $successCount = $import->successCount;
            $failedRows   = $import->failedRows;
            // dd($successCount, $failedRows);

            return redirect()->back()->with([
                'info'     => "Proses import selesai!",
                'successCount' => $successCount,
                'failedRows'  => $failedRows,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal Mengimport File']);
        }
    }
}
