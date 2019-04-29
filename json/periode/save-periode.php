<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    if(isset($_POST)) {

        $id_periode     = (isset($_POST['id_periode'])) ? trim($_POST['id_periode']) : '';
        $periode_title  = (isset($_POST['periode_title'])) ? trim($_POST['periode_title']) : '';
        $start_date     = (isset($_POST['start_date'])) ? trim($_POST['start_date']) : '';
        $end_date       = (isset($_POST['end_date'])) ? trim($_POST['end_date']) : '';
        $past_periode   = (isset($_POST['past_periode'])) ? trim($_POST['past_periode']) : '';
        
        try {
            if (!$_POST['is_edit']) {

                $next_periode = $pdo->prepare('SELECT AUTO_INCREMENT as id
                                                FROM information_schema.TABLES
                                                WHERE TABLE_SCHEMA = "ricky"
                                                AND TABLE_NAME = "periode"');
                $next_periode->execute();
                $next_periode_id = $next_periode->fetch()['id'];

                $handle = $pdo->prepare('INSERT INTO periode (periode_title, start_date, end_date) VALUES (:periode_title, :start_date, :end_date)');
                $handle->execute( 
                    array(
                        'periode_title' => $periode_title,
                        'start_date'    => date('Y-m-d', strtotime($start_date)),
                        'end_date'      => date('Y-m-d', strtotime($end_date))	
                    )
                );

                if($past_periode) {
                    $copy = $pdo->prepare('SELECT kriteria.*, pilihan_kriteria.id_kriteria as id_kriteria_pil FROM kriteria
                                           LEFT JOIN pilihan_kriteria ON (pilihan_kriteria.id_kriteria = kriteria.id_kriteria)
                                           WHERE kriteria.periode_id = ' . $past_periode . ' GROUP BY kriteria.id_kriteria');
                    $copy->execute();
                    $data = $copy->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($data as $key => $value) {
                        $run_copy = $pdo->prepare('INSERT INTO kriteria (nama, type, bobot, ada_pilihan, urutan_order, kyu_id, periode_id) 
                                                   SELECT nama, type, bobot, ada_pilihan, urutan_order, kyu_id, ' . $next_periode_id . '
                                                   FROM kriteria 
                                                   WHERE id_kriteria = ' . $value['id_kriteria']);
                        $run_copy->execute();
                        $last_insert_kriteria = $pdo->lastInsertId();

                        if($run_copy) {
                            $kr_param = $pdo->prepare('SELECT * FROM pilihan_kriteria
                                                       WHERE id_kriteria = ' . $value['id_kriteria']);
                            $kr_param->execute();
                            $res_param = $kr_param->fetchAll(PDO::FETCH_ASSOC);

                            if(count($res_param) > 0) {
                                $run_copy_param = $pdo->prepare('INSERT INTO pilihan_kriteria (id_kriteria, nama, nilai, urutan_order) 
                                                                 SELECT ' . $last_insert_kriteria . ', nama, nilai, urutan_order
                                                                 FROM pilihan_kriteria 
                                                                 WHERE id_kriteria = ' . $value['id_kriteria']);
                                $run_copy_param->execute();
                            }
                        }
                    }
                }

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Periode telah dibuat!'
                    )
                );
            } else {
                // 
                $prepare_query = 'UPDATE periode SET periode_title = :periode_title, start_date = :start_date, end_date = :end_date WHERE id_periode = :id_periode';
                $data = array(
                    'id_periode'    => $id_periode,
                    'periode_title' => $periode_title,
                    'start_date'    => date('Y-m-d', strtotime($start_date)),
                    'end_date'      => date('Y-m-d', strtotime($end_date))
                );		
                $handle = $pdo->prepare($prepare_query);		
                $handle->execute($data);
                
                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Data periode telah dirubah!'
                    )
                );
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