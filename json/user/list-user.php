<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    // check for username availibity
    $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';

    $query = $pdo->prepare('SELECT id_user, username, nama, role FROM user');			
	$query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    
    if($query->rowCount() > 0) {
        echo json_encode($query->fetchAll());
        exit(); // stop the process if not available
    } else {
        echo json_encode(
            array(
                'error' => true,
                'msg' => 'Something went wrong!'
            )
        );
    }
?>