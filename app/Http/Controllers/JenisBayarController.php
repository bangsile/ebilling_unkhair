<?php

namespace App\Http\Controllers;

use App\Models\JenisBayar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JenisBayarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = JenisBayar::orderBy('bank', 'ASC');
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('nominal', function ($billing) {
                    return formatRupiah($billing->nominal);
                })
                ->editColumn('status', function ($billing) {
                    if ($billing->tgl_expire) {
                        if ($billing->lunas) {
                            return '<span class="badge badge-success" style="font-size: 1rem">Lunas</span>';
                        } elseif ($billing->tgl_expire < now()) {
                            return '<span class="badge badge-danger" style="font-size: 1rem">Expired</span>';
                        } else {
                            return '<span class="badge badge-warning" style="font-size: 1rem">Pending</span>';
                        }
                    }
                    return '';
                })
                ->editColumn('created_at', function ($billing) {
                    return tgl_indo($billing->created_at);
                })
                ->rawColumns(['status', 'nominal', 'created_at'])
                ->make(true);
        }
        $data = [
            'judul' => 'Jenis Pembayaran',
            'datatable' => [
                'url' => route('jenis-bayar'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'keterangan', 'name' => 'keterangan', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'kode', 'name' => 'kode', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'bank', 'name' => 'bank', 'orderable' => 'true', 'searchable' => 'true']
                ]
            ]
        ];
        return view('pages.jenis-bayar.index', $data);
    }

    public function create()
    {
        return view('pages.jenis-bayar.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'keterangan' => 'required',
                'bank' => 'required',
                'kode' => 'required|unique:jenis_bayars,kode',
            ],
            [
                'kode.unique' => 'Kode Jenis Bayar Sudah Terdaftar',
                'kode.required' => 'Kode Jenis Bayar Wajib Diisi',
                'keterangan.required' => 'Keterangan Wajib Diisi',
                'bank.required' => 'Nama Bank Wajib Diisi',
            ]
        );
        try {
            JenisBayar::create($request->all());
            return redirect()->route('jenis-bayar')->with('success', 'Berhasil Menambah Jenis Bayar');
        } catch (\Throwable $th) {
            return redirect()->route('jenis-bayar.tambah')->withErrors('error', 'Gagal Menambah Jenis Bayar');
        }
    }
}
