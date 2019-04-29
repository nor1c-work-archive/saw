<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
	    $kyu_id  = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
	    $periode = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
	    $id_kenshin = (isset($_POST['id_kenshin'])) ? trim($_POST['id_kenshin']) : '';

        $kriteria = $pdo->prepare('SELECT * 
                                   FROM kriteria 
                                   WHERE kyu_id = ' . $kyu_id . '
                                   AND periode_id = ' . $periode . '
                                   GROUP BY id_kriteria');
        $kriteria->execute();
        $kriteria = $kriteria->fetchAll(PDO::FETCH_ASSOC);

        foreach ($kriteria as $key => $value) {
            $nilai[$key] = $pdo->prepare('SELECT * 
                                       FROM nilai_kenshin 
                                       WHERE kyu_id = ' . $kyu_id . '
                                       AND id_kriteria = '.$value['id_kriteria'].'
                                       AND id_kenshin = ' . $id_kenshin . '
                                       GROUP BY id_kriteria');
            $nilai[$key]->execute();
            $nilai[$key] = $nilai[$key]->fetchAll(PDO::FETCH_ASSOC);
            
            $kriteria[$key]['nilai'] = $nilai[$key][0]['nilai'];
        }

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