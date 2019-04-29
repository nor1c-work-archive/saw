<?php 
    // header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    ob_start();

    if(isset($_POST)) {

        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
        $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $hasil      = $_POST['hasil'];

        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Document</title>
                <style>
                    @media print{@page {size: landscape}}
                    html {
                        font-family: Calibri;
                        margin: 0;
                        font-size: 12px;
                        margin-top: 100px;
                    }
                    ul li {
                        float:left;
                        list-style: none;
                    }
                    table {
                        border-collapse: collapse;
                    }

                    table, th, td {
                        border: 1px solid black;
                    }
                    table td {
                        padding: 3px;
                    }
                </style>
            </head>
            <body>
                <div style="width:90%;margin:0 auto;padding: 10px;">
                    
                    <div style="width:100%;text-align:center;">
                        <span style="font-size:15px;font-weight:bold;">
                            FORMULIR
                            <br>
                            PERMOHONAN UJIAN PENGURUS
                        </span>
                    </div>
                    <br><br><br><br>
                    
                        <span>
                            <ul>
                                <li style="width:33% !important;">
                                    Dojo : ...................................
                                </li>
                                <li style="width:33% !important;">
                                    Kota/Kabupaten* : ...................................
                                </li>
                                <li style="width:33% !important;">
                                    Provinsi : ...................................
                                </li>
                            </ul>
                        </span>
                        <br><br><br>
                        <span>
                            Salam persaudaraan,<br>
                            Dengan ini kami sampaikan permohonan sekaligus rekapitulasi kenshi-kenshi kami yang akan mengikuti ujian kenaikan tingkat, dengan catatan mereka telah memenuhi persyaratan teknis dan/atau administrasi
                        </span>
                    

                        <table style="width:100%;">
                            <tr style="">
                                <td style="width:50px;">NO</td>
                                <td>NAMA LENGKAP</td>
                                <td>NIK</td>
                                <td>KYU/DAN</td>
                            </tr>';

                            $lulus = array();
                            foreach ($hasil as $key => $value) {
                                if ($value['nilai'] >= 70) {
                                    array_push($lulus, $value['nama_kenshin']);
                                }
                            }

                            $nilai_kenshin = $pdo->prepare('SELECT kenshin.nama_kenshin, kenshin.nik, kyu.kyu_title FROM nilai_kenshin
                                                            LEFT JOIN kenshin ON (nilai_kenshin.id_kenshin=kenshin.id_kenshin)
                                                            LEFT JOIN kyu ON (kyu.id_kyu=nilai_kenshin.kyu_id)
                                                            WHERE nilai_kenshin.id_periode = :id_periode AND nilai_kenshin.kyu_id = :kyu_id
                                                            AND kenshin.nama_kenshin IN ("' . implode('","', $lulus) . '")
                                                            GROUP BY nilai_kenshin.id_kenshin');
                            $nilai_kenshin->execute(
                                array(
                                    'id_periode'    => $periode,
                                    'kyu_id'        => $kyu_id
                                )
                            );
                            $nilai = $nilai_kenshin->fetchAll(PDO::FETCH_ASSOC);

                            $no = 1;
                            foreach ($nilai as $key => $value) {
                                echo '<tr>
                                          <td>'.$no.'</td>
                                          <td>'.$value['nama_kenshin'].'</td>
                                          <td>'.$value['nik'].'</td>
                                          <td>'.$value['kyu_title'].'</td>
                                      </tr>';

                                $no++;
                            }

                        echo '</table>

                        
                    
                        <span>
                            <ul>
                                <li style="width:25% !important;">
                                    Menyetujui:<br>
                                    PB. PERKEMI
                                    <br><br>
                                    __________________________<br>
                                    Nama &nbsp;&nbsp;   : __________________<br>
                                    Jabatan : __________________<br>
                                    Tanggal : __________________
                                </li>
                                <li style="width:25% !important;">
                                    Menyetujui:<br>
                                    PERKEMI Pengrov ................
                                    <br><br>
                                    __________________________<br>
                                    Nama  &nbsp;&nbsp;  : __________________<br>
                                    Jabatan : __________________<br>
                                    Tanggal : __________________
                                </li>
                                <li style="width:25% !important;">
                                    Menyetujui:<br>
                                    PERKEMI Pengkab/Pengkot* ................
                                    <br><br>
                                    __________________________<br>
                                    Nama   &nbsp;&nbsp; : __________________<br>
                                    Jabatan : __________________<br>
                                    Tanggal : __________________
                                </li>
                                <li style="width:25% !important;">
                                    <br>
                                    PERKEMI Pengdo ................
                                    <br><br>
                                    __________________________<br>
                                    Nama   &nbsp;&nbsp; : __________________<br>
                                    Jabatan : __________________<br>
                                    Tanggal : __________________
                                </li>
                            </ul>
                        </span>

                        

                        <br><br><br><br><br><br><br><br><br><br>
                        <div>
                            *Coret yang tidak perlu
                        </div>

                </div>
            </body>
            </html>
        ';

    }

    // $result = ob_get_flush();
    // echo $result;

?>