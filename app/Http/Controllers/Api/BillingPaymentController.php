<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingResource;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingPaymentController extends Controller
{
    public function detail_billing(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'billing_id' => 'required',
        ], [
            'billing_id.required' => 'Billing ID wajib diisi'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => false,
                'message' => $validator->errors(),
            ], 402);
        }

        $billing = Billing::where('id', $request->billing_id)->first()->toArray();
        if (!$billing) {
            return new BillingResource(false, 'Billing Tidak Ditemukan', null);
        }
        $billing += ['billing_id' => $request->billing_id];
        $billing['detail'] = json_decode($billing['detail'], true);
        unset($billing['id']);

        return new BillingResource(true, 'Billing Ditemukan', $billing);
    }

    public function store_billing(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }

        try {
            $validator = Validator::make($request->all(), [
                'trx_id' => 'required',
                'no_va' => 'required',
                'jenis_bayar' => ['required', 'string', 'exists:jenis_bayars,kode'],
                'nama_bank' => 'required|string',
                'nominal' => 'required|numeric',
                'nama' => 'required|string',
                'tgl_expire' => 'required',
                'detail' => 'required|json',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }

            // dd($request->detail);

            $detail = [];
            if ($request->detail) {
                $detail = json_decode($request->detail, true);
            }

            $values = [
                'trx_id' =>  $request->trx_id,
                'no_va' => $request->no_va,
                'nama_bank' => $request->nama_bank,
                'nominal' => $request->nominal,
                'nama' => $request->nama,
                'jenis_bayar' => $request->jenis_bayar,
                'tgl_expire' => $request->tgl_expire,
                'detail' => json_encode($detail),
            ];

            $billing = Billing::updateOrCreate(
                [
                    'trx_id' =>  $request->trx_id,
                    'no_va' => $request->no_va,
                ],
                $values
            );
            if (!$billing) {
                return new BillingResource(false, 'Gagal Create Billing', null);
            }

            $values += [
                'billing_id' => $billing->id
            ];
            $values['detail'] = $detail;

            return new BillingResource(true, 'Berhasil Create Billing', $values);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage(),
            ], 402);
        }
    }

    public function update_billing(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }

        dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'billing_id' => 'required|exists:billings,id',
                'trx_id' => 'required',
                'no_va' => 'required',
                'jenis_bayar' => ['required', 'string', 'exists:jenis_bayars,kode'],
                'nama_bank' => 'required|string',
                'nominal' => 'required|numeric',
                'nama' => 'required|string',
                'tgl_expire' => 'required',
                'detail' => 'required|json',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }

            // dd($request->detail);

            $detail = [];
            if ($request->detail) {
                $detail = json_decode($request->detail, true);
            }

            $values = [
                'trx_id' =>  $request->trx_id,
                'no_va' => $request->no_va,
                'nama_bank' => $request->nama_bank,
                'nominal' => $request->nominal,
                'nama' => $request->nama,
                'jenis_bayar' => $request->jenis_bayar,
                'tgl_expire' => $request->tgl_expire,
                'detail' => json_encode($detail),
            ];

            $billing = Billing::where('id', $request->billing_id)->update(
                $values
            );
            if (!$billing) {
                return new BillingResource(false, 'Gagal Update Billing', null);
            }

            $values += [
                'billing_id' => $request->billing_id
            ];
            $values['detail'] = $detail;

            return new BillingResource(true, 'Berhasil Update Billing', $values);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage(),
            ], 402);
        }
    }

    public function delete_billing(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || $apiKey !== 'secret') {
            return response()->json([
                'response' => false,
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }

        dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'billing_id' => 'required|exists:billings,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'response' => false,
                    'message' => $validator->errors(),
                ], 402);
            }

            $billing = Billing::where('id', $request->billing_id)->delete();
            if (!$billing) {
                return new BillingResource(false, 'Gagal Delete Billing', null);
            }

            $values = [
                'billing_id' => $request->billing_id
            ];

            return new BillingResource(true, 'Berhasil Delete Billing', $values);
        } catch (\Throwable $th) {
            return response()->json([
                'response' => false,
                'message' => $th->getMessage(),
            ], 402);
        }
    }
}
