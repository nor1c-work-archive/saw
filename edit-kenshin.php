<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

$id_kenshin = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_kenshin) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT * FROM kenshin WHERE id_kenshin = :id_kenshin');
	$query->execute(array('id_kenshin' => $id_kenshin));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	}

	$id_kenshin = (isset($result['id_kenshin'])) ? trim($result['id_kenshin']) : '';
	$nama_kenshin = (isset($result['nama_kenshin'])) ? trim($result['nama_kenshin']) : '';
}

if(isset($_POST['submit'])):	
	
//	$nama_kenshin = (isset($_POST['nama_kenshin'])) ? trim($_POST['nama_kenshin']) : '';
	$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();
	
	// Validasi ID kenshin
	if(!$id_kenshin) {
		$errors[] = 'ID kenshin tidak ada';
	}
		
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$prepare_query = 'UPDATE kenshin SET nama_kenshin = :nama_kenshin WHERE id_kenshin = :id_kenshin';
		$data = array(
			'nama_kenshin' => $nama_kenshin,
			'id_kenshin' => $id_kenshin,
		);		
		$handle = $pdo->prepare($prepare_query);		
		$sukses = $handle->execute($data);
		
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_kenshin (id_kenshin, id_kriteria, nilai) 
				VALUES (:id_kenshin, :id_kriteria, :nilai)
				ON DUPLICATE KEY UPDATE nilai = :nilai');
				$handle->execute( array(
					'id_kenshin' => $id_kenshin,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;
		
		redirect_to('list-kenshin.php?status=sukses-edit');
	
	endif;

endif;
?>

<?php
$judul_page = 'Edit kenshin';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			<h1>Edit kenshin</h1>
			
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
					<p>Data berhasil disimpan</p>
				</div>	
				
			<?php elseif($ada_error): ?>
				
				<p><?php echo $ada_error; ?></p>
			
			<?php else: ?>				
				
				<form action="edit-kenshin.php?id=<?php echo $id_kenshin; ?>" method="post">
					<div class="field-wrap clearfix">					
						<tr><td><b>Nama Kenshin</b></td>
						<td>:</td>
						<td><b><?php echo $result['nama_kenshin']; ?></b></td>
						</tr>
					</div>					
										
					<h3>Nilai Kriteria</h3>
					<?php
					$query2 = $pdo->prepare('SELECT nilai_kenshin.nilai AS nilai, kriteria.nama AS nama, kriteria.id_kriteria AS id_kriteria, kriteria.ada_pilihan AS jenis_nilai 
					FROM kriteria LEFT JOIN nilai_kenshin 
					ON nilai_kenshin.id_kriteria = kriteria.id_kriteria 
					AND nilai_kenshin.id_kenshin = :id_kenshin 
					WHERE kriteria.kyu_id = ' . $_GET['kyu'] . '
					ORDER BY kriteria.urutan_order ASC');
					$query2->execute(array(
						'id_kenshin' => $id_kenshin
					));
					$query2->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query2->rowCount() > 0):
					
						while($kriteria = $query2->fetch()):
						?>
							<div class="field-wrap clearfix">					
								<label><?php echo $kriteria['nama']; ?></label>
								<?php if(!$kriteria['jenis_nilai']): ?>
									<input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]" value="<?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?>">								
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
											<option value="<?php echo $hasl['nilai']; ?>" <?php selected($kriteria['nilai'], $hasl['nilai']); ?>><?php echo $hasl['nama']; ?></option>
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
						<button type="submit" name="submit" value="submit" class="button">Simpan Perubahan</button>
					</div>
				</form>
				
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');