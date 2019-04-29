<?php 

	require_once('../../includes/init.php');
	
	header('Content-Type: application/json');

	$ada_error = false;
	$result = '';

	$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

	if(!$id_user) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		$query = $pdo->prepare('SELECT id_user, username, nama, email, role FROM user WHERE user.id_user = :id_user');
		$query->execute(array('id_user' => $id_user));
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if(empty($result)) {
			echo json_encode(array('message' => '404 Data Not Found!'));
		} else {
			echo json_encode($result);
		}
	}
	
?>