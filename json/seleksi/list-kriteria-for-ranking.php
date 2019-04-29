<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
	    $kyu_id  = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
	    $periode = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

        $kriteria = $pdo->prepare('SELECT * 
                                   FROM kriteria 
                                   WHERE kyu_id = ' . $kyu_id . '
                                   AND periode_id = ' . $periode . '
                                   GROUP BY id_kriteria');
        $kriteria->execute();
        $kriteria = $kriteria->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($kriteria);
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>