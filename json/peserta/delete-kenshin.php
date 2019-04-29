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
                'msg'   => 'Data peserta tidak ditemukan!'
            )
        );
	} else {
		try {
            $kenshin = $pdo->prepare('DELETE FROM kenshin WHERE id_kenshin = :id_kenshin');				
			$kenshin->execute(array(
				'id_kenshin' => $id
            ));
            
            $nilai = $pdo->prepare('DELETE FROM nilai_kenshin WHERE id_kenshin = :id_kenshin');				
			$nilai->execute(array(
				'id_kenshin' => $id
			));

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'Data peserta berhasil dihapus!'
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