<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $id         = (isset($_POST['id'])) ? trim($_POST['id']) : '';
    $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
    $searchType     = (isset($_POST['searchType'])) ? trim($_POST['searchType']) : '';
    $searchKeyword  = (isset($_POST['searchKeyword'])) ? trim($_POST['searchKeyword']) : '';

    try {
        $sql   = "SELECT kenshin.id_kenshin, kenshin.nama_kenshin
                  FROM kenshin
                  LEFT JOIN nilai_kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                  LEFT JOIN kriteria ON (kriteria.id_kriteria=nilai_kenshin.id_kriteria)
                  LEFT JOIN periode ON (periode.id_periode=nilai_kenshin.id_periode)
                  WHERE nilai_kenshin.kyu_id = " . $kyu_id . " 
                  AND nilai_kenshin.id_periode = " . $periode;
        if ($id != '') {
            $sql .= " AND kenshin.id_kenshin = " . $id;
        }

        if($searchKeyword != '') {
            $sql .= " AND kenshin." . $searchType . " LIKE '%$searchKeyword%' ";
        }

        $sql   .= " GROUP BY kenshin.id_kenshin ";
        $query = $pdo->prepare($sql);

        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $querys = $pdo->prepare('SELECT id_kriteria, nama, type, bobot, ada_pilihan
                                FROM kriteria
                                WHERE kyu_id = '.$kyu_id.'
                                ORDER BY urutan_order ASC');
        $querys->execute();			
        $kriterias = $querys->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

        
        $query2 = $pdo->prepare('SELECT kenshin.id_kenshin, kenshin.nama_kenshin 
                                 FROM kenshin 
                                 LEFT JOIN nilai_kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                                 WHERE nilai_kenshin.kyu_id = ' . $kyu_id);
        $query2->execute();			
        $query2->setFetchMode(PDO::FETCH_ASSOC);
        $kenshins = $query2->fetchAll();
                
        $final_data = [];
        foreach($query->fetchAll() as $mskey => $data) {
            
            $sql = "SELECT id_kriteria, nilai 
                    FROM nilai_kenshin 
                    WHERE id_kenshin = :id_kenshin 
                    AND id_periode = :id_periode
                    AND kyu_id = :kyu_id
                    GROUP BY nilai_kenshin.id_nilai_kenshin";
            $query3 = $pdo->prepare($sql);
            $query3->execute(array(
                'id_kenshin' => $data['id_kenshin'],
                'id_periode' => $periode,
                'kyu_id'     => $kyu_id
            ));			
            $query3->setFetchMode(PDO::FETCH_ASSOC);
            
            $nilais[$mskey] = $query3->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);

            foreach ($nilais[$mskey] as $key => $value) {
                $pilihan[$key] = $pdo->prepare('SELECT *
                                                FROM kriteria 
                                                WHERE id_kriteria = ' . $key);
                $pilihan[$key]->execute();			
                $pilihan[$key]->setFetchMode(PDO::FETCH_ASSOC);
                $pilihan[$key] = $pilihan[$key]->fetchAll();

                foreach ($pilihan[$key] as $pkey => $pvalue) {
                    if($pvalue['ada_pilihan'] == 1) {
                        $pil[$pkey] = $pdo->prepare('SELECT id_pil_kriteria, nama, nilai FROM pilihan_kriteria WHERE id_kriteria = ' . $pvalue['id_kriteria']);
                        $pil[$pkey]->execute();			
                        $pil[$pkey]->setFetchMode(PDO::FETCH_ASSOC);
                        $pil[$pkey] = $pil[$pkey]->fetchAll();

                        foreach ($pil[$pkey] as $pkey => $pvalue) {
                            if($value['nilai'] == $pvalue['nilai']) {
                                $nilais[$mskey][$key]['nilai'] = $pvalue['nama'];
                            }
                        }
                    }
                }
            }

            // print_r($nilais[$mskey]);
            
            // foreach($kriterias as $id_kriteria => $values) {
            //     if(isset($nilais[$mskey][$id_kriteria])) {
            //         $nilais[$mskey][$id_kriteria]['nilai'];
            //         $kriterias[$id_kriteria]['nilai'][$data['id_kenshin']] = $nilais[$mskey][$id_kriteria]['nilai'];
            //     } else {
            //         $nilais[$mskey][$id_kriteria] = 0;
            //         $kriterias[$id_kriteria]['nilai'][$data['id_kenshin']] = 0;
            //     }
                
            //     // if(isset($kriterias[$id_kriteria]['tn_kuadrat'])){
            //     //     $kriterias[$id_kriteria]['tn_kuadrat'] += pow($kriterias[$id_kriteria]['nilai'][$data['id_kenshin']], 2);
            //     // } else {
            //     //     $kriterias[$id_kriteria]['tn_kuadrat'] = pow($kriterias[$id_kriteria]['nilai'][$data['id_kenshin']], 2);
            //     // }
            // }

            $final_data[$mskey]['id_kenshin']    = $data['id_kenshin'];
            $final_data[$mskey]['nama_kenshin']  = $data['nama_kenshin'];
            $final_data[$mskey]['nilai']         = $nilais[$mskey];
        }

        if($query->rowCount() > 0) {
            echo json_encode($final_data);
            exit(); // stop the process if not available
        } else {
            echo json_encode(
                array(
                    'error' => false,
                    'msg' => 'Belum ada Data'
                )
            );
        }
    } catch (Exception $err) {
        echo json_encode(
                array(
                    'error' => true,
                    'msg' => 'Something went wrong!' . $err
                )
            );
    }

?>