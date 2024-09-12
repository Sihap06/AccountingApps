<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Servis iPhone</title>
    <style>
        @page {
            size: 24cm 13.5cm;
            margin: 0;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 12px;
            font-size: 12px;
            padding: 0;
            color: #000;
            text-transform: uppercase
        }

        .header {
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 8px;
            width: 100%;
            vertical-align: top
        }

        .header-left img {
            width: 200px;
        }

        .header-left p {
            margin: 4px 0;
            font-size: 12px
        }

        .header-right {
            text-align: right;
            font-size: 12px;
        }

        .header-right p {
            margin: 4px 0;
        }

        .terms {
            font-size: 8px;
            margin-top: 24px
        }

        .terms p {
            margin: 4px 0;
        }

        .terms ul {
            margin: 0px;
            padding-left: 10px;
        }


        .items {
            width: 100%;
            font-size: 12px;
            border-bottom: 0.5px solid #000;
            padding-bottom: 16px;
            height: 120px
        }

        .items td {
            padding: 4px
        }

        .handphone td {
            padding: 4px
        }

        .amount {
            margin-left: 100px
        }

        .amount td {
            padding: 4px
        }

        .signature {
            width: 100%;
            margin-top: 50px;
        }

        .signature p {
            margin: 4px 0;
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td>
                <div class="header-left">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
                    <p style="margin-top: 8px">Jl. Karimata No. 58B <span style="margin-left: 10px">089638911151</span>
                    </p>
                </div>
            </td>
            <td style="vertical-align: bottom">
                <div class="header-right">
                    <p style="font-size: 14px; margin-bottom: 14px">Nota No: <strong>A048</strong></p>
                    <p>Tanggal: {{ $date }}</p>
                    <p>Nama: {{ $detailItem['customer_name'] }}</p>
                    <p>No. Handphone: {{ $detailItem['no_telp'] }}</p>
                </div>
            </td>
        </tr>


    </table>

    <table class="items">
        <thead>
            <tr align="left">
                <th width="10%">No</th>
                <th width="40%">Service</th>
                <th width="30%">Harga</th>
                <th width="20%">Garansi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $detailItem['service'] }}</td>
                <td>Rp {{ number_format($detailItem['biaya']) }}</td>
                <td>
                    @if ($detailItem['warranty'] != null)
                        {{ $detailItem['warranty'] }}
                        @if ($detailItem['warranty_type'] == 'daily')
                            Hari
                        @elseif ($detailItem['warranty_type'] == 'weekly')
                            Minggu
                        @else
                            Bulan
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @foreach ($detailItem['items'] as $index => $item)
                <tr>
                    <td>{{ $index + 2 }}</td>
                    <td>{{ $item['service'] }}</td>
                    <td>Rp {{ number_format($item['biaya']) }}</td>
                    <td>
                        @if ($item['warranty'] != null)
                            {{ $item['warranty'] }}
                            @if ($item['warranty_type'] == 'daily')
                                Hari
                            @elseif ($item['warranty_type'] == 'weekly')
                                Minggu
                            @else
                                Bulan
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%; font-size: 12px">
        <tr style="vertical-align: top">
            <td width="60%">
                <p style="text-decoration: underline; margin-bottom: 4px; margin-top: 4px"><strong>Data
                        Handphone</strong></p>
                <table class="handphone">
                    <tr>
                        <td>Merk</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>No. Imei</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                </table>
                <div class="terms">
                    <p>Ketentuan:</p>
                    <ul>
                        <li>Pengambilan atau claim garansi WAJIB disertakan nota fisik dan tidak ada REFUND dana</li>
                        <li>Garansi tidak berlaku jika segel rusak atau human error dan hanya berlaku 1x pergantian part
                        </li>
                        <li>Garansi baterai berlaku jika BH turun derastis dan drop (mlembung tidak termasuk)</li>
                        <li>Kerusakan atau Kehilangan dalam waktu 3 bulan di luar tanggung jawab kami</li>
                    </ul>
                </div>
            </td>
            <td width="40%">
                <table class="amount">
                    <tr>
                        <td><strong>Total</strong></td>
                        <td>:</td>
                        <td>Rp {{ number_format($detailItem['total']) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment</strong></td>
                        <td>:</td>
                        <td>{{ $payment_method }}</td>
                </table>

                <table class="signature">
                    <tr style="height: 100%;">
                        <td style="vertical-align: bottom;">
                            <div style="text-align: center">
                                <p>Pemilik</p>
                                <p style="margin-top: 50px">........................</p>
                            </div>
                        </td>
                        <td style="vertical-align: bottom;">
                            <div style="text-align: center">
                                <p>Teknisi</p>
                                <p style="margin-top: 50px">........................</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
