<?php

if (!function_exists('post_data')) {
  function post_data($url, $data)
  {
    $useragent = 'PHP Client 1.0 (curl) ' . phpversion();  // set user agent
    $ch = curl_init(); // persiapkan curl
    curl_setopt($ch, CURLOPT_URL, $url); // set url 
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //post data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data trasnfer 
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent); //panggil useragent
    $output = curl_exec($ch); //output ke string
    curl_close($ch); // tutup curl 
    return $output; // mengembalikan hasil curl
  }
}

if (!function_exists('get_data')) {
  function get_data($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    if (!empty(curl_error($ch))) {
      $result = print_r(curl_error($ch) . ' - ' . $url);
    }
    curl_close($ch);
    return $result;
  }
}

if (!function_exists('str_curl')) {
  function str_curl($url, $data)
  {
    return $url . '?' . http_build_query($data);
  }
}

if (!function_exists('get_token')) {
  function get_token()
  {
    $username_admin = env('API_USERNAME_SIMAK');
    $password_admin = env('API_PASSWORD_SIMAK');
    $url_simak = env('API_URL_SIMAK');
    $response  = post_data($url_simak . '/4pisim4k/index.php/token', ['username' => $username_admin, 'password' => $password_admin]);
    return json_decode($response, true);
  }
}
