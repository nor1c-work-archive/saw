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
		$query = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
		$query->execute(array(
			'id_kriteria' => $id
		));
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if(empty($result)) {
			echo json_encode(array('message' => '404 Data Not Found!'));
		} else {
			echo json_encode($result);
		}
	}
	
?>