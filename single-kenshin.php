<?php require_once('includes/init.php'); ?>

<?php
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
}
?>

<?php
$judul_page = 'Detail Peserta UKT dan Nilai per-Kriteria';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
		<table>	
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>
				
			<?php elseif(!empty($result)): ?>
			
				<tr><td><h4>Nama Kenshin</h4></td>
				<td>: </td>
				<td><?php echo $result['nama_kenshin']; ?></td>
				</tr>
				
				<tr><td><h4>NIM</h4></td>
				<td>:</td>
				<td><?php echo $result['nim']; ?><td>
				</tr>
				
				<tr><td><h4>NIK Kempo</h4></td>
				<td>:</td>
				<td><?php echo $result['nik']; ?><td>
				</tr>
				
				<tr><td><h4>Fakultas / Jurusan</h4></td>
				<td>:</td>
				<td><?php echo $result['jurusan']; ?><td>
				</tr>
				
				<tr><td><h4>Tingkatan</h4></td>
				<td>:</td>
				<td><?php echo $result['tingkatan']; ?><td>
				</tr>
				
				<tr><td><h4>Email</h4></td>
				<td>:</td>
				<td><?php echo $result['email']; ?><td>
				</tr>

				<tr><td><h4>Alamat</h4></td>
				<td>:</td>
				<td><?php echo $result['alamat']; ?><td>
				</tr>

				<tr><td><h4>No. Telp</h4></td>
				<td>:</td>
				<td><?php echo $result['hp']; ?><td>
				</tr>
				<?php
				$query2 = $pdo->prepare('SELECT nilai_kenshin.nilai AS nilai, kriteria.nama AS nama FROM kriteria 
				LEFT JOIN nilai_kenshin ON nilai_kenshin.id_kriteria = kriteria.id_kriteria 
				AND nilai_kenshin.id_kenshin = :id_kenshin 
				WHERE kriteria.kyu_id = ' . $_GET['kyu'] . '
				ORDER BY kriteria.urutan_order ASC');
				$query2->execute(array(
					'id_kenshin' => $id_kenshin
				));
				$query2->setFetchMode(PDO::FETCH_ASSOC);
				$kriterias = $query2->fetchAll();
				if(!empty($kriterias)):
				?>
					<table class="pure-table">
						<thead>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo $kriteria['nama']; ?></th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach($kriterias as $kriteria ): ?>
									<th><?php echo ($kriteria['nilai']) ? $kriteria['nilai'] : 0; ?></th>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
			</table>	
				<?php
				endif;
				?>

				<p><a href="edit-kenshin.php?id=<?php echo $id_kenshin; ?>&kyu=<?=$_GET['kyu']?>" class="button"><span class="fa fa-pencil"></span> Edit</a> &nbsp; <a href="hapus-kenshin.php?id=<?php echo $id_kenshin; ?>&kyu=<?=$_GET['kyu']?>" class="button button-red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></p>
			
			<?php endif; ?>			
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');