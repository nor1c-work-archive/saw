<?php

    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $id         = (isset($_POST['id'])) ? trim($_POST['id']) : '';
    $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';

    $kriteria = $pdo->prepare('SELECT *
                            FROM kriteria
                            WHERE kyu_id = '.$kyu_id.'
                            AND periode_id = '.$periode.'
                            ORDER BY urutan_order ASC');
    $kriteria->execute();			
    $kriterias = $kriteria->fetchAll(PDO::FETCH_ASSOC);

    $matriks_x = array();
    $list_kriteria = array();
    $kriteriaArr = array();
    foreach($kriterias as $kkr => $vkr) {

        $kriteriaArr[$vkr['id_kriteria']] = $vkr['nama'];

        if($vkr['ada_pilihan'] == '0') {

            $pil_kriteria[$kkr] = $pdo->prepare('SELECT *
                                        FROM pilihan_kriteria
                                        WHERE id_kriteria = '.$vkr['id_kriteria'].'
                                        ORDER BY urutan_order ASC');
            $pil_kriteria[$kkr]->execute();			
            $pil_kriteria[$kkr] = $pil_kriteria[$kkr]->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pil_kriteria[$kkr] as $pkey => $pval) {
                
                $nk = $pdo->prepare('SELECT id_kenshin, nilai
                                    FROM nilai_kenshin
                                    WHERE id_kriteria = '.$vkr['id_kriteria'].'
                                    AND kyu_id = '.$kyu_id.'
                                    AND id_periode = '.$periode);
                $nk->execute();			
                $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

                foreach ($nk as $nkey => $nval) {
                    
                    if(strpos($pval['nama'], '<=') !== false) {
                        if($nval['nilai'] <= str_replace('<=', '', $pval['nama'])) {
                            // echo $nval['nilai'];
                            $matriks_x[$pval['id_kriteria']][$nval['id_kenshin']] = $pval['nilai'];
                            $list_kriteria[$pval['id_kriteria']]['id_kriteria'] = $vkr['id_kriteria'];
                            $list_kriteria[$pval['id_kriteria']]['nama'] = $vkr['nama'];
                            $list_kriteria[$pval['id_kriteria']]['type'] = $vkr['type'];
                            $list_kriteria[$pval['id_kriteria']]['bobot'] = $vkr['bobot'];
                        }
                    }

                    if(strpos($pval['nama'], '-') !== false) {
                        list($first, $last) = explode('-', $pval['nama']);
                        $first = (float)$first-1;

                        $pval['nama'] = implode('-', array($first, $last));

                        if($nval['nilai'] >= explode('-', $pval['nama'])[0]) {
                            $matriks_x[$pval['id_kriteria']][$nval['id_kenshin']] = $pval['nilai'];
                            $list_kriteria[$pval['id_kriteria']]['id_kriteria'] = $vkr['id_kriteria'];
                            $list_kriteria[$pval['id_kriteria']]['nama'] = $vkr['nama'];
                            $list_kriteria[$pval['id_kriteria']]['type'] = $vkr['type'];
                            $list_kriteria[$pval['id_kriteria']]['bobot'] = $vkr['bobot'];
                        }
                    }

                    if(strpos($pval['nama'], '>') !== false) {
                        if($nval['nilai'] > str_replace('>', '', $pval['nama'])) {
                            // echo $nval['nilai'];
                            $matriks_x[$pval['id_kriteria']][$nval['id_kenshin']] = $pval['nilai'];
                            $list_kriteria[$pval['id_kriteria']]['id_kriteria'] = $vkr['id_kriteria'];
                            $list_kriteria[$pval['id_kriteria']]['nama'] = $vkr['nama'];
                            $list_kriteria[$pval['id_kriteria']]['type'] = $vkr['type'];
                            $list_kriteria[$pval['id_kriteria']]['bobot'] = $vkr['bobot'];
                        }
                    }

                }

            }
        } else {

            $nk = $pdo->prepare('SELECT id_kenshin, id_kriteria, nilai
                                FROM nilai_kenshin
                                WHERE id_kriteria = '.$vkr['id_kriteria'].'
                                AND kyu_id = '.$kyu_id.'
                                AND id_periode = '.$periode);
            $nk->execute();			
            $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

            foreach ($nk as $key => $value) {
                $matriks_x[$value['id_kriteria']][$value['id_kenshin']] = $value['nilai'];
                $list_kriteria[$value['id_kriteria']]['id_kriteria'] = $vkr['id_kriteria'];
                $list_kriteria[$value['id_kriteria']]['nama'] = $vkr['nama'];
                $list_kriteria[$value['id_kriteria']]['type'] = $vkr['type'];
                $list_kriteria[$value['id_kriteria']]['bobot'] = $vkr['bobot'];
                
            }

        }
        
    }

    /* >>> STEP 3 ===================================
    * Matriks Ternormalisasi (R)
    * ------------------------------------------- */

    $nil = array();
    foreach ($matriks_x as $key => $value) {
        foreach($value as $key_n => $val[$key]) {
            $nil[$key][$key_n] = $val[$key];
        }
    }

    $max = array();
    $no = 1;
    foreach ($nil as $key => $value) {
        $max[$no] = max($value);
        $no++;
    }

    $matriks_r = array();
    $no = 1;

    foreach($matriks_x as $id_kriteria => $nilai_kenshins) {

        $tipe = $list_kriteria[$id_kriteria]['type'];
        foreach($nilai_kenshins as $id_alternatif => $nilai) {

            // echo $nilai . "<br>";
            if ($tipe == 'c1') {
                $nilai_normal = $nilai / max($nilai_kenshins);
            } else {
                $nilai_normal = $nilai / $max[$no];
            }
            
            $matriks_r[$id_kriteria][$id_alternatif] = $nilai_normal;
        }    
        $no++;
    }

    $nilai_x = array();
    foreach ($kriterias as $vkey => $vvalue) {
        foreach ($matriks_r as $key => $value) {
            $nilai_x[$key] = $value;
        }
    }

    $terendah = array();
    foreach ($nilai_x as $key => $value) {
        $mean[$key] = array_sum($value)/count($value);
        if(min($value) < 0.7) {
            $min[$key] = min($value);
        } else {
            $min[$key] = 0;
        }

        foreach ($value as $vkey => $vvalue) {
            if($vvalue < 0.7) {
                $terendah[$vkey][] = $kriteriaArr[$key];
            }
        }
    }

    // print_r($terendah);


    /* >>> STEP 4 ================================
    * Perangkingan
    * ------------------------------------------- */

    $final_data = array();
    $digit      = 4;

    $query2 = $pdo->prepare('SELECT kenshin.id_kenshin, kenshin.nama_kenshin, nilai_kenshin.kyu_id, nilai_kenshin.is_processed
                            FROM nilai_kenshin 
                            LEFT JOIN kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                            WHERE nilai_kenshin.kyu_id = ' . $kyu_id . ' 
                            AND nilai_kenshin.id_periode = ' . $periode . '
                            GROUP BY nilai_kenshin.id_kenshin');
    $query2->execute();			
    $query2->setFetchMode(PDO::FETCH_ASSOC);
    $kenshins = $query2->fetchAll();

    $ranks = array();
    foreach($kenshins as $kenshin) {

        $total_nilai = 0;
        foreach($list_kriteria as $kriteria) {
        
            $bobot = $kriteria['bobot'];
            $id_kenshin = $kenshin['id_kenshin'];
            $id_kriteria = $kriteria['id_kriteria'];
            
            $nilai_r = $matriks_r[$id_kriteria][$id_kenshin];
            $total_nilai = $total_nilai + ($bobot * $nilai_r);

            // if($kenshin['id_kenshin'] == '7') {
            //     echo $bobot * $nilai_r . "<br>";
            // }

        }

        $ranks[$kenshin['id_kenshin']]['id_kenshin']    = $kenshin['id_kenshin'];
        $ranks[$kenshin['id_kenshin']]['nama_kenshin']  = $kenshin['nama_kenshin'];
        $ranks[$kenshin['id_kenshin']]['kyu_id']        = $kenshin['kyu_id'];
        $ranks[$kenshin['id_kenshin']]['is_processed']  = $kenshin['is_processed'];
        $ranks[$kenshin['id_kenshin']]['nilai']         = $total_nilai;

        foreach ($terendah as $tkey => $tvalue) {
            if($tkey == $kenshin['id_kenshin']) {
                $ranks[$kenshin['id_kenshin']]['terendah'] = $tvalue;
            }
        }
        
    }

    $sorted_ranks = $ranks;		
    
    $nama_kenshin = array();
	$nilai = array();
	foreach ($sorted_ranks as $key => $row) {
		$nama_kenshin[$key] = $row['nama_kenshin'];
		$nilai[$key]        = $row['nilai'];
    }
    
    array_multisort($nilai, SORT_DESC, $nama_kenshin, SORT_ASC, $sorted_ranks);

    echo json_encode($sorted_ranks);

?>