<?php require_once('includes/init.php'); ?>


<?php
$judul_page = 'List Kriteria';
require_once('template-parts/header.php');
?>

	<div class="container">
	
		<?php include_once('template-parts/sidebar-kriteria.php'); ?>
	
			<?php
			$status = isset($_GET['status']) ? $_GET['status'] : '';
			$msg = '';
			switch($status):
				case 'sukses-baru':
					$msg = 'Kriteria baru berhasil dibuat';
					break;
				case 'sukses-hapus':
					$msg = 'Kriteria behasil dihapus';
					break;
				case 'sukses-edit':
					$msg = 'Kriteria behasil diedit';
					break;
			endswitch;
			
			if($msg):
				echo '<div class="msg-box msg-box-full">';
				echo '<p><span class="fa fa-bullhorn"></span> &nbsp; '.$msg.'</p>';
				echo '</div>';
			endif;
			?>
			
			<?php
			$query = $pdo->prepare('SELECT * 
									FROM kriteria kr 
									LEFT JOIN kyu k ON (k.id_kyu=kr.kyu_id)
									WHERE kr.kyu_id = '.$_GET['kyu'].'
									ORDER BY urutan_order ASC');			
			$query->execute();
			// menampilkan berupa nama field
			$query->setFetchMode(PDO::FETCH_ASSOC);
			
			if($query->rowCount() > 0):
			?>

			<div class="table-responsive">
				<table class="table table-sm">
					<thead>
						<tr>
							<th>Nama Kriteria</th>
							<th>Type</th>
							<th>Bobot</th>
							<th>Urutan</th>
							<th>Cara Penilaian</th>
							<!-- <th>Detail</th> -->
							<!-- <th>Edit</th>
							<th>Hapus</th> -->
						</tr>
					</thead>
					<tbody>
						<?php while($hasil = $query->fetch()): ?>
							<tr>
								<td><?php echo $hasil['nama']; ?></td>
								<td>
								<?php
								if($hasil['type'] == 'c1') {
									echo 'C-1';
								} elseif($hasil['type'] == 'c2') {
									echo 'C-2';
								} elseif($hasil['type'] == 'c3') {
									echo 'C-3';
								} elseif($hasil['type'] == 'c4') {
									echo 'C-4';
								} elseif($hasil['type'] == 'c5') {
									echo 'C-5';
								}
								?>
								</td>
								<td><?php echo $hasil['bobot']; ?></td>							
								<td><?php echo $hasil['urutan_order']; ?></td>							
								<td><?php echo ($hasil['ada_pilihan']) ? 'Pilihan': 'Inputan'; ?></td>							
								<!-- <td><a href="single-kriteria.php?id=<?php echo $hasil['id_kriteria']; ?>&kyu=<?=$hasil['kyu_id']?>"><span class="fa fa-eye"></span> Detail</a></td>
								<td><a href="edit-kriteria.php?id=<?php echo $hasil['id_kriteria']; ?>&kyu=<?=$hasil['kyu_id']?>"><span class="fa fa-pencil"></span> Edit</a></td>
								<td><a href="hapus-kriteria.php?id=<?php echo $hasil['id_kriteria']; ?>&kyu=<?=$hasil['kyu_id']?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td> -->
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>

			<?php else: ?>
				<p>Maaf, belum ada data untuk kriteria.</p>
			<?php endif; ?>
	
	</div><!-- .container -->

<?php
require_once('template-parts/footer.php');