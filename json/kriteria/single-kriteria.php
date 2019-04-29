<?php 

	require_once('../../includes/init.php');
	
	header('Content-Type: application/json');

	$ada_error = false;
	$result = '';

	$id = (isset($_GET['id'])) ? trim($_GET['id']) : '';

	if(!$id) {
        echo json_encode(
            array(
                'error' => false,
                'msg'   => 'Kriteria tidak ditemukan!'
            )
        );
	} else {
		$query = $pdo->prepare('SELECT * 
								FROM kriteria 
								WHERE kriteria.id_kriteria = :id_kriteria');
	    $query->execute(array('id_kriteria' => $id));
		$kriteria_result = $query->fetchAll(PDO::FETCH_ASSOC);

		$variables = $pdo->prepare('SELECT *
									FROM pilihan_kriteria
									WHERE id_kriteria = ' . $kriteria_result[0]['id_kriteria']);
	    $variables->execute(array('id_kriteria' => $id));
		$var_result = $variables->fetchAll(PDO::FETCH_ASSOC);


		if(empty($kriteria_result)) {
			echo json_encode(
				array(
					'error'	=> false,
                	'msg'   => 'Kriteria tidak ditemukan!'
				)
			);
		} else {
			echo json_encode(
				array(
					'kriteria' 	=> $kriteria_result,
					'variables'	=> $var_result
				)
			);
		}
	}
	
?>