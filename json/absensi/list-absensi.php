<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
	    $kyu_id     = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';
        $periode    = (isset($_POST['periode'])) ? trim($_POST['periode']) : '';
        $searchType     = (isset($_POST['searchType'])) ? trim($_POST['searchType']) : '';
        $searchKeyword  = (isset($_POST['searchKeyword'])) ? trim($_POST['searchKeyword']) : '';

        $offset     = $_POST['offset'];
        $start      = $_POST['start'];

        // get full date of the month
        $month = date('m', time());
        $year = date('Y', time());

        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        for($i=$start_time; $i<$end_time; $i+=86400) {
            $dates[] = date('d', $i);
        }
        // end of getting full date of the month

        $sql = "SELECT absensi.*, kenshin.nama_kenshin 
                FROM absensi
                LEFT JOIN kyu ON (kyu.id_kyu=absensi.kyu_id)
                LEFT JOIN kenshin ON (absensi.id_kenshin=kenshin.id_kenshin)
                WHERE absensi.kyu_id = ".$kyu_id."
                AND absensi.periode_id = ".$periode;

        if($searchKeyword != '') {
            $sql .= " AND kenshin." . $searchType . " LIKE '%$searchKeyword%' ";
        }

        $sql .= " GROUP BY absensi.id_kenshin ";

        $query = $pdo->prepare($sql);
        $query->execute();
        $query->setFetchMode();
        $kenshin = $query->fetchAll(PDO::FETCH_ASSOC);

        $attendance = array();
        foreach ($kenshin as $key => $value) {
            $attendance[$value['id_kenshin']]['nama']       = $value['nama_kenshin'];
            $attendance[$value['id_kenshin']]['id_kenshin'] = $value['id_kenshin'];

            $tanggal[$key] = $pdo->prepare('SELECT absensi.tanggal
                                FROM absensi
                                LEFT JOIN kyu ON (kyu.id_kyu=absensi.kyu_id)
                                LEFT JOIN kenshin ON (absensi.id_kenshin=kenshin.id_kenshin)
                                WHERE absensi.kyu_id = '.$kyu_id.'
                                AND absensi.periode_id = '.$periode.'
                                AND absensi.id_kenshin = '.$value['id_kenshin']);
            $tanggal[$key]->execute();
            $tanggal[$key]->setFetchMode();
            $tanggal[$key] = $tanggal[$key]->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tanggal[$key] as $tkey => $tvalue) {
                $attendance[$value['id_kenshin']]['tanggal'][] = date('Y-m-d', strtotime($tvalue['tanggal']));
            }
        }

        echo json_encode($attendance);


    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>