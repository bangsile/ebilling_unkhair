<?php

namespace App\Imports;

use App\Models\BillingUkt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataImport implements ToModel,  WithHeadingRow, WithValidation, WithLimit
{
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
        if (empty($row['npm'])) {
            return null;
        }
        // Cek apakah data sudah ada berdasarkan email
        $existingData = BillingUkt::where('no_identitas', $row['npm'])->first();
        // $data = $this->get_mahasiswa($row['no_identitas']);
        // dd($data);
        $token = env('API_TOKEN_SIMAK', 'default_token');
        $response_mahasiswa = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/apiv2/mahasiswa', ['token' => $token, 'nim' => $row['npm']])), TRUE);
        // $nama = str_replace(array("'", "'", "’", "â", "€", "™"), '', addslashes($response_mahasiswa['data']['mahasiswa']['nama_mahasiswa']));
        // preg_replace('/[^a-zA-Z0-9\s]/', '', $input);
        $decodedName = html_entity_decode($response_mahasiswa['data']['mahasiswa']['nama_mahasiswa']);
        $nama = preg_replace('/[^a-zA-Z0-9\s]/', '', $decodedName);
        // dd($response_mahasiswa);
        // $data = $response_mahasiswa['data'];
        // dd($data['mahasiswa']['nama_mahasiswa']);

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
            return null;
        }

        // Jika data belum ada, lakukan insert
        return new BillingUkt([
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

    /**
     * Batasi jumlah baris yang diproses.
     */
    public function limit(): int
    {
        return 100; // Maksimal hanya 100 baris yang diproses
    }

    /**
     * Aturan validasi untuk setiap baris data.
     */
    public function rules(): array
    {
        return [
            // 'name'  => 'required|string|max:255',
            // 'email' => 'required|email',
            // 'age'   => 'required|integer|min:18|max:65',
        ];
    }

    /**
     * Pesan error untuk validasi.
     */
    public function customValidationMessages()
    {
        return [
            // 'name.required'  => 'Nama wajib diisi.',
            // 'email.required' => 'Email wajib diisi.',
            // 'email.email'    => 'Format email tidak valid.',
            // 'age.required'   => 'Umur wajib diisi.',
            // 'age.integer'    => 'Umur harus berupa angka.',
            // 'age.min'        => 'Umur minimal 18 tahun.',
            // 'age.max'        => 'Umur maksimal 65 tahun.',
        ];
    }

    // public function get_mahasiswa(String $npm)
    // {
    //     $token = env('API_TOKEN_SIMAK', 'default_token');

    //     // Function untuk hit ke API
    //     function get_data($url)
    //     {
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //         $result = curl_exec($ch);
    //         if (!empty(curl_error($ch))) {
    //             $result = print_r(curl_error($ch) . ' - ' . $url);
    //         }
    //         curl_close($ch);
    //         return $result;
    //     }
    //     function str_curl($url, $data)
    //     {
    //         return $url . '?' . http_build_query($data);
    //     }

    //     $response_mahasiswa = json_decode(get_data(str_curl('https://simak.unkhair.ac.id/apiv2/mahasiswa', ['token' => $token, 'nim' => $npm])), TRUE);

    //     if ($response_mahasiswa && $response_mahasiswa['status'] == true) {
    //         return $response_mahasiswa['data'];
    //     }
    // }
}
