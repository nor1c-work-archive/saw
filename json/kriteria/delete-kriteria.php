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
                'msg'   => 'Kriteria tidak ditemukan!'
            )
        );
	} else {
		try {
            
            $kenshin = $pdo->prepare('DELETE FROM nilai_kenshin WHERE id_kriteria = :id_kriteria');				
            $kenshin->execute(array('id_kriteria' => $id));
            
            $variable = $pdo->prepare('DELETE FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria');
            $variable->execute(array('id_kriteria' => $id));

            $query = $pdo->prepare('DELETE FROM kriteria WHERE id_kriteria = :id_kriteria');
            $query->execute(array('id_kriteria' => $id));

            echo json_encode(
                array(
                    'error' => false,
                    'msg'   => 'Kriteria berhasil dihapus!'
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