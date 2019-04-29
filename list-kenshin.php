<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$judul_page = 'List Kenshin';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			
			<?php
			$status = isset($_GET['status']) ? $_GET['status'] : '';
			$msg = '';
			switch($status):
				case 'sukses-baru':
					$msg = 'Data Kenshin baru berhasil ditambahkan';
					break;
				case 'sukses-hapus':
					$msg = 'Kenshin berhasil dihapus';
					break;
				case 'sukses-edit':
					$msg = 'Kenshin berhasil diedit';
					break;
			endswitch;
			
			if($msg):
				echo '<div class="msg-box msg-box-full">';
				echo '<p><span class="fa fa-bullhorn"></span> &nbsp; '.$msg.'</p>';
				echo '</div>';
			endif;
			?>
		
			<h1>Daftar Peserta UKT</h1>
			
			<?php
			$query = $pdo->prepare('SELECT * FROM kenshin WHERE kyu_id = ' . $_GET['kyu']);			
			$query->execute();
			// menampilkan berupa nama field
			$query->setFetchMode(PDO::FETCH_ASSOC);
			
			if($query->rowCount() > 0):
			?>
			
			<table class="pure-table pure-table-striped">
				<thead>
					<tr>
						<th>Nama Kenshin</th>
						<th>NIM</th>
						<th>NIK Kempo</th>
						<th>Detail</th>						
						<th>Edit</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody>
					<?php while($hasil = $query->fetch()): ?>
						<tr>
							<td><?php echo $hasil['nama_kenshin']; ?></td>							
							<td><?php echo $hasil['nim']; ?></td>
							<td><?php echo $hasil['nik']; ?></td>							
							<td><a href="single-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>&kyu=<?=$_GET['kyu']?>"><span class="fa fa-eye"></span> Detail</a></td>
							<td><a href="edit-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>&kyu=<?=$_GET['kyu']?>"><span class="fa fa-pencil"></span> Edit</a></td>
							<td><a href="hapus-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>&kyu=<?=$_GET['kyu']?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
			
			
			<!-- Langsung Lihat Nilai ==================== -->
			<?php
			// Fetch semua kriteria
			$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot FROM kriteria
				WHERE kyu_id = '.$_GET['kyu'].'
				ORDER BY urutan_order ASC');
			$query->execute();			
			$kriterias = $query->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
			
			// Fetch semua kenshin
			$query2 = $pdo->prepare('SELECT id_kenshin, nama_kenshin FROM kenshin WHERE kyu_id = ' . $_GET['kyu']);
			$query2->execute();			
			$query2->setFetchMode(PDO::FETCH_ASSOC);
			$kenshins = $query2->fetchAll();			
			?>
			
			<h3>Nilai per-Kriteria</h3>
			<table class="pure-table pure-table-striped">
				<thead>
					<tr class="super-top">
						<th rowspan="2" class="super-top-left">Nama Kenshin</th>
						<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
					</tr>
					<tr>
						<?php foreach($kriterias as $kriteria ): ?>
							<th><?php echo $kriteria['nama']; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($kenshins as $kenshin): ?>
						<tr>
							<td><?php echo $kenshin['nama_kenshin']; ?></td>
							<?php
							// Ambil Nilai
							$query3 = $pdo->prepare('SELECT id_kriteria, nilai FROM nilai_kenshin
								WHERE id_kenshin = :id_kenshin');
							$query3->execute(array(
								'id_kenshin' => $kenshin['id_kenshin']
							));			
							$query3->setFetchMode(PDO::FETCH_ASSOC);
							$nilais = $query3->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_UNIQUE);
							
							foreach($kriterias as $id_kriteria => $values):
								echo '<td>';
								if(isset($nilais[$id_kriteria])) {
									echo $nilais[$id_kriteria]['nilai'];
									$kriterias[$id_kriteria]['nilai'][$kenshin['id_kenshin']] = $nilais[$id_kriteria]['nilai'];
								} else {
									echo 0;
									$kriterias[$id_kriteria]['nilai'][$kenshin['id_kenshin']] = 0;
								}
								
								if(isset($kriterias[$id_kriteria]['tn_kuadrat'])){
									$kriterias[$id_kriteria]['tn_kuadrat'] += pow($kriterias[$id_kriteria]['nilai'][$kenshin['id_kenshin']], 2);
								} else {
									$kriterias[$id_kriteria]['tn_kuadrat'] = pow($kriterias[$id_kriteria]['nilai'][$kenshin['id_kenshin']], 2);
								}
								echo '</td>';
							endforeach;
							?>
							</pre>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<?php else: ?>
				<p>Maaf, belum ada data untuk kenshin.</p>
			<?php endif; ?>
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->

<?php
require_once('template-parts/footer.php');