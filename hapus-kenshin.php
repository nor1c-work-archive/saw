<?php require_once('includes/init.php'); ?>
<?php cek_login($role = array(1, 2)); ?>

<?php
$ada_error = false;
$result = '';

$id_kenshin = (isset($_GET['id'])) ? trim($_GET['id']) : '';

if(!$id_kenshin) {
	$ada_error = 'Maaf, data tidak dapat diproses.';
} else {
	$query = $pdo->prepare('SELECT id_kenshin FROM kenshin WHERE id_kenshin = :id_kenshin');
	$query->execute(array('id_kenshin' => $id_kenshin));
	$result = $query->fetch();
	
	if(empty($result)) {
		$ada_error = 'Maaf, data tidak dapat diproses.';
	} else {
		
		$handle = $pdo->prepare('DELETE FROM nilai_kenshin WHERE id_kenshin = :id_kenshin');				
		$handle->execute(array(
			'id_kenshin' => $result['id_kenshin']
		));
		$handle = $pdo->prepare('DELETE FROM kenshin WHERE id_kenshin = :id_kenshin');				
		$handle->execute(array(
			'id_kenshin' => $result['id_kenshin']
		));
		redirect_to('list-kenshin.php?status=sukses-hapus');
		
	}
}
?>

<?php
$judul_page = 'Hapus kenshin';
require_once('template-parts/header.php');
?>

	<div class="main-content-row">
	<div class="container clearfix">
	
		<?php include_once('template-parts/sidebar-kenshin.php'); ?>
	
		<div class="main-content the-content">
			<h1><?php echo $judul_page; ?></h1>
			
			<?php if($ada_error): ?>
			
				<?php echo '<p>'.$ada_error.'</p>'; ?>	
			
			<?php endif; ?>
			
		</div>
	
	</div><!-- .container -->
	</div><!-- .main-content-row -->


<?php
require_once('template-parts/footer.php');