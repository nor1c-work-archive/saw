<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $errors = array();
    $sukses = false;

    $id_user = (isset($_POST['id_user'])) ? trim($_POST['id_user']) : '';
    $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
    $password2 = (isset($_POST['confirmation_password'])) ? trim($_POST['confirmation_password']) : '';
    $nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
    $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
    $role = (isset($_POST['role'])) ? trim($_POST['role']) : '';

    if($_POST['submit']):		
        
        try {
            if ($id_user == '') {
                $handle = $pdo->prepare('INSERT INTO user (username, password, nama, email, role) 
                                     VALUES (:username, :password, :nama, :email, :role)');
                $handle->execute(array(
                    'username' => $username,
                    'password' => sha1($password),
                    'nama' => $nama,
                    'email' => $email,
                    'role' => $role
                ));

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'User telah dibuat!'
                    )
                );
            } else {
                $prepare_query = 'UPDATE user SET nama = :nama, email = :email, role = :role WHERE id_user = :id_user';
                $data = array(
                    'nama' => $nama,
                    'email' => $email,
                    'role' => $role,
                    'id_user' => $id_user
                );

                if($password != '') {
                    $prepare_query = 'UPDATE user SET nama = :nama, email = :email, role = :role, password = :password WHERE id_user = :id_user';
                    $data['password'] = sha1($password);
                }		

                $handle = $pdo->prepare($prepare_query);		
                $sukses = $handle->execute($data);

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Data user berhasil dirubah!'
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

    endif;
?>