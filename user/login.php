<?php require_once('../includes/init.php'); 

$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['username']) ? trim($_POST['password']) : '';

if(isset($_POST['submit'])):
	
	// Validasi
	if(!$username) {
		$errors[] = 'Username tidak boleh kosong';
	}
	if(!$password) {
		$errors[] = 'Password tidak boleh kosong';
	}
	
	if(empty($errors)):
		
		$query = $pdo->prepare('SELECT * FROM user WHERE username = :username');
		$query->execute( array(
			'username' => $username
		));
		$query->setFetchMode(PDO::FETCH_ASSOC);
		$user = $query->fetch();
		
		if($user) {
			$hashed_password = sha1($password);
			if($user['password'] === $hashed_password) {
				$_SESSION["user_id"] = $user["id_user"];
				$_SESSION["username"] = $user["username"];
				$_SESSION["role"] = $user["role"];
				header('Location: ../' .  $base_path . 'user/dashboard');
			} else {
				$errors[] = 'Maaf, anda salah memasukkan username / password';
			}
		} else {
			$errors[] = 'Maaf, anda salah memasukkan username / password';
		}
		
	endif;

endif;	
?>

<?php
	require_once('../template-parts/header.php');
?>

	<div style="width:300px;text-align:center;margin: 0 auto;">
		<img src="../images/logo.jpg" alt="" style="padding:50px;width:100%;margin:0 auto;">
	</div>

<div id="custom-login-container">
	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<div>
			<label>Username:</label>
			<input type="text" class="form-control" name="username" value="<?php echo htmlentities($username); ?>">
		</div>
		<br>
		<div>					
			<label>Password:</label>
			<input type="password" class="form-control" name="password">
		</div>
		<br>
		<div>
			<button type="submit" class="btn btn-primary" name="submit" value="submit" class="button">Log in</button>
		</div>
	</form>
</div>