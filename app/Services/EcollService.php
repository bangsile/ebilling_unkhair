<?php

namespace App\Services;

use Exception;

class EcollService
{
  // CREATE VA BTN
  public function createVaBTN($data)
  {
    $api_key = env('API_KEY_ECOLL');
    $api_url = env('API_URL_ECOLL');

    $params = [
      'demo' => true,
      'expired_va' => 2, // expired_va
      'apikey' => $api_key,
      'kode_payment' => '007',
      'jenis_payment' => $data['jenis_bayar'],
      'prefix_trx' => 'TRX',
      'nama' => $data['nama'],
      'nominal' => $data['nominal'],
      'deskripsi' => $data['deskripsi'],
    ];

    // dd($params);
    $response = json_decode(post_data("{$api_url}/btn/createva.php", $params), TRUE);
    // $response = json_decode(post_data('http://ecoll.unkhair.ac.id/btn/createva.php', $params), TRUE);
    // dd($response);
    if (!$response['response']) {
      throw new Exception("Respon failed");
    } else {
      $message = 'Berhasil Membuat Virtual Account BANK BTN';
      return [
        'res' => true,
        'msg' => $message,
        'data' => [
          'trx_id' => $response['data']['trx_id'],
          'no_va' => $response['data']['va'],
          'expired' => $response['data']['expired_va'],
        ]
      ];
    }
  }


  // CREATE VA BNI
  public function createVaBNI($data)
  {
    $api_key = env('API_KEY_ECOLL');
    $api_url = env('API_URL_ECOLL');

    $params = [
      'apikey' => $api_key,
      'type' => 'createbilling',
      'trx_id' => 'TRX' . date('Y') . rand(10, 99) . time(),
      'trx_amount' => $data['nominal'],
      'expired_va' => 1, // expired_va 1 hari
      'customer_name' => $data['nama'],
      'customer_email' => $data['email'] ?? '',
      'description' => $data['deskripsi']
    ];
    // dd($params);
    $response = json_decode(post_data("{$api_url}/bni/createva.php", $params), TRUE);
    if (!$response['response']) {
      throw new Exception("Respon failed");
    }

    // inquiry va
    $params = [
      'apikey' => $api_key,
      'trx_id' => $response['data']['trx_id'],
    ];

    $response = json_decode(post_data("{$api_url}/bni/inquiry.php", $params), TRUE);
    // dd($response);

    if (!$response['response']) {
      throw new Exception("Respon failed");
    } else {
      $message = 'Berhasil Membuat Virtual Account BANK BNI';
      return [
        'res' => true,
        'msg' => $message,
        'data' => [
          'trx_id' => $response['data']['trx_id'],
          'no_va' => $response['data']['virtual_account'],
          'expired' => $response['data']['datetime_expired'],
        ]
      ];
    }
  }

  public function updateVaBNI($data)
  {
    $api_key = env('API_KEY_ECOLL');
    $api_url = env('API_URL_ECOLL');

    // $tgl_expire_billing = date('Y-m-d', strtotime($bayar['tgl_expire']));
		// $tgl_now = new DateTime(date("Y-m-d"));
		// $tgl_expire = new DateTime($tgl_expire_billing);
		// $jarak = $tgl_expire->diff($tgl_now);
		// $expired_va = $jarak->d;
		
		$params = [
			'apikey' => $api_key,
			'type' => 'updatebilling',
			'trx_id' => $data["trx_id"],
			'virtual_account' => $data["no_va"],
			'trx_amount' => $data['nominal'],
			'expired_va' => ($data["tgl_expire"]) ? $data["tgl_expire"] : 1, // expired_va 1 hari
			'customer_name' => $data["nama"],
		];
		
		// dd($params);
		
		$response = json_decode(post_data("{$api_url}/bni/createva.php", $params), TRUE);
		// dd($response);
		if (!$response['response']) {
      dd($response);
			return false;
		}
		return $response;
		//update nominal di database billing_ukts jika  response true
  }
}
