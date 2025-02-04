<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = Fakultas::orderBy('nama_fakultas', 'ASC');
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return tgl_indo($row->created_at);
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }

        $data = [
            'judul' => 'Daftar Fakultas',
            'datatable' => [
                'url' => route('fakultas.index'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nama_fakultas', 'name' => 'nama_fakultas', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'created_at', 'name' => 'created_at', 'orderable' => 'false', 'searchable' => 'false'],
                ]
            ]
        ];
        return view('pages.fakultas.index', $data);
    }

    public function import()
    {
        $token = get_token();
        if ($token['status'] != '200') {
            return redirect(route('fakultas.index'))->with('error', 'Terjadi kesalahan saat pembuatan token!');
        }

        $response = json_decode(get_data(str_curl(env('API_URL_SIMAK') . '/4pisim4k/index.php/fakultas', ['token' => $token['data']['token']])), TRUE);
        if ($response['status'] != '200') {
            return redirect(route('fakultas.index'))->with('error', $response['message']);
        }

        $success = 0;
        foreach ($response['data'] as $fakultas) {
            $res = Fakultas::updateOrCreate(
                [
                    'id' => $fakultas['id_fakultas']
                ],
                [
                    'id' => $fakultas['id_fakultas'],
                    'nama_fakultas' => ucwords(strtolower($fakultas['nama_fakultas'])),
                    'status' => $fakultas['status']
                ]
            );

            if ($res) {
                $success++;
            }
        }

        return redirect(route('fakultas.index'))->with('success', 'Sebanyak ' . $success . ' fakultas berhasil diimport dari SIMAK!');
    }
}
