<?php 

	require_once('../../includes/init.php');
	
	header('Content-Type: application/json');

	$ada_error = false;
	$result = '';

	$id         = (isset($_POST['id'])) ? trim($_POST['id']) : '';
	$periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
    $kyu_id        = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';

	if(!$id) {
        echo json_encode(
            array(
                'error' => false,
                'msg'   => 'Data peserta  tidak ditemukan!'
            )
        );
	} else {
		$query = $pdo->prepare('SELECT * 
								FROM nilai_kenshin
                                WHERE id_kenshin = :id_kenshin
                                AND id_periode = :id_periode
                                AND kyu_id = :kyu_id');
	    $query->execute(
            array(
                'id_kenshin'    => $id,
                'id_periode'    => $periode,
                'kyu_id'        => $kyu_id
            )
        );
		$nilai_result = $query->fetchAll(PDO::FETCH_ASSOC);

		$variables = $pdo->prepare('SELECT *
									FROM kenshin
									WHERE id_kenshin = :id_kenshin');
	    $variables->execute(array('id_kenshin' => $id));
		$peserta_result = $variables->fetchAll(PDO::FETCH_ASSOC);


		if(empty($nilai_result)) {
			echo json_encode(
				array(
					'error'	=> false,
                	'msg'   => 'Data peserta tidak ditemukan!'
				)
			);
		} else {
			echo json_encode(
				array(
					'peserta'	=> $peserta_result,
					'nilai' 	=> $nilai_result
				)
			);
		}
	}
	
?>