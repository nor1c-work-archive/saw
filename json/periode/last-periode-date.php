<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
        $query = $pdo->prepare('SELECT end_date
                                FROM periode
                                ORDER BY end_date DESC 
                                LIMIT 1');			
        $query->execute();
        $query->setFetchMode();
        
        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(
            array(
                'y' => date('Y', strtotime($data[0]['end_date'])),
                'm' => date('m', strtotime($data[0]['end_date'])),
                'd' => date('d', strtotime($data[0]['end_date']))
            )
        );
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>