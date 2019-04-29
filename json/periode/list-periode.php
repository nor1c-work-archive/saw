<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    try {
        $query = $pdo->prepare('SELECT * 
							FROM periode
							ORDER BY end_date DESC');			
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        
        echo json_encode($query->fetchAll());
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong! ' . $e
            )
        );
    }
    
?>