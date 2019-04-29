<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
	    $id_kenshin     = (isset($_POST['id_kenshin'])) ? trim($_POST['id_kenshin']) : '';
	    $kyu_id         = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
	    $periode        = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

        $count = $pdo->prepare('SELECT *
                                FROM absensi 
                                WHERE kyu_id = ' . $kyu_id . '
                                AND periode_id = ' . $periode . '
                                AND id_kenshin = ' . $id_kenshin . '
                                GROUP BY tanggal');
        $count->execute();
        $count = $count->fetchAll();
        
        echo json_encode($count);
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>