<?php

namespace App\Http\Controllers;

use App\Models\JenisBayar;
use Illuminate\Http\Request;

class JenisBayarController extends Controller
{
    public function index()
    {
        $jenis_bayar = JenisBayar::all();
        return view('pages.jenis-bayar.index', ['jenis_bayar' => $jenis_bayar]);
    }

    public function create()
    {
        return view('pages.jenis-bayar.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'keterangan' => 'required',
            'bank' => 'required',
            'kode' => 'required|unique:jenis_bayars,kode',
        ],[
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
