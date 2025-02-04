<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($tgl, $time = TRUE)
    {
        if (!$tgl) {
            return '';
        }
        $date = \Carbon\Carbon::parse($tgl)->locale('id');

        $date->settings(['formatFunction' => 'translatedFormat']);

        if (!$time) {
            return $date->format('j F Y');
        }
        return $date->format('j F Y H:i');
    }
}
