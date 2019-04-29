<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    // check for username availibity
    $kyu_id         = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
    $searchType     = (isset($_POST['searchType'])) ? trim($_POST['searchType']) : '';
    $searchKeyword  = (isset($_POST['searchKeyword'])) ? trim($_POST['searchKeyword']) : '';

    try {
        $sql = "SELECT id_kenshin, username, nim, nik, nama_kenshin, jurusan, email, alamat, hp, kyu.kyu_description as tingkatan
                FROM kenshin
                LEFT JOIN kyu ON (kenshin.kyu_id=kyu.id_kyu)";
        if($kyu_id != '') {
            $sql .= " WHERE kyu_id = :kyu_id ";
            if($searchKeyword != '') {
                $sql .= " AND kenshin." . $searchType . " LIKE '%$searchKeyword%' ";
            }
        } else {
            if($searchKeyword != '') {
                $sql .= " WHERE kenshin." . $searchType . " LIKE '%$searchKeyword%' ";
            }
        }

        // echo $sql;

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