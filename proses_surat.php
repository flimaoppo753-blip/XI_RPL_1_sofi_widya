<?php

// Masukkan library DomPDF
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data dari form
    $nama       = htmlspecialchars($_POST['nama'] ?? '');
    $nis        = htmlspecialchars($_POST['nis'] ?? '');
    $kelas      = htmlspecialchars($_POST['kelas'] ?? '');
    $alasan     = htmlspecialchars($_POST['alasan'] ?? '');
    $keterangan = htmlspecialchars($_POST['keterangan'] ?? '');

    // Format tanggal
    $tgl_mulai = !empty($_POST['tgl_mulai'])
        ? date('d F Y', strtotime($_POST['tgl_mulai']))
        : '';

    $tgl_selesai = !empty($_POST['tgl_selesai'])
        ? date('d F Y', strtotime($_POST['tgl_selesai']))
        : '';

    $tgl_sekarang = date('d F Y');

    // Template HTML PDF
    $html = '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">

        <title>Cetak Surat Izin</title>

        <style>
            body {
                font-family: "Times New Roman", serif;
                font-size: 12pt;
                margin: 20px;
            }

            .kop {
                font-family: "Century Gothic", sans-serif;
                text-align: center;
                border-bottom: 3px double #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }

            .kop h2 {
                margin: 0;
                font-size: 16pt;
                text-transform: uppercase;
            }

            .kop p {
                margin: 2px;
                font-size: 10pt;
            }

            .title {
                text-align: center;
                font-weight: bold;
                text-decoration: underline;
                margin-bottom: 25px;
            }

            .content {
                line-height: 1.6;
                text-align: justify;
            }

            .table-data {
                margin: 15px 0 15px 30px;
                width: 90%;
            }

            .table-data td {
                padding: 4px 0;
                vertical-align: top;
            }

            .ttd-container {
                width: 100%;
                margin-top: 50px;
            }

            .ttd-box {
                float: right;
                width: 200px;
                text-align: center;
            }
        </style>
    </head>

    <body>

        <div class="kop">
            <h2>SMK TEXMACO SEMARANG</h2>
            <p>Jl. Raya Mangkang Kulon | Telp: (024) 220.8888</p>
        </div>

        <div class="title">
            SURAT IZIN MENINGGALKAN KELAS
        </div>

        <div class="content">

            <p>
                Yang bertanda tangan di bawah ini:
            </p>

            <table class="table-data">

                <tr>
                    <td width="130">Nama</td>
                    <td width="15">:</td>
                    <td><b>' . $nama . '</b></td>
                </tr>

                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td>' . $nis . '</td>
                </tr>

                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>' . $kelas . '</td>
                </tr>

            </table>

            <p>
                Bermaksud untuk mengajukan izin meninggalkan kelas,
                pada tanggal <b>' . $tgl_mulai . '</b>
                sampai dengan <b>' . $tgl_selesai . '</b>
                dikarenakan <b>' . $alasan . '</b>.
            </p>

            ' . (
                !empty($keterangan)
                ? '<p><b>Detail keterangan:</b> ' . $keterangan . '</p>'
                : ''
            ) . '

            <p>
                Demikian surat pengajuan izin ini saya buat dengan
                sebenar-benarnya. Atas perhatian dan pengertian
                Bapak/Ibu, saya ucapkan terima kasih.
            </p>

        </div>

        <div class="ttd-container">
            <div class="ttd-box">

                <p>
                    Semarang, ' . $tgl_sekarang . '<br>
                    Hormat saya,
                </p>

                <br><br><br>

                <p>
                    <b>' . $nama . '</b>
                </p>

            </div>
        </div>

    </body>
    </html>
    ';

    // Konfigurasi DomPDF
    $options = new Options();

    // Mengaktifkan resource eksternal jika diperlukan
    $options->set('isRemoteEnabled', true);

    // Inisialisasi DomPDF
    $dompdf = new Dompdf($options);

    // Load HTML
    $dompdf->loadHtml($html);

    // Ukuran kertas A4 portrait
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF
    $dompdf->render();

    // Nama file PDF
    $nama_file = "Surat_Izin_" . str_replace(' ', '_', $nama) . ".pdf";

    // Tampilkan PDF di browser
    $dompdf->stream($nama_file, [
        "Attachment" => false
    ]);
}
?>