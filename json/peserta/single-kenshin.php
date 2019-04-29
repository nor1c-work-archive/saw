<?php 

	require_once('../../includes/init.php');
	
	header('Content-Type: application/json');

	$ada_error = false;
	$result = '';

	$id_kenshin = (isset($_GET['id'])) ? trim($_GET['id']) : '';

	if(!$id_kenshin) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$query = $pdo->prepare('SELECT id_kenshin, kyu_id, nama_kenshin, username, nim, nik, jurusan, email, hp, alamat, kyu.kyu_title, kyu.kyu_description as tingkatan
								FROM kenshin 
								LEFT JOIN kyu ON (kyu.id_kyu=kenshin.kyu_id)
								WHERE kenshin.id_kenshin = :id_kenshin');
		$query->execute(array('id_kenshin' => $id_kenshin));
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if(empty($result)) {
			echo json_encode(array('message' => '404 Data Not Found!'));
		} else {
			echo json_encode($result);
		}
	}
	
?>