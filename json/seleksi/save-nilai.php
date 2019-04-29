<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    if(isset($_POST)) {

        // $id_nilai   = (isset($_POST['id_nilai'])) ? trim($_POST['id_nilai']) : '';
        $id_kenshin = (isset($_POST['id_kenshin'])) ? trim($_POST['id_kenshin']) : '';
        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
        $kriteria   = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : '';
        $kyu        = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';

        try {
            if (!$_POST['is_edit']) {
                if(!empty($kriteria)) {
                    foreach($kriteria as $id_kriteria => $nilai) {
                        $handle = $pdo->prepare('INSERT INTO nilai_kenshin (id_kenshin, id_kriteria, id_periode, kyu_id, nilai) VALUES (:id_kenshin, :id_kriteria, :id_periode, :kyu_id, :nilai)');
                        $handle->execute( array(
                            'id_kenshin'    => $id_kenshin,
                            'id_kriteria'   => $id_kriteria,
                            'id_periode'    => $periode,
                            'kyu_id'        => $kyu,
                            'nilai'         => $nilai
                        ));
                    };
                };

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Nilai peserta telah ditambahkan!'
                    )
                );
            } else {
                //
                // print_r($_POST);
                if(!empty($kriteria)) {
                    foreach($kriteria as $id_kriteria => $nilai) {
                        $handle = $pdo->prepare('UPDATE nilai_kenshin 
                                                 SET nilai = :nilai
                                                 WHERE id_kenshin = :id_kenshin
                                                 AND id_kriteria = :id_kriteria
                                                 AND id_periode = :id_periode
                                                 AND kyu_id = :kyu_id');
                        $handle->execute( 
                            array(
                                'id_kenshin'    => $id_kenshin,
                                'id_kriteria'   => $id_kriteria,
                                'id_periode'    => $periode,
                                'kyu_id'        => $kyu,
                                'nilai'         => $nilai
                            )
                        );
                    };

                    echo json_encode(
                        array(
                            'error' => false, 
                            'msg' => 'Nilai peserta telah dirubah!'
                        )
                    );
                };
            }
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