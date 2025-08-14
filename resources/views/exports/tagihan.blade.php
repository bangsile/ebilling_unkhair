<!DOCTYPE html>
<html>

<head>
    <title>Tagihan Pembayaran</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body style="padding: 25px">
    <table style="width: 100%;" border="0" cellspacing="0" cellpadding="10">
        <tr>
            <td style="text-align: center">
                <img src="{{ public_path('/logo.png') }}" alt="Unkhair" style="width: 65px">
            </td>
            <td>
                <div style="text-align: center; line-height: 2px; font-weight: bold; font-size: large; margin: auto">
                    <p>Billing Tagihan {{ $keterangan }}</p>
                    <p>Tahun Akademik {{ $tahun_akademik }}</p>
                    <p>Universitas Khairun Ternate</p>
                </div>
            </td>
            <td style="text-align: center;">
                @if ($bank == 'BTN')
                    <img src="{{ public_path('/logo/btn.png') }}" alt="btn"
                        style="width: 60px; border: 1px solid blue; border-radius: 100%">
                @endif
                @if ($bank == 'BNI')
                    <div
                        style="height: 60px; width: 60px; border: 1px solid rgb(215, 97, 0); border-radius: 100%; margin-left: auto; margin-right: auto;">
                        <img src="{{ public_path('/logo/bni.png') }}" alt="bni"
                            style="width: 50px; margin-top: 50%; transform: translateY(-50%);">
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div style="margin-top: 5px; border: 1px solid rgb(148, 148, 148); padding: 5px; font-size: 12px; ">
        <div style="font-weight: bold; line-height: 10px; margin-top: -10px;">
            <p>Ternate, {{ \Carbon\Carbon::parse(now())->translatedFormat('d F Y') }}</p>
            <p>Kepada Yth {{ $nama }}</p>
        </div>
        <div>
            <p style="font-weight: bold; line-height: 14px;">Berikut kami sampaikan tagihan {{ $keterangan }} anda
                kepada UNKHAIR dengan
                rincian sebagai berikut :</p>
            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%">
                <tr>
                    <td style="width: 30%; padding-bottom: 7px;">Kode Tagihan</td>
                    <td style="width: 5%">:</td>
                    <td>{{ $trx_id }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Nomor Billing</td>
                    <td>:</td>
                    <td>{{ $no_va }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Sistem Payment</td>
                    <td>:</td>
                    <td>E-Coll {{ $bank }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Nama</td>
                    <td>:</td>
                    <td>{{ $nama }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Program Studi</td>
                    <td>:</td>
                    <td>{{ $prodi }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Total Tagihan</td>
                    <td>:</td>
                    <td>{{ $nominal }}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 7px">Keterangan</td>
                    <td>:</td>
                    <td>{{ $keterangan . ' ' . $tahun_akademik }}</td>
                </tr>
                <tr>
                    <td>Jatuh Tempo</td>
                    <td>:</td>
                    <td>{{ $expire }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div
        style="border: 1px solid rgb(148, 148, 148); border-top: 0; padding: 5px; font-size: 12px; font-weight: bold; line-height: 20px;">
        @if ($bank == 'BTN')
            <p>Silakan lakukan pembayaran menggunakan Nomor Billing {{ $no_va }} melalui semua
                channel Bank di seluruh Indonesia sebelum tanggal jatuh tempo {{ $expire }}.</p>
        @endif
        @if ($bank == 'BNI')
            <p>Silakan lakukan pembayaran menggunakan Nomor Billing {{ $no_va }} melalui semua
                Bank BNI di seluruh Indonesia sebelum tanggal jatuh tempo {{ $expire }}.</p>
        @endif
    </div>
    {{-- <div class="head">
        <div>img unkhair</div>
        <div>
            <h2>Billing Tagihan Pembayaran UKT</h2>
            <h2>Tahun Akademik 20251</h2>
            <h2>Universitas Khairun Ternate</h2>
        </div>
        <div>img bank</div>
    </div>

    <div>
        <p>Ternate, 12 Agustus 2025</p>
        <p>Kepada Yth Usamah Robbani Abdullah</p>
        <p>Berikut kami sampaikan tagihan pembayaran UKT anda kepada UNKHAIR dengan rincian sebagai berikut : </p>

        <div class="detail">
            <div>Kode Tagihan</div>
            <div>:</div>
            <div>UKTMHS9023479023</div>
        </div>
    </div> --}}
</body>

</html>
