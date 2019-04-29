<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    if(isset($_POST)) {

        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
        $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $hasil      = $_POST['hasil'];

        try {
            $nilai_kenshin = $pdo->prepare('SELECT * FROM nilai_kenshin
                                            WHERE id_periode = :id_periode AND kyu_id = :kyu_id');
            $nilai_kenshin->execute(
                array(
                    'id_periode'    => $periode,
                    'kyu_id'        => $kyu_id
                )
            );
            $nilai = $nilai_kenshin->fetchAll(PDO::FETCH_ASSOC);

            foreach ($hasil as $key => $value) {
                if($value['nilai'] >= 70) {
                    $kenshin = $pdo->prepare('UPDATE kenshin SET kyu_id = :kyu_updated 
                                              WHERE id_kenshin = :id_kenshin');
                    $kenshin->execute( 
                        array(
                            'id_kenshin'    => $value['id_kenshin'],
                            'kyu_updated'   => ($value['kyu_id'] != '' ? $value['kyu_id']+1 : $kyu_id)
                        )
                    );
                }

                $processed = $pdo->prepare('UPDATE nilai_kenshin SET is_processed = "1" 
                                            WHERE id_periode = :id_periode AND kyu_id = :kyu_id AND id_kenshin = :id_kenshin');
                $processed->execute( array(
                    'id_periode'    => $periode,
                    'kyu_id'        => $kyu_id,
                    'id_kenshin'    => $value['id_kenshin']
                ));
            }
            

            echo json_encode(
                array(
                    'error' => false, 
                    'msg' => 'Processed!'
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