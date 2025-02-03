<?php

namespace App\Imports;

use App\Models\BillingMahasiswa;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class BillingUktImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation, WithLimit
{
    use SkipsFailures;
    public $successCount = 0;
    public $failedRows = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    /**
     * Insert or update data in the database.
     */
    public function model(array $row)
    {
        $nomor = trim($row['no']);
        $npm = trim($row['npm']);
        $nominal = trim($row['nominal']);
        $tahun_akademik = trim($row['tahun_akademik']);
        $kategori_ukt = trim($row['kategori_ukt']);
        // if (empty($row['no']) && empty($row['npm']) && empty($row['nominal']) && empty($row['tahun_akademik']) && empty($row['kategori_ukt'])) {
        if (!$nomor && (!$npm && strlen($npm) > 3) && !$nominal && !$tahun_akademik && !$kategori_ukt) {
            return null;
        }
        try {
            // Cek apakah data sudah ada berdasarkan email
            $existingData = BillingMahasiswa::where('no_identitas', $row['npm'])->first();
            $token = env('API_TOKEN_SIMAK', 'default_token');
            $response_mahasiswa = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/apiv2/mahasiswa', ['token' => $token, 'nim' => $row['npm']])), TRUE);
            if (!$response_mahasiswa) {
                $this->failedRows[] = [
                    'errors'  => ['NPM tidak terdaftar'],
                    'data'  => $row,
                ];
                return null;
            }
            $decodedName = html_entity_decode($response_mahasiswa['data']['mahasiswa']['nama_mahasiswa']);
            $nama = preg_replace('/[^a-zA-Z0-9\s]/', '', $decodedName);

            if ($existingData) {
                // Jika data sudah ada, lakukan update
                $existingData->update([
                    'nama_bank' => 'BNI',
                    'jenis_bayar' => 'ukt',
                    'nominal' => $row['nominal'],
                    // 'tgl_expire' => '',
                    'lunas' => false,
                    'nama' => $nama,
                    'no_identitas' => $response_mahasiswa['data']['mahasiswa']['nim'],
                    'angkatan' => substr($response_mahasiswa['data']['mahasiswa']['id_periode_masuk'], 0, 4),
                    'tahun_akademik' => $row['tahun_akademik'],
                    'kode_prodi' => $response_mahasiswa['data']['head']['kode_program_studi'],
                    'nama_prodi' => $response_mahasiswa['data']['head']['nama_program_studi'],
                    'nama_fakultas' => $response_mahasiswa['data']['head']['nama_fakultas'],
                    'kategori_ukt' => $row['kategori_ukt'],
                    // 'jalur' => $row['jalur'],
                ]);
                // return null;
            } else {
                BillingMahasiswa::create([
                    // 'trx_id' => '',
                    // 'no_va' => '',
                    'nama_bank' => 'BNI',
                    'jenis_bayar' => 'ukt',
                    'nominal' => $row['nominal'],
                    // 'tgl_expire' => '',
                    'lunas' => false,
                    'nama' => $nama,
                    'no_identitas' => $response_mahasiswa['data']['mahasiswa']['nim'],
                    'angkatan' =>  substr($response_mahasiswa['data']['mahasiswa']['id_periode_masuk'], 0, 4),
                    'tahun_akademik' => $row['tahun_akademik'],
                    'kode_prodi' => $response_mahasiswa['data']['head']['kode_program_studi'],
                    'nama_prodi' => $response_mahasiswa['data']['head']['nama_program_studi'],
                    'nama_fakultas' => $response_mahasiswa['data']['head']['nama_fakultas'],
                    'kategori_ukt' => $row['kategori_ukt'],
                    // 'jalur' => $row['jalur'],
                ]);
            }
            $this->successCount++;
            // dd($this->successCount);
        } catch (\Throwable $th) {
            // dd($th);
            // $this->failedRows[] = [
            //     'row'     => $row,  // Ambil baris yang gagal
            //     'errors'  => $th->getMessage(),  // Ambil pesan kesalahan
            // ];
        }
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $rowNumber = $failure->row(); // Ambil nomor baris yang gagal

            // Cek apakah sudah ada error untuk baris ini, jika iya gabungkan error-nya
            if (isset($this->failedRows[$rowNumber])) {
                $this->failedRows[$rowNumber]['errors'] = array_merge(
                    $this->failedRows[$rowNumber]['errors'],
                    $failure->errors()
                );
            } else {
                // Jika belum ada, tambahkan baru ke daftar failedRows
                $this->failedRows[$rowNumber] = [
                    // 'row' => $rowNumber,   // Simpan nomor baris yang gagal
                    'errors' => $failure->errors(),  // Simpan pesan kesalahan
                    'data' => $failure->values(),  // Simpan data yang gagal
                ];
            }
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    /**
     * Batasi jumlah baris yang diproses.
     */
    public function limit(): int
    {
        return 500; // Maksimal hanya 100 baris yang diproses
    }

    /**
     * Aturan validasi untuk setiap baris data.
     */
    public function rules(): array
    {
        //($row['npm']) && empty($row['nominal']) && empty($row['tahun_akademik']) && empty($row['kategori_ukt'])
        return [
            'npm' => 'required',
            'nominal'  => 'required|numeric',
            'tahun_akademik' => 'required|min:5|max:5',
            'kategori_ukt'   => 'required',
        ];
    }

    /**
     * Pesan error untuk validasi.
     */
    public function customValidationMessages()
    {
        return [

            'npm.required'  => 'NPM wajib diisi',
            'nominal.required' => 'Nominal wajib diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'tahun_akademik.required' => 'Tahun akademik wajib diisi',
            'tahun_akademik.min' => 'Tahun akademik harus terdiri dari 5 karakter',
            'tahun_akademik.max' => 'Tahun akademik harus terdiri dari 5 karakter',
            'kategori_ukt.required' => 'Kategori UKT wajib diisi',
        ];
    }
}
