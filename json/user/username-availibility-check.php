<?php
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    // check for username availibity
    if(!$_POST['is_edit']) {
        $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';

        $query = $pdo->prepare('SELECT username FROM user WHERE user.username = :username');
        $query->execute(array('username' => $username));
        $result = $query->fetch();
        if(!empty($result)) {
            echo json_encode(
                array(
                    'error' => true,
                    'msg' => 'Username sudah terpakai!'
                )
            );
            exit(); // stop the process if not available
        } else {
            echo json_encode(
                array(
                    'error' => false,
                    'msg' => 'Username tersedia!'
                )
            );
        }
    } else {
        echo json_encode(
            array(
                'error' => false
            )
        );
    }
?>