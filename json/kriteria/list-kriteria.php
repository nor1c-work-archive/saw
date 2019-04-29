

<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
	    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

        $query = $pdo->prepare('SELECT * 
                                FROM kriteria kr 
                                WHERE kr.kyu_id = '.$kyu_id.'
                                AND kr.periode_id = '.$periode.'
                                ORDER BY urutan_order ASC');
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        
        echo json_encode($query->fetchAll());
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>