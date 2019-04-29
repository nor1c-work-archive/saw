<?php 

    require_once('../../includes/init.php');
    
    header('Content-Type: application/json');

    $errors = array();
    $sukses = false;

    $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
    $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
    $password2 = (isset($_POST['confirmation_password'])) ? trim($_POST['confirmation_password']) : '';
    $nama = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
    $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
    $role = (isset($_POST['role'])) ? trim($_POST['role']) : '';

    if($_POST['submit']):		
        
        // Validasi Username
        if(!$username) {
            $errors[] = 'Username tidak boleh kosong';
        }		
        // Validasi Password
        if(!$password) {
            $errors[] = 'Password tidak boleh kosong';
        }		
        // Validasi Password 2
        if($password != $password2) {
            $errors[] = 'Password harus sama keduanya';
        }		
        // Validasi Nama
        if(!$nama) {
            $errors[] = 'Nama tidak boleh kosong';
        }		
        // Validasi Email
        if(!$email) {
            $errors[] = 'Email tidak boleh kosong';
        }
        // Validasi role
        if(!$role) {
            $errors[] = 'Role tidak boleh kosong';
        }
        
        // Cek Username
        if($username) {
            $query = $pdo->prepare('SELECT username FROM user WHERE user.username = :username');
            $query->execute(array('username' => $username));
            $result = $query->fetch();
            if(!empty($result)) {
                $errors[] = 'Username sudah digunakan';
            }
        }

        // Jika lolos validasi lakukan hal di bawah ini
        if(empty($errors)):
            $handle = $pdo->prepare('INSERT INTO user (username, password, nama, email, role) VALUES (:username, :password, :nama, :email, :role)');
            $handle->execute( array(
                'username' => $username,
                'password' => sha1($password),
                'nama' => $nama,
                'email' => $email,
                'role' => $role
            ) );
            $sukses = "<strong>{$username}</strong> berhasil dimasukkan.";

            if($sukses) {
                echo json_encode(array('error' => false));
            } else {
                echo json_encode(array('error' => true));
            }
        
        endif;

    endif;
?>