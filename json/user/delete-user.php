<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $errors = array();
    $sukses = false;

	$id = (isset($_POST['id'])) ? trim($_POST['id']) : '';

	if(!$id) {
        echo json_encode(
            array(
                'error' => false,
                'msg'   => 'User tidak ditemukan!'
            )
        );
	} else {
		try {
            $handle = $pdo->prepare('DELETE FROM user WHERE id_user = :id_user');				
			$handle->execute(array(
				'id_user' => $id
			));

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'User berhasil dihapus!'
                )
            );
        } catch (Exception $e) {
            echo json_encode(
                array(
                    'error' => true,
                    'msg'   => 'Something went wrong!'
                )
            );
        }
	}

?>