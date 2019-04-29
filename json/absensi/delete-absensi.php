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
                'msg'   => 'Data absen tidak ditemukan!'
            )
        );
	} else {
		try {
            
            $kenshin = $pdo->prepare('DELETE FROM absensi WHERE id_absensi = :id_absensi');				
            $kenshin->execute(array('id_absensi' => $id));

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'Data absen berhasil dihapus!'
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