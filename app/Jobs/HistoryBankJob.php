<?php

namespace App\Jobs;

use App\Models\Billing;
use App\Models\BillingMahasiswa;
use App\Models\HistoryBank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Validator;

class HistoryBankJob implements ShouldQueue
{
    use Queueable;
    protected $data;
    protected $apiKey;
    /**
     * Create a new job instance.
     */
    public function __construct(array $data, $apiKey)
    {
        $this->data = $data;
        $this->apiKey = $apiKey;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            if (!$this->apiKey || $this->apiKey !== 'secret') {
                print("Unauthorized: Invalid or missing API key.");
                return;
            }
            $validator = Validator::make($this->data, [
                'trx_id'     => 'required|string',
                'no_va'     => 'required|string',
            ]);

            if ($validator->fails()) {
                print($validator->errors());
                return response()->json($validator->errors(), 422);
            }

            $billing_pembayaran = Billing::where('trx_id', $this->data["trx_id"])->where('no_va', $this->data["no_va"])->first();
            $billing_ukt = BillingMahasiswa::where('trx_id', $this->data["trx_id"])->where('no_va', $this->data["no_va"])->first();
            $billing = $billing_pembayaran ?? $billing_ukt;
            if ($billing) {
                HistoryBank::create([
                    "trx_id" => $billing->trx_id,
                    "no_va" => $billing->no_va,
                    "nominal" => $billing->nominal,
                    "nama" => $billing->nama,
                    "metode_pembayaran" => $billing->nama_bank,
                ]);

                //auto lunas
                $billing->update([       
                    "lunas" => 1
                ]);

                print("Billing Updated");
            } else {
                print("Billing Not Found");
            }
        } catch (\Throwable $th) {
            print($th->getMessage());
        }
    }
}
