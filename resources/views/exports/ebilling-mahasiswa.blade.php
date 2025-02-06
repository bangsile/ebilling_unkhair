<h4>Rekening Koran Unkhair Periode : <b>{{ $tgl_transaksi }}</b></h4>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Trx ID</th>
            <th style="text-align:left;">VA</th>
            <th>Bank</th>
            <th style="text-align:right;">Nominal</th>
            <th>UKT</th>
            <th>NPM</th>
            <th>Nama Mahasiswa</th>
            <th style="text-align:left;">Angkatan</th>
            <th>Prodi</th>
            <th>Ket</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $billing)
            <tr>
                <td>{{ tgl_indo($billing->updated_at) }}</td>
                <td>{{ $billing->trx_id }}</td>
                <td style="text-align:left;">{{ "'" . $billing->no_va }}</td>
                <td>{{ $billing->nama_bank }}</td>
                <td style="text-align:right;">{{ $billing->nominal }}</td>
                <td>{{ $billing->kategori_ukt }}</td>
                <td>{{ $billing->no_identitas }}</td>
                <td>{{ ucwords(strtolower($billing->nama)) }}</td>
                <td style="text-align:left;">{{ $billing->angkatan }}</td>
                <td>{{ $billing->kode_prodi . ' - ' . $billing->nama_prodi }}</td>
                <td>
                    @php
                        $ket = '-';
                        if ($billing->jenis_bayar == 'ukt') {
                            $ket = 'Pembayaran UKT ' . $billing->tahun_akademik;
                        } elseif (in_array($billing->jenis_bayar, ['umb', 'ipi', 'pemkes'])) {
                            $ket = 'Pembayaran UKT ' . $billing->tahun_akademik;
                            $ket .= '<br>Jalur ' . $billing->jalur;
                        }
                        echo $ket;
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
