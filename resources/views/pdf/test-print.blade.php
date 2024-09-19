<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Servis iPhone</title>
    <style>
        @media print {
            body {
                font-family: "Courier New", Courier, monospace;
                padding: 0;
            }

            @page {
                size: 24cm 13.5cm;
                margin: 0;
            }
        }

        @page {
            size: 24cm 13.5cm;
            margin: 0;
        }

        body {
            font-family: "Courier New", Courier, monospace;
            /* margin: 12px;
            font-size: 12px; */
            padding: 0;
            /* color: #000; */
            /* text-transform: uppercase */
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
                    {{-- <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo"> --}}
                    <p style="margin-top: 8px">Jl. Karimata No. 58B <span style="margin-left: 10px">089638911151</span>
                    </p>
                </div>
            </td>
            <td style="vertical-align: bottom">
                <div class="header-right">
                    <p style="font-size: 14px; margin-bottom: 14px">Nota No:
                        <strong>INV00001</strong>
                    </p>
                    <p>Tanggal: 20/09/2024</p>
                    <p>Nama: CIMO</p>
                    <p>No. Handphone: 0912091029012</p>
                </div>
            </td>
        </tr>


    </table>

</body>

</html>
