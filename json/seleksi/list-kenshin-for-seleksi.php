<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    // check for username availibity
    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
    $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';

    try {
        $sql = "SELECT kenshin.id_kenshin, username, nim, nik, nama_kenshin, jurusan, email, alamat, hp, kyu.kyu_description as tingkatan
                FROM kenshin
                LEFT JOIN kyu ON (kenshin.kyu_id=kyu.id_kyu)";

        if($kyu_id != '') {
            $sql .= " WHERE kenshin.kyu_id = :kyu_id 
                      GROUP BY kenshin.id_kenshin ";
        }

        $query = $pdo->prepare($sql);
        
        $query->execute(array('kyu_id' => $kyu_id));
        $query->setFetchMode(PDO::FETCH_ASSOC);
        
        if($query->rowCount() > 0) {
            echo json_encode($query->fetchAll());
            exit(); // stop the process if not available
        } else {
            echo json_encode(
                array(
                    'error' => false,
                    'msg' => 'Belum ada Data!'
                )
            );
        }
    } catch(Exception $err) {
        echo json_encode(
            array(
                'error' => true,
                'msg' => 'Something went wrong! Error: ' . $err
            )
        );
    }
?>