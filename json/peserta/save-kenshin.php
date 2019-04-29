<?php 
    header('Content-type: application/json');
    require_once('../../includes/init.php');
    

    $errors = array();
    $sukses = false;

    $id_kenshin     = (isset($_POST['id_kenshin'])) ? trim($_POST['id_kenshin']) : '';
    $username       = (isset($_POST['username'])) ? trim($_POST['username']) : '';
    $password       = (isset($_POST['password'])) ? trim($_POST['password']) : '';
    $password2      = (isset($_POST['confirmation_password'])) ? trim($_POST['confirmation_password']) : '';
    $nama           = (isset($_POST['nama'])) ? trim($_POST['nama']) : '';
    $nim            = (isset($_POST['nim'])) ? trim($_POST['nim']) : '';
    $nik            = (isset($_POST['nik'])) ? trim($_POST['nik']) : '';
    $jurusan        = (isset($_POST['jurusan'])) ? trim($_POST['jurusan']) : '';
    $tingkatan      = (isset($_POST['tingkatan'])) ? trim($_POST['tingkatan']) : '';
    $hp             = (isset($_POST['hp'])) ? trim($_POST['hp']) : '';
    $email          = (isset($_POST['email'])) ? trim($_POST['email']) : '';
    $alamat         = (isset($_POST['alamat'])) ? trim($_POST['alamat']) : '';

    if($_POST['submit']) {	
        
        try {
            if ($id_kenshin == '') {
                $handle = $pdo->prepare('INSERT INTO kenshin (username, password, nama_kenshin, nim, nik, jurusan, kyu_id, hp, email, alamat) 
                                         VALUES (:username, :password, :nama, :nim, :nik, :jurusan, :tingkatan, :hp, :email, :alamat)');
                $handle->execute(array(
                    'username'  => $username,
                    'password'  => sha1($password),
                    'nama'      => $nama,
                    'nim'       => $nim,
                    'nik'       => $nik,
                    'jurusan'   => $jurusan,
                    'tingkatan' => $tingkatan,
                    'hp'        => $hp,
                    'email'     => $email,
                    'alamat'    => $alamat,
                ));

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Peserta telah ditambah!'
                    )
                );
            } else {
                $prepare_query = 'UPDATE kenshin SET nama_kenshin = :nama, nim = :nim, nik = :nik, jurusan = :jurusan, kyu_id = :tingkatan, hp = :hp, email = :email, alamat = :alamat WHERE id_kenshin = :id_kenshin';
                $data = array(
                    'id_kenshin'=> $id_kenshin,
                    'nama'      => $nama,
                    'nim'       => $nim,
                    'nik'       => $nik,
                    'jurusan'   => $jurusan,
                    'tingkatan' => $tingkatan,
                    'hp'        => $hp,
                    'email'     => $email,
                    'alamat'    => $alamat,
                );

                if($password != '') {
                    $prepare_query = 'UPDATE kenshin SET nama_kenshin = :nama, password = :password, nim = :nim, nik = :nik, jurusan = :jurusan, kyu_id = :tingkatan, hp = :hp, email = :email, alamat = :alamat WHERE id_kenshin = :id_kenshin';
                    $data['password'] = sha1($password);
                }		

                $handle = $pdo->prepare($prepare_query);		
                $sukses = $handle->execute($data);

                echo json_encode(
                    array(
                        'error' => false, 
                        'msg' => 'Data peserta berhasil dirubah!'
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
    }
?>