<?php

// header('Content-type: application/json');
echo '<pre>';
require_once('../../includes/init.php');


$kyu_id = '1';
$periode = '10';

$kriteria = $pdo->prepare('SELECT *
                           FROM kriteria
                           WHERE kyu_id = '.$kyu_id.'
                           AND periode_id = '.$periode.'
                           ORDER BY urutan_order ASC');
$kriteria->execute();			
$kriteria = $kriteria->fetchAll(PDO::FETCH_ASSOC);

$matriks_x = array();
$list_kriteria = array();
foreach($kriteria as $kkr => $vkr) {

    if($vkr['ada_pilihan'] == '0') {

        $pil_kriteria[$kkr] = $pdo->prepare('SELECT *
                                       FROM pilihan_kriteria
                                       WHERE id_kriteria = '.$vkr['id_kriteria'].'
                                       ORDER BY urutan_order ASC');
        $pil_kriteria[$kkr]->execute();			
        $pil_kriteria[$kkr] = $pil_kriteria[$kkr]->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pil_kriteria[$kkr] as $pkey => $pval) {
            
            $nk = $pdo->prepare('SELECT nilai_kenshin.id_kenshin, nilai_kenshin.nilai, kenshin.nama_kenshin
                                 FROM nilai_kenshin
                                 LEFT JOIN kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                                 WHERE nilai_kenshin.id_kriteria = '.$vkr['id_kriteria'].'
                                 AND nilai_kenshin.kyu_id = '.$kyu_id.'
                                 AND nilai_kenshin.id_periode = '.$periode);
            $nk->execute();			
            $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

            foreach ($nk as $nkey => $nval) {
                
                if(strpos($pval['nama'], '<=') !== false) {
                    if($nval['nilai'] <= str_replace('<=', '', $pval['nama'])) {
                        // echo $nval['nilai'];
                        $matriks_x[$nval['id_kenshin']]['id_kenshin'] = $nval['id_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nama_kenshin'] = $nval['nama_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                    }
                }

                if(strpos($pval['nama'], '-') !== false) {
                    if($nval['nilai'] >= explode('-', trim($pval['nama']))[0] && $nval['nilai'] <= explode('-', trim($pval['nama']))[1]) {
                        // echo $nval['nilai'];
                        $matriks_x[$nval['id_kenshin']]['id_kenshin'] = $nval['id_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nama_kenshin'] = $nval['nama_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                    }
                }

                if(strpos($pval['nama'], '>') !== false) {
                    if($nval['nilai'] > str_replace('>', '', $pval['nama'])) {
                        // echo $nval['nilai'];
                        $matriks_x[$nval['id_kenshin']]['id_kenshin'] = $nval['id_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nama_kenshin'] = $nval['nama_kenshin'];
                        $matriks_x[$nval['id_kenshin']]['nilai'][$pval['id_kriteria']]['nilai'] = $pval['nilai'];
                    }
                }

            }

        }
    } else {

        $nk = $pdo->prepare('SELECT nilai_kenshin.id_kenshin, nilai_kenshin.nilai, nilai_kenshin.id_kriteria, kenshin.nama_kenshin
                             FROM nilai_kenshin
                             LEFT JOIN kenshin ON (kenshin.id_kenshin=nilai_kenshin.id_kenshin)
                             WHERE nilai_kenshin.id_kriteria = '.$vkr['id_kriteria'].'
                             AND nilai_kenshin.kyu_id = '.$kyu_id.'
                             AND nilai_kenshin.id_periode = '.$periode);
        $nk->execute();			
        $nk = $nk->fetchAll(PDO::FETCH_ASSOC);

        foreach ($nk as $key => $value) {
            
            $matriks_x[$value['id_kenshin']]['id_kenshin'] = $value['id_kenshin'];
            $matriks_x[$value['id_kenshin']]['nama_kenshin'] = $value['nama_kenshin'];
            $matriks_x[$value['id_kenshin']]['nilai'][$value['id_kriteria']]['nilai'] = $value['nilai'];
            
        }

    }
    
}

print_r($matriks_x);

?>