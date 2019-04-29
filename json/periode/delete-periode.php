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
                'msg'   => 'Periode tidak ditemukan!'
            )
        );
	} else {
		try {
            $query = $pdo->prepare('DELETE FROM periode WHERE id_periode = :id_periode');
            $query->execute(array('id_periode' => $id));

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'Periode berhasil dihapus!'
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