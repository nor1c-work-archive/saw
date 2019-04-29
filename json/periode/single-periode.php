<?php 

	require_once('../../includes/init.php');
	
	header('Content-Type: application/json');

	$ada_error = false;
	$result = '';

	$id_user = (isset($_GET['id'])) ? trim($_GET['id']) : '';

	try {
        $query = $pdo->prepare('SELECT id_periode, periode_title, start_date, end_date FROM periode WHERE id_periode = :id_periode');
        $query->execute(array('id_periode' => $id_user));
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if(empty($result)) {
            echo json_encode(array('message' => '404 Data Not Found!'));
        } else {
            echo json_encode($result);
        }
    } catch (Exception $e) {
        echo json_encode(
            array(
                'error' => true,
                'msg'   => 'Something went wrong!'
            )
        );
    }
	
?>