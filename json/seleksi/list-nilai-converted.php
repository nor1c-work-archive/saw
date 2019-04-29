<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $id         = (isset($_POST['id'])) ? trim($_POST['id']) : '';
    $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';

    try {
        $kriteria = $pdo->prepare('SELECT *
                                FROM kriteria
                                WHERE kyu_id = '.$kyu_id.'
                                AND periode_id = '.$periode.'
                                ORDER BY urutan_order ASC');
        $kriteria->execute();			
        $kriterias = $kriteria->fetchAll(PDO::FETCH_ASSOC);

        $matriks_x = array();
        $final_data = array();
        foreach($kriterias as $kkr => $vkr) {

            if($vkr['ada_pilihan'] == '0') {

                $pil_kriteria[$kkr] = $pdo->prepare('SELECT *
                                            FROM pilihan_kriteria
                                            WHERE id_kriteria = '.$vkr['id_kriteria'].'
                                            ORDER BY urutan_order ASC');
                $pil_kriteria[$kkr]->execute();			
                $pil_kriteria[$kkr] = $pil_kriteria[$kkr]->fetchAll(PDO::FETCH_ASSOC);

                foreach ($pil_kriteria[$kkr] as $pkey => $pval) {
                    
                    $nk = $pdo->prepare('SELECT kenshin.id_kenshin, nilai_kenshin.nilai, kenshin.nama_kenshin
                                            FROM nilai_kenshin
                                            LEFT JOIN kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                                            WHERE nilai_kenshin.id_kriteria = '.$vkr['id_kriteria'].'
                                            AND nilai_kenshin.kyu_id = '.$kyu_id.'
                                            AND nilai_kenshin.id_periode = '.$periode.'
                                            GROUP BY kenshin.id_kenshin');
                    $nk->execute();			
                    $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($nk as $nkey => $nval) {
                        
                        if(strpos($pval['nama'], '<=') !== false) {
                            if($nval['nilai'] <= str_replace('<=', '', $pval['nama'])) {
                                $final_data[$nkey]['id_kenshin'] = $nval['id_kenshin'];
                                $final_data[$nkey]['nama_kenshin'] = $nval['nama_kenshin'];
                                $final_data[$nkey]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                            }
                        }

                        if(strpos($pval['nama'], '-') !== false) {
                            list($first, $last) = explode('-', $pval['nama']);
                            $first = (float)$first-1;

                            $pval['nama'] = implode('-', array($first, $last));

                            if($nval['nilai'] >= explode('-', $pval['nama'])[0]) {
                                $final_data[$nkey]['id_kenshin'] = $nval['id_kenshin'];
                                $final_data[$nkey]['nama_kenshin'] = $nval['nama_kenshin'];
                                $final_data[$nkey]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                            }
                        }

                        if(strpos($pval['nama'], '>') !== false) {
                            if($nval['nilai'] > str_replace('>', '', $pval['nama'])) {
                                $final_data[$nkey]['id_kenshin'] = $nval['id_kenshin'];
                                $final_data[$nkey]['nama_kenshin'] = $nval['nama_kenshin'];
                                $final_data[$nkey]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                            }
                        }

                    }

                }
            } else {

                $nk = $pdo->prepare('SELECT kenshin.id_kenshin, nilai_kenshin.id_kriteria, nilai_kenshin.nilai, kenshin.nama_kenshin
                                            FROM nilai_kenshin
                                            LEFT JOIN kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                                            WHERE nilai_kenshin.id_kriteria = '.$vkr['id_kriteria'].'
                                            AND nilai_kenshin.kyu_id = '.$kyu_id.'
                                            AND nilai_kenshin.id_periode = '.$periode.'
                                            GROUP BY kenshin.id_kenshin');
                $nk->execute();			
                $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

                foreach ($nk as $key => $value) {
                    $final_data[$key]['id_kenshin'] = $value['id_kenshin'];
                    $final_data[$key]['nama_kenshin'] = $value['nama_kenshin'];
                    $final_data[$key]['nilai'][$value['id_kriteria']]['nilai'] = $value['nilai'];
                    
                }

            }
            
        }

        // if($query->rowCount() > 0) {
            
        //     exit(); // stop the process if not available
        // } else {
        //     echo json_encode(
        //         array(
        //             'error' => false,
        //             'msg' => 'Belum ada Data'
        //         )
        //     );
        // }
        echo json_encode($final_data);

    } catch (Exception $err) {
        echo json_encode(
                array(
                    'error' => true,
                    'msg' => 'Something went wrong!' . $err
                )
            );
    }

?>