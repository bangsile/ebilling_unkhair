<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BillingPmbController extends Controller
{
    public function index(Request $request)
    {
        $tahun = date('Y');

        if ($request->ajax()) {
            $billings = Billing::select(['*'])
                ->tahun($tahun)
                ->pembayaran('pmb')
                ->orderBy('created_at', 'DESC');
            return DataTables::of($billings)
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
                ->addColumn('action', function ($billing) {
                    return '';
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->input('search.value'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->input('search.value');
                            $w->where('nama', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $data = [
            'judul' => 'Billing PMB S1/D3 ' . $tahun,
            'datatable' => [
                'url' => route('billing-pmb.index'),
                'id_table' => 'id-datatable',
                'columns' => [
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'nama', 'name' => 'nama', 'orderable' => 'true', 'searchable' => 'true'],
                    ['data' => 'nama_bank', 'name' => 'nama_bank', 'orderable' => 'true', 'searchable' => 'false'],
                    ['data' => 'nominal', 'name' => 'nominal', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'status', 'name' => 'status', 'orderable' => 'false', 'searchable' => 'false'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => 'false', 'searchable' => 'false']
                ]
            ]
        ];
        return view('pages.billing.billing-pmb', $data);
    }
}
