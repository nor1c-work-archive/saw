<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    if(isset($_POST)) {

        $id_kriteria = (isset($_POST['id_kriteria'])) ? trim($_POST['id_kriteria']) : '';
        $nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
        $type = (isset($_POST['type'])) ? trim($_POST['type']) : '';
        $bobot = (isset($_POST['bobot'])) ? trim($_POST['bobot']) : '';
        $jenis_nilai = (isset($_POST['cara_penilaian'])) ? trim($_POST['cara_penilaian']) : 0;
        $pilihan = (isset($_POST['variable'])) ? $_POST['variable'] : '';
        $urutan_order = (isset($_POST['urutan_order'])) ? trim($_POST['urutan_order']) : '0';
        $kyu_id = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $periode = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

        try {
            if (!$_POST['is_edit']) {
                $handle = $pdo->prepare('INSERT INTO kriteria (nama, type, bobot, urutan_order, ada_pilihan, kyu_id, periode_id) VALUES (:nama, :type, :bobot, :urutan_order, :jenis_nilai, :kyu_id, :periode)');
                $handle->execute( 
                    array(
                        'nama' => $nama,
                        'type' => $type,
                        'bobot' => $bobot,
                        'urutan_order' => $urutan_order,
                        'jenis_nilai' => $jenis_nilai,			
                        'kyu_id' => $kyu_id,			
                        'periode' => $periode,			
                    )
                );
                $id_kriteria = $pdo->lastInsertId();
                
                if($id_kriteria && !empty($pilihan)) {
                    foreach($pilihan as $pil) {
                        $nama = (isset($pil['nama'])) ? trim($pil['nama']) : '';
                        $nilai = (isset($pil['nilai'])) ? floatval($pil['nilai']) : '';
                        $urutan_order = (isset($pil['urutan_order']) && $pil['urutan_order']) ? (int) trim($pil['urutan_order']) : 0;
                        
                        if($nama != '' && ($nilai >= 0)) {
                            $prepare_query = 'INSERT INTO pilihan_kriteria (nama, id_kriteria, nilai, urutan_order) VALUES  (:nama, :id_kriteria, :nilai, :urutan_order)';
                            $data = array(
                                'nama' => $nama,
                                'id_kriteria' => $id_kriteria,
                                'nilai' => $nilai,
                                'urutan_order' => $urutan_order
                            );		
                            $handle = $pdo->prepare($prepare_query);		
                            $sukses = $handle->execute($data);				
                        }
                    } 
                }

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Kriteria telah dibuat!'
                    )
                );
            } else {
                // 
                $prepare_query = 'UPDATE kriteria SET nama = :nama, type = :type, bobot = :bobot, urutan_order = :urutan_order, ada_pilihan = :jenis_nilai WHERE id_kriteria = :id_kriteria';
                $data = array(
                    'nama' => $nama,
                    'type' => $type,
                    'bobot' => $bobot,
                    'urutan_order' => $urutan_order,
                    'id_kriteria' => $id_kriteria,
                    'jenis_nilai' => $jenis_nilai
                );		
                $handle = $pdo->prepare($prepare_query);		
                $sukses = $handle->execute($data);
                
                
                // Simpan Pilihan Kriteria / Variabel
                $ids_pilihan = array();
                if(!empty($pilihan)): foreach($pilihan as $pil):
                    
                    $id_pil_kriteria = (isset($pil['id'])) ? trim($pil['id']) : '';
                    $nama = (isset($pil['nama'])) ? trim($pil['nama']) : '';
                    $nilai = (isset($pil['nilai'])) ? floatval(trim($pil['nilai'])) : '';
                    $urutan_order = (isset($pil['urutan_order']) && $pil['urutan_order']) ? (int) trim($pil['urutan_order']) : 0;
                    
                    if($id_pil_kriteria && $nama != '' && ($nilai >= 0)):				
                        // Update jika pilihan telah ada di database				
                        $prepare_query = 'UPDATE pilihan_kriteria SET nama = :nama, id_kriteria = :id_kriteria, nilai = :nilai, urutan_order = :urutan_order WHERE id_pil_kriteria = :id_pil_kriteria';
                        $data = array(
                            'nama' => $nama,
                            'id_kriteria' => $id_kriteria,
                            'nilai' => $nilai,
                            'urutan_order' => $urutan_order,
                            'id_pil_kriteria' => $id_pil_kriteria		
                        );		
                        $handle = $pdo->prepare($prepare_query);		
                        $sukses = $handle->execute($data);
                        if($sukses):
                            $ids_pilihan[] = $id_pil_kriteria;
                        endif;					
                        
                    elseif(($nama != '') && ($nilai >= 0)):
                        // Insert jika pilihan belum ada di database
                        $prepare_query = 'INSERT INTO pilihan_kriteria (nama, id_kriteria, nilai, urutan_order) VALUES  (:nama, :id_kriteria, :nilai, :urutan_order)';
                        $data = array(
                            'nama' => $nama,
                            'id_kriteria' => $id_kriteria,
                            'nilai' => $nilai,
                            'urutan_order' => $urutan_order	
                        );		
                        $handle = $pdo->prepare($prepare_query);		
                        $sukses = $handle->execute($data);				
                        if($sukses):
                            $last_id = $pdo->lastInsertId();
                            $ids_pilihan[] = $last_id;
                        endif;
                        
                    endif;
                    
                endforeach; endif; // end if(!empty($pilihan))
                    
                // Bersihkan pilihan
                if(!empty($ids_pilihan)):
                    $not_in = implode(',', $ids_pilihan);
                    $prepare_query = 'DELETE FROM pilihan_kriteria WHERE id_pil_kriteria NOT IN ('.$not_in.') AND id_kriteria = ' . $id_kriteria;
                    $handle = $pdo->prepare($prepare_query);	
                    $handle->execute();
                else:
                    $prepare_query = 'DELETE FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria';
                    $handle = $pdo->prepare($prepare_query);	
                    $handle->execute(array('id_kriteria' => $id_kriteria));
                endif;

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Data kriteria telah dirubah!'
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