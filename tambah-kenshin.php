<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$nama_kenshin = (isset($_POST['nama_kenshin'])) ? trim($_POST['nama_kenshin']) : '';
$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();

 
 
//var_dump($result);
//exit();
if(isset($_POST['submit'])):	
	
	// Validasi
	//if(!$nama_kenshin) {
		//$errors[] = 'Nomor kenshin tidak boleh kosong';
	//}	
		
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO kenshin (nama_kenshin) VALUES (:nama_kenshin)');
		$handle->execute( array(
			'nama_kenshin' => $nama_kenshin
			
		) );
		$sukses = "kenshin no. <strong>{$nama_kenshin}</strong> berhasil dimasukkan.";
		$id_kenshin = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_kenshin (id_kenshin, id_kriteria, nilai) VALUES (:id_kenshin, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_kenshin' => $id_kenshin,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-kenshin.php?status=sukses-baru');		
		
	endif;

endif;
?>

<?php
$judul_page = 'Penilaian UKT Kenshi';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			<h1>Penilaian UKT Kenshi</h1>
			
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
			
			
				<form action="tambah-kenshin.php" method="post">
					<div class="field-wrap clearfix">					
						<label>Nama Kenshin <span class="red">*</span></label>
						<select name="select" id="select">
						<?php
						$query = $pdo->prepare('SELECT * FROM kenshin WHERE kyu_id = ' . $_GET['kyu']);	
						$query->execute();
						// menampilkan berupa nama field
						$query->setFetchMode(PDO::FETCH_ASSOC);
						while($hasil = $query->fetch()): 
							echo "<option value='".$hasil['id_kenshin']."'>".$hasil['nama_kenshin']."</option>";
						endwhile;
						?>
						</select>
						</div>					
										
					<h3>Nilai Kriteria</h3>
					<?php
					$query = $pdo->prepare('SELECT id_kriteria, nama, ada_pilihan FROM kriteria WHERE kyu_id = ' . $_GET['kyu'] . ' ORDER BY urutan_order ASC');			
					$query->execute();
					// menampilkan berupa nama field
					$query->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query->rowCount() > 0):
					
						while($kriteria = $query->fetch()):							
						?>
						
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['ada_pilihan']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">								
								<?php else: ?>
									
									<select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
										<option value="0">-- Pilih Variabel --</option>
										<?php
										$query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
										$query3->execute(array(
											'id_kriteria' => $kriteria['id_kriteria']
										));
										// menampilkan berupa nama field
										$query3->setFetchMode(PDO::FETCH_ASSOC);
										if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
										?>
											<option value="<?php echo $hasl['nilai']; ?>"><?php echo $hasl['nama']; ?></option>
										<?php
										endwhile; endif;
										?>
									</select>
									
								<?php endif; ?>
							</div>	
						
						<?php
						endwhile;
						
					else:					
						echo '<p>Kriteria masih kosong.</p>';						
					endif;
					?>
															
					<div class="field-wrap clearfix">
						<button type="submit" name="submit" value="submit" class="button">Konfirmasi Nilai</button>
					</div>
				</form>
					
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');