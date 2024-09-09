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
            font-family: "Courier", monospace;
            margin: 8px;
            font-size: 12px;
            padding: 0;
            color: #000;
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
            font-size: 14px
        }

        .header-right {
            text-align: right;
            font-size: 14px;
        }

        .header-right p {
            margin: 4px 0;
        }

        .content {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .content-left {
            width: 40%;
            display: inline-block;
        }

        .content-left-container {
            border: 1px solid black;
            border-radius: 10px;
            padding: 8px
        }

        .content-right {
            width: 57%;
            display: inline-block;
            margin-left: 16px
        }

        .content-right-container {
            border: 1px solid black;
            border-radius: 10px;
            padding: 8px
        }

        .data-handphone td:first-child {
            width: 1px;
            /* Make the first column width minimal */
            white-space: nowrap;
            /* Prevent the first column from wrapping */
        }

        .data-handphone td:last-child {
            padding-left: 5px;
            /* Minimal space between the two columns */
        }

        .content-left p,
        .content-right p {
            margin: 4px 0;
        }

        /* .content-left table td,
        .content-right table td {
            padding: 4px;
            border: 1px solid #000;
        } */

        .terms {
            font-size: 10px;
        }

        .terms p {
            margin: 4px 0;
        }

        .terms ul {
            margin: 0px;
            padding-left: 10px;
        }

        .kerusakan {
            width: 100%;
            height: 220px;
            border-collapse: collapse;
            border: 1px solid black;
        }

        .kerusakan th,
        .kerusakan td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
            height: 15px;
            vertical-align: middle;
        }

        /* Apply rounded corners to the first and last cell of the first row */
        .kerusakan thead th:first-child {
            border-top-left-radius: 10px;
        }

        .kerusakan thead th:last-child {
            border-top-right-radius: 10px;
        }

        /* Apply rounded corners to the first and last cell of the last row */
        .kerusakan tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        .kerusakan tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        .kerusakan p {
            margin: 0;
        }




        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .footer div {
            text-align: center;
            width: 48%;
        }

        .items {
            width: 100%;
            font-size: 14px;
            border-bottom: 1px solid #000;
            padding-bottom: 16px;
            height: 100px
        }

        .items td {
            padding: 8px
        }
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td>
                <div class="header-left">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
                    <p style="margin-top: 8px">Jl. Karimata No. 58B - 089638911151</p>
                </div>
            </td>
            <td style="vertical-align: bottom">
                <div class="header-right">
                    <p style="font-size: 16px; margin-bottom: 16px">NOTA NO: <strong>A048</strong></p>
                    <p>TANGGAL: {{ $date }}</p>
                    <p>NAMA: [Nama Pelanggan]</p>
                    <p>NO. HANDPHONE: [No. Handphone]</p>
                    <p style="margin-top: 16px; font-size: 16px">GARANSI: <strong>10 BULAN</strong></p>
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
                <td>Replace LCD Iphone 11</td>
                <td>Rp 1.000.000</td>
                <td>1 Bulan</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Replace LCD Iphone 11</td>
                <td>Rp 1.000.000</td>
                <td>1 Bulan</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Replace LCD Iphone 11</td>
                <td>Rp 1.000.000</td>
                <td>1 Bulan</td>
            </tr>
            <tr>
                <td>1</td>
                <td>Replace LCD Iphone 11</td>
                <td>Rp 1.000.000</td>
                <td>1 Bulan</td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; font-size: 14px">
        <tr style="vertical-align: top">
            <td width="70%">
                <p style="text-decoration: underline; margin-bottom: 4px">Data Handphone</p>
                <table class="">
                    <tr>
                        <td>MERK</td>
                        <td>:</td>
                        <td>..................</td>
                    </tr>
                    <tr>
                        <td>TYPE</td>
                        <td>:</td>
                        <td>..................</td>
                    </tr>
                    <tr>
                        <td>NO. IMEI</td>
                        <td>:</td>
                        <td>..................</td>
                    </tr>
                </table>
            </td>
            <td width="30%">
                <p>Total Rp 4.900.000</p>
                <p>Payment: BRI</p>
            </td>
        </tr>
    </table>
</body>

</html>
