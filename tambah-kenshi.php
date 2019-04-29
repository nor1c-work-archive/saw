<?php require_once('includes/init.php'); ?>

<?php
$errors = array();
$sukses = false;

$username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
$password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
$password2 = (isset($_POST['password2'])) ? trim($_POST['password2']) : '';
$nim = (isset($_POST['nim'])) ? trim($_POST['nim']) : '';
$nik = (isset($_POST['nik'])) ? trim($_POST['nik']) : '';
$nama_kenshin = (isset($_POST['nama_kenshin'])) ? trim($_POST['nama_kenshin']) : '';
$jurusan = (isset($_POST['jurusan'])) ? trim($_POST['jurusan']) : '';
$tingkatan = (isset($_POST['tingkatan'])) ? trim($_POST['tingkatan']) : '';
$email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
$alamat = (isset($_POST['alamat'])) ? trim($_POST['alamat']) : '';
$hp = (isset($_POST['hp'])) ? trim($_POST['hp']) : '';
$kyu_id = (isset($_POST['kyu_id'])) ? trim($_POST['kyu_id']) : '';

if(isset($_POST['submit'])):		
	
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
	if(!$nama_kenshin) {
		$errors[] = 'Nama tidak boleh kosong';
	}		
	// Validasi Email
	if(!$email) {
		$errors[] = 'Email tidak boleh kosong';
	}
	
	// Cek Username
	if($username) {
		$query = $pdo->prepare('SELECT username FROM kenshin WHERE kenshin.username = :username');
		$query->execute(array('username' => $username));
		$result = $query->fetch();
		if(!empty($result)) {
			$errors[] = 'Username sudah digunakan';
		}
	}
		
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO kenshin (username, password, nim, nik, nama_kenshin, jurusan, tingkatan, email, alamat, hp, kyu_id) VALUES (:username, :password, :nim, :nik, :nama_kenshin, :jurusan, :tingkatan, :email, :alamat, :hp, :kyu_id)');
		$handle->execute( array(
			'username' => $username,
			'password' => sha1($password),
            'nim' => $nim,
            'nik' => $nik,
			'nama_kenshin' => $nama_kenshin,
            'jurusan' => $jurusan,
            'tingkatan' => $tingkatan,
			'email' => $email,
			'alamat' => $alamat,
			'hp' => $hp,
			'kyu_id' => $kyu_id,
			//'tanggal_input' => date('Y-m-d')
		) );
		$sukses = "<strong>{$username}</strong> berhasil dimasukkan.";
	
	endif;

endif;
?>

<?php
$judul_page = 'Calon Peserta UKT';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			<h1>Tambah Calon Peserta UKT</h1>
			
			<?php if(!empty($errors)): ?>
				<div class="msg-box warning-box">
					<p><strong>Error:</strong></p>
					<ul>
						<?php foreach($errors as $error): ?>
							<li><?php echo $error; ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				
			<?php endif; ?>
			
			<?php if($sukses): ?>
			
				<div class="msg-box">
					<p><?php echo $sukses; ?></p>
				</div>	
				
			<?php else: ?>
			
				<form action="tambah-kenshi.php" method="post">
					<input type="hidden" name="kyu_id" value="<?=$_GET['kyu']?>">
					<tr><div class="field-wrap clearfix">					
                        <td><label>Username <span class="red">*</span></label></td>
                        <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
                        </div></tr>
					<div class="field-wrap clearfix">					
						<label>Password <span class="red">*</span></label>
						<input type="password" name="password">
					</div>
					<div class="field-wrap clearfix">					
						<label>Password Lagi <span class="red">*</span></label>
						<input type="password" name="password2">
					</div>
                    <div class="field-wrap clearfix">					
						<label>NIM</label>
						<input type="text" name="nim" value="<?php echo $nim; ?>">
					</div>
                    <div class="field-wrap clearfix">					
						<label>NIK Kempo</label>
						<input type="text" name="nik" value="<?php echo $nik; ?>">
					</div>
					<div class="field-wrap clearfix">					
						<label>Nama <span class="red">*</span></label>
						<input type="text" name="nama_kenshin" value="<?php echo $nama_kenshin; ?>">
					</div>
                    <div class="field-wrap clearfix">					
						<label>Fakultas/Jurusan</label>
						<select name="jurusan">
							<option value="Teknik Informatika" >Teknik Informatika</option>
							<option value="Sistem Informasi" >Sistem Informasi</option>
                            <option value="Manajemen Informatika" >Manajemen Informatika</option>
							<option value="Ekonomi Akuntansi" >Ekonomi Akuntansi</option>
                            <option value="Teknik Sistem Perkapalan" >Teknik Sistem Perkapalan</option>
						</select>
					</div>
                    <div class="field-wrap clearfix">					
						<label>Tingkatan</label>
						<select name="tingkatan">
							<option value="Yudansha 1" >Yudansha 1</option>
							<option value="Menuju Kyu 1" >Menuju Kyu 1</option>
                            <option value="Menuju Kyu 2" >Menuju Kyu 2</option>
							<option value="Menuju Kyu 3" >Menuju Kyu 3</option>
                            <option value="Menuju Kyu 4" >Menuju Kyu 4</option>
                            <option value="Menuju Kyu 5" >Menuju Kyu 5</option>
                            <option value="Menuju Kyu 6" >Menuju Kyu 6</option>
						</select>
					</div>
                    <div class="field-wrap clearfix">					
						<label>Alamat</label>
						<input type="text" name="alamat" value="<?php echo $alamat; ?>">
					</div>
                    <div class="field-wrap clearfix">					
						<label>No. Telp</label>
						<input type="text" name="hp" value="<?php echo $hp; ?>">
					</div>
					<div class="field-wrap clearfix">					
						<label>Email <span class="red">*</span></label>
						<input type="email" name="email" value="<?php echo $email; ?>">
					</div>
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Simpan</button>
					</div>
				</form>
				
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');