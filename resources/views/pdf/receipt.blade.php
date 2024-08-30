<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Servis iPhone</title>
    <style>
        @page {
            size: 8.27in 3.90in;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .invoice-container {
            padding: 20px;
            margin: 0 auto;
            border: 1px dashed #000;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 80px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 5px 0;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        .info-section,
        .data-handphone,
        .kerusakan-section,
        .biaya-section,
        .signature-section {
            margin-bottom: 10px;
        }

        .info-section div,
        .data-handphone div,
        .kerusakan-section div,
        .biaya-section div {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .info-section div p,
        .data-handphone div p,
        .kerusakan-section div p,
        .biaya-section div p {
            margin: 2px 0;
        }

        .checkboxes {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        .checkboxes div {
            display: flex;
            align-items: center;
        }

        .checkboxes label {
            margin-left: 5px;
        }

        .ketentuan-section {
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-section div {
            width: 45%;
            text-align: center;
        }

        .signature-section p {
            margin: 40px 0 0;
            border-top: 1px solid #000;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <img src="logo.png" alt="Logo Perusahaan"> <!-- Ganti dengan URL logo Anda -->
            <h1>iService Jember</h1>
            <p>@iservicejember</p>
            <p>089639911151</p>
            <p>Jl. Karimata No. 58B</p>
        </div>

        <div class="info-section">
            <div>
                <p><strong>Tanggal:</strong> 8 Aug 2023</p>
                <p><strong>Nota No:</strong> A048</p>
            </div>
            <div>
                <p><strong>Nama:</strong> John Doe</p>
                <p><strong>No. HP:</strong> 0812-3456-7890</p>
            </div>
        </div>

        <div class="data-handphone">
            <p><strong>Data Handphone:</strong></p>
            <div>
                <p><strong>Merek:</strong> iPhone 11</p>
                <p><strong>Warna:</strong> Putih</p>
            </div>
            <div class="checkboxes">
                <div>
                    <input type="checkbox" id="sim" name="sim" checked>
                    <label for="sim">SIM Tray</label>
                </div>
                <div>
                    <input type="checkbox" id="battery" name="battery">
                    <label for="battery">Battery</label>
                </div>
                <div>
                    <input type="checkbox" id="back" name="back" checked>
                    <label for="back">Back Casing</label>
                </div>
                <div>
                    <input type="checkbox" id="tray" name="tray">
                    <label for="tray">Tray</label>
                </div>
            </div>
        </div>

        <div class="kerusakan-section">
            <p><strong>Kerusakan:</strong></p>
            <div>
                <p>Replace Baterai iPhone 11</p>
            </div>
        </div>

        <div class="biaya-section">
            <p><strong>Biaya Servis:</strong></p>
            <div>
                <p>Rp 500.000</p>
            </div>
        </div>

        <div class="ketentuan-section">
            <p><strong>Ketentuan:</strong></p>
            <ul>
                <li>Setiap pengambilan harus disertai nota ini.</li>
                <li>Kami tidak bertanggung jawab atas kerusakan pada unit jika ada perbaikan oleh pihak ketiga.</li>
            </ul>
        </div>

        <div class="signature-section">
            <div>
                <p>Pemilik Handphone</p>
            </div>
            <div>
                <p>Teknisi</p>
            </div>
        </div>
    </div>
</body>

</html>
