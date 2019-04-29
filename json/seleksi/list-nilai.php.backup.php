<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    // $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
    // $kyu        = (isset($_POST['kyu'])) ? trim($_POST['kyu']) : '';
    $periode    = 10;
    $kyu        = 1;

    $query = $pdo->prepare('SELECT kenshin.id_kenshin, kenshin.nama_kenshin
                            FROM kenshin
                            LEFT JOIN nilai_kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                            LEFT JOIN kriteria ON (kriteria.id_kriteria=nilai_kenshin.id_kriteria)
                            LEFT JOIN periode ON (periode.id_periode=nilai_kenshin.id_periode)
                            WHERE periode.id_periode = ' . $periode . ' AND nilai_kenshin.kyu_id = ' . $kyu . ' GROUP BY kenshin.id_kenshin ');
	$query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);

    $nilai = $pdo->prepare('SELECT kenshin.id_kenshin, kenshin.nama_kenshin, nilai_kenshin.nilai, kriteria.nama, kriteria.id_kriteria
                            FROM kenshin
                            LEFT JOIN nilai_kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                            LEFT JOIN kriteria ON (kriteria.id_kriteria=nilai_kenshin.id_kriteria)
                            LEFT JOIN periode ON (periode.id_periode=nilai_kenshin.id_periode)
                            WHERE periode.id_periode = ' . $periode . ' AND nilai_kenshin.kyu_id = ' . $kyu . ' GROUP BY kriteria.id_kriteria ');
	$nilai->execute();
    $nilai->setFetchMode(PDO::FETCH_ASSOC);

    $nilai = $nilai->fetchAll();

    $final_data = [];

    foreach($query->fetchAll() as $key => $data) {
        $final_data['data'][$data['id_kenshin']] = [];
        $final_data['data'][$data['id_kenshin']]['nama_kenshin'] = $data['nama_kenshin'];
        $final_data['data'][$data['id_kenshin']]['nilai'] = array();
    }

    $kriteria = array();
    foreach($nilai as $key => $data) {
        if(!in_array($data['id_kriteria'], $kriteria)) {
            array_push($kriteria, $data['id_kriteria']);
        }

        $final_data['data'][$data['id_kenshin']]['nilai'][$data['id_kriteria']] = $data['nilai'];
    }
    
    $final_data['listed_kriteria'] = $kriteria;


    if($query->rowCount() > 0) {
        echo json_encode($final_data);
        exit(); // stop the process if not available
    } else {
        echo json_encode(
            array(
                'error' => true,
                'msg' => 'Something went wrong!'
            )
        );
    }
?>