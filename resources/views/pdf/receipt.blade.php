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
            font-family: 'Courier New', Courier, monospace;
            margin: 16px;
            padding: 0;
            color: #000;
        }

        .header {
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 16px;
            width: 100%
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

        .content-left,
        .content-right {
            width: 48%;
        }

        .content-left p,
        .content-right p {
            margin: 4px 0;
        }

        .content-left table,
        .content-right table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-left table td,
        .content-right table td {
            padding: 4px;
            border: 1px solid #000;
        }

        .terms {
            border-top: 2px solid #000;
            padding-top: 8px;
            font-size: 12px;
        }

        .terms p {
            margin: 4px 0;
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
    </style>
</head>

<body>
    <table class="header">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="header-left">
                                <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
                            </div>
                        </td>
                        <td style="padding-left: 16px; vertical-align: bottom">
                            <div class="header-left">
                                <p>@ i.service.jember</p>
                                <p>089638911151</p>
                                <p>Jl. Karimata No. 58B</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="vertical-align: bottom">
                <div class="header-right">
                    <p style="font-size: 16px; margin-bottom: 16px">NOTA NO: <strong>A048</strong></p>
                    <p>TANGGAL: 8 Des 2023</p>
                    <p>NAMA: [Nama Pelanggan]</p>
                    <p>NO. HANDPHONE: [No. Handphone]</p>
                    <p style="margin-top: 16px; font-size: 16px">GARANSI: <strong>10 BULAN</strong></p>
                </div>
            </td>
        </tr>


    </table>

    <div class="content">
        <div class="content-left">
            <table>
                <tr>
                    <td>KELENGKAPAN</td>
                    <td>
                        <p>SIM CARD <input type="checkbox"></p>
                        <p>BATTERY <input type="checkbox" checked></p>
                        <p>MEMORY <input type="checkbox"></p>
                        <p>BACK CASING <input type="checkbox" checked></p>
                        <p>TRAY <input type="checkbox"></p>
                    </td>
                </tr>
                <tr>
                    <td>DATA HANDPHONE</td>
                    <td>
                        <p>MERK: iPhone 11</p>
                        <p>TYPE: Putih</p>
                        <p>NO. IMEI: [IMEI]</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="content-right">
            <table>
                <tr>
                    <td>SERVICE</td>
                    <td><input type="checkbox"> SERVICE</td>
                </tr>
                <tr>
                    <td>GARANSI</td>
                    <td><input type="checkbox" checked> GARANSI (3 Bulan)</td>
                </tr>
                <tr>
                    <td>KERUSAKAN</td>
                    <td>Replace baterai iPhone 11</td>
                </tr>
                <tr>
                    <td>BIAYA SERVICE</td>
                    <td>Rp. 500.000</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="terms">
        <p>Ketentuan:</p>
        <p>- Setiap pengambilan harus disertai nota ini.</p>
        <p>- Nota ini berlaku selama 1 (satu) bulan sesuai tanggal nota.</p>
        <p>- Kehilangan atas kerusakan pada barang yang diservice setelah lewat 1 (satu) bulan bukan tanggung jawab
            kami.</p>
        <p>- Garansi barang yang diservice hanya berlaku 1 (satu) kali dengan kerusakan yang sama.</p>
        <p>- Garansi tidak berlaku untuk Service LCD, Nota Hilang, Human Error.</p>
    </div>

    <div class="footer">
        <div>
            <p>PEMILIK HANDPHONE</p>
            <p>[Tanda Tangan]</p>
        </div>
        <div>
            <p>TEKNISI</p>
            <p>[Tanda Tangan]</p>
        </div>
    </div>
</body>

</html>
