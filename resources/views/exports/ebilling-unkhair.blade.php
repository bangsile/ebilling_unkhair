<h4>Rekening Koran Unkhair Periode : <b>{{ $tgl_transaksi }}</b></h4>
<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Trx ID</th>
            <th style="text-align:left;">VA</th>
            <th>Bank</th>
            <th style="text-align:right;">Nominal</th>
            <th>Nama Lengkap</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($result as $billing)
            <tr>
                <td>{{ tgl_indo($billing->updated_at) }}</td>
                <td>{{ $billing->trx_id }}</td>
                <td style="text-align:left;mso-number-format:'@';">&nbsp;{{ $billing->no_va }}</td>
                <td>{{ $billing->nama_bank }}</td>
                <td style="text-align:right;">{{ $billing->nominal }}</td>
                <td>{{ ucwords(strtolower($billing->nama)) }}</td>
                <td>
                    @php
                        echo json_decode($billing->detail)->deskripsi ?? '-';
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
