<div id="modify">
	<ul>
		<?php 
			$role = $_SESSION['role'];
		
			if($role == '1' || $role == '2') {
				echo '<li><a id="btn-add" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> TAMBAH</a></li>
					  <li><a id="btn-edit" data-toggle="modal" data-target="#addModal"><i class="fas fa-pen"></i> UBAH</a></li>';
			}
		?>
		<li><a id="btn-detail" data-toggle="modal" data-target="#detailModal"><i class="fas fa-search"></i> DETIL</a></li>
		<?php
			if($role == '1' || $role == '2') {
				echo '<li><a id="btn-delete"><i class="fas fa-trash"></i> HAPUS</a></li>';
			}
		?>

		<?php require_once('../template-parts/kyu.php') ?>
	</ul>
</div>