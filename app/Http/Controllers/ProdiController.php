<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = Prodi::with('fakultas')->orderBy('fakultas_id', 'ASC')->orderBy('nm_prodi', 'ASC');
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('nm_prodi', function ($row) {
                    return $row->kd_prodi . ' - ' . $row->nm_prodi;
                })
                ->editColumn('fakultas', function ($row) {
                    return $row->fakultas->nama_fakultas ?? '';
                })
                ->editColumn('created_at', function ($row) {
                    return tgl_indo($row->created_at);
                })
                ->rawColumns(['nm_prodi', 'fakultas', 'created_at'])
                ->make(true);
        }

        $data = [
            'judul' => 'Daftar Program Studi',
            'datatable' => [
                'url' => route('prodi.index'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nm_prodi', 'name' => 'nm_prodi', 'orderable' => 'false', 'searchable' => 'true'],
                    ['data' => 'jenjang', 'name' => 'jenjang', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'fakultas', 'name' => 'fakultas', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'created_at', 'name' => 'created_at', 'orderable' => 'false', 'searchable' => 'false'],
                ]
            ]
        ];
        return view('pages.prodi.index', $data);
    }

    public function import()
    {
        $token = get_token();
        if (!$token || $token['status'] != '200') {
            return redirect(route('prodi.index'))->with('error', 'Terjadi kesalahan saat pembuatan token!');
        }

        $response = json_decode(get_data(str_curl(env('API_URL_SIMAK') . '/4pisim4k/index.php/prodi', ['token' => $token['data']['token']])), TRUE);
        if ($response['status'] != '200') {
            return redirect(route('prodi.index'))->with('error', $response['message']);
        }

        // dd($response);

        $success = 0;
        foreach ($response['data'] as $prodi) {
            $res = Prodi::updateOrCreate(
                [
                    'id' => $prodi['id_prodi'],
                    'kd_prodi' => $prodi['kode_program_studi'],
                ],
                [
                    'id' => $prodi['id_prodi'],
                    'fakultas_id' => trim($prodi['id_fakultas']) ? $prodi['id_fakultas'] : NULL,
                    'kd_prodi' => $prodi['kode_program_studi'],
                    'nm_prodi' => ucwords(strtolower($prodi['nama_program_studi'])),
                    'status' => $prodi['status'],
                    'jenjang' => $prodi['nama_jenjang_pendidikan'],
                ]
            );

            if ($res) {
                $success++;
            }
        }

        return redirect(route('prodi.index'))->with('success', 'Sebanyak ' . $success . ' prodi berhasil diimport dari SIMAK!');
    }
}
