<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $errors = array();
    $sukses = false;

    $id_kenshin     = (isset($_POST['id_kenshin'])) ? trim($_POST['id_kenshin']) : '';
    $tanggal        = (isset($_POST['tanggal'])) ? date('Y-m-d', strtotime(trim($_POST['tanggal']))) : '';
    $kyu_id         = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
    $periode        = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

    if($id_kenshin != '') {	
        
        try {
            $handle = $pdo->prepare('INSERT INTO absensi (id_kenshin, periode_id, kyu_id, tanggal) 
                                     VALUES (:id_kenshin, :periode, :kyu_id, :tanggal)');
            $handle->execute(array(
                'id_kenshin'    => $id_kenshin,
                'periode'       => $periode,
                'kyu_id'        => $kyu_id,
                'tanggal'       => $tanggal,
            ));

            echo json_encode(
                array(
                    'error' => false, 
                    'msg' => 'Peserta berhasil di absen!'
                )
            );
        } catch (Exception $err) {
            echo json_encode(
                array(
                    'error' => true, 
                    'msg' => 'Something went wrong!'
                )
            );
        }
    }
?>