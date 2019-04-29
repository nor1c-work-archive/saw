<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $errors = array();
    $sukses = false;

	$id         = (isset($_POST['id'])) ? trim($_POST['id']) : '';
	$kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
	$periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

	if(!$id) {
        echo json_encode(
            array(
                'error' => false,
                'msg'   => 'Nilai tidak ditemukan!'
            )
        );
	} else {
		try {
            
            $kenshin = $pdo->prepare('DELETE FROM nilai_kenshin WHERE id_kenshin = :id_kenshin AND kyu_id = :kyu_id AND id_periode = :id_periode');				
            $kenshin->execute(
                array(
                    'id_kenshin' => $id,
                    'kyu_id'     => $kyu_id,
                    'id_periode' => $periode
                )
            );

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'Nilai berhasil dihapus!'
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