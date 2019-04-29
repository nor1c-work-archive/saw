<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$judul_page = 'List Kenshin';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-user.php'); ?>
	
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
		
			<h1>Daftar Kenshi Kempo UNSADA</h1>
			
			<?php
			$query = $pdo->prepare('SELECT * FROM kenshin');			
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
						<th>Cetak</th>
						<th>Hapus</th>
					</tr>
				</thead>
				<tbody>
					<?php while($hasil = $query->fetch()): ?>
						<tr>
							<td><?php echo $hasil['nama_kenshin']; ?></td>							
							<td><?php echo $hasil['nim']; ?></td>
							<td><?php echo $hasil['nik']; ?></td>							
							<td><a href="single-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>"><span class="fa fa-eye"></span> Detail</a></td>
							<td><a href="edit-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>"><span class="fa fa-pencil"></span> Edit</a></td>
							<td><a href="form24.php?id=<?php echo $hasil['id_kenshin']; ?>"><span class="fa fa-bullhorn"></span> Print</a></td>
							<td><a href="hapus-kenshin.php?id=<?php echo $hasil['id_kenshin']; ?>" class="red yaqin-hapus"><span class="fa fa-times"></span> Hapus</a></td>
						</tr>
					<?php endwhile; ?>
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