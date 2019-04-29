<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {

	    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

        $date = $pdo->prepare('SELECT absensi.tanggal
                               FROM absensi
                               LEFT JOIN kyu ON (kyu.id_kyu=absensi.kyu_id)
                               LEFT JOIN kenshin ON (absensi.id_kenshin=kenshin.id_kenshin)
                               WHERE absensi.kyu_id = '.$kyu_id.'
                               AND absensi.periode_id = '.$periode.'
                               GROUP BY absensi.tanggal
                               ORDER BY absensi.tanggal ASC');
        $date->execute();
        $date->setFetchMode();
        $date = $date->fetchAll(PDO::FETCH_ASSOC);

        $month = array();
        foreach ($date as $dkey => $dvalue) {
            $month['month'][ltrim(date('m', strtotime($dvalue['tanggal'])), '0')][] = 1;
        }
        

        echo json_encode(array_merge(array('date' => $date), $month));
        
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>