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
