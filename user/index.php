<?php require_once('../includes/init.php'); ?>


	<?php
		$judul_page = 'User';
		require_once('../template-parts/header.php');
	?>
			
	<div id="custom-full-container">
	
		<h4>Daftar <?=$judul_page?></h4>

		<div id="successMessage" class="alert alert-success" role="alert"></div>
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
			</ul>
		</div>

		<table id="table" class="table table-lg table-hover" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500"
		>
			<thead>
				<tr>
					<th width="40">No</th>
					<th>Nama Lengkap</th>
					<th>Username</th>
					<th>Role</th>
				</tr>
			</thead>
			<tbody id="listData"></tbody>
		</table>

	</div>

	<!-- tambah user -->
	<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalInputTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="addModalBody">
				<table class="modal-tab pure-table">
					<div id="errMessage" class="alert alert-danger" role="alert"></div>
					<form id="form-add">
						<input type="hidden" name="id_user">
						<tr>
							<td>Nama Lengkap<span class="required">*</span></td>
							<td width="500">
								<input type="text" name="nama" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Username<span class="required">*</span></td>
							<td>
								<input type="text" name="username" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>
								<label for="password">Password<span class="required">*</span></label>
							</td>
							<td>
								<input type="password" id="password" name="password" class="form-control" minLength="6" required/>
							</td>
						</tr>
						<tr>
							<td>
								<label for="confirmation_password">Password Lagi<span class="required">*</span></label>
							</td>
							<td>
								<input type="password" id="confirmation_password" name="confirmation_password" class="form-control" minLength="6" required/>
							</td>
						</tr>
						<tr>
							<td>Email<span class="required">*</span></td>
							<td>
								<input type="text" name="email" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Role<span class="required">*</span></td>
							<td>
								<select class="form-control" id="" name="role">
									<option value="1">Administrator</option>
									<option value="2">Pelatih</option>
									<option value="3">Viewer</option>
								</select>
							</td>
						</tr>
				</table>
			</div>
			<div class="modal-footer">
				<input type="submit" id="submit" class="btn btn-primary" value="Simpan">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</form>
			</div>
			</div>
		</div>
	</div>

	<!-- detail per user -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalTitle">Detail User <b><span id="nama_title"></b></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detailModalBody">
				<span id="err_message"></span>
				<table class="modal-tab pure-table">
					<tr>
						<td width="150">Nama Lengkap</td>
						<td width="2">:</td>
						<td>
							<span id="nama"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Username</td>
						<td width="2">:</td>
						<td>
							<span id="username"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Email</td>
						<td width="2">:</td>
						<td>
							<span id="email"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Role</td>
						<td width="2">:</td>
						<td>
							<span id="role"></span>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<script>

		refresh_data();

		$('#successMessage').hide();
		$('#errMessage').hide();

		var id = '';

		$(document).ready(function() {
			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				$('#form-add')[0].reset();
				$('input[name=username]').removeAttr('disabled');
				$('#modalInputTitle').html('Tambah User');
				$('input[name=id_user]').val('');
			});

			$('#btn-edit').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					$.ajax({
						url: '../json/user/single-user.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#modalInputTitle').html('Ubah detail data user <b>' + res[0].nama + '</b>');

							$('input[name=id_user]').val(res[0].id_user);
							$('input[name=username]').attr('disabled', true);
							$('input[name=username]').val(res[0].username);
							$('input[name=nama]').val(res[0].nama);
							$('input[name=nama_title]').val(res[0].nama);
							$('input[name=email]').val(res[0].email);
							$('select[name=role]').val(res[0].role).change();
						}
					});
				}
			});

			$('#btn-detail').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
					$('#detailModal').modal('hide');
				} else {
					$.ajax({
						url: '../json/user/single-user.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#detailModalTitle').html(res[0].title);
							$('#username').html(res[0].username);
							$('#nama').html(res[0].nama);
							$('#nama_title').html(res[0].nama);
							$('#email').html(res[0].email);
							$('#role').html(
								res[0].role == '1' ?
									'Administrator' : 'Pelatih'
							);
						}
					});
				}
			});

			$('#btn-delete').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					if(confirm('Are you sure want to delete this data?')) {
						$.ajax({
                            url: '../json/user/delete-user.php',
                            type: 'post',
                            data: {
                                id: id
                            },
                            success: function(res) {
                                refresh_data();
								$('#successMessage').show();
								$('#successMessage').html(res.msg);
                            },
                            error: function(err) {
                                $('#errMessage').show();
                                $('#errMessage').html(res.msg);
                            }
                        })
					}
				}
			});

			$('#password').keyup(function() {
				if($(this).val() == $('#confirmation_password').val()) {
					$('#submit').removeAttr('disabled', true);
				} else {
					$('#submit').attr('disabled', 'disabled');
				}
			});

			$('#confirmation_password').keyup(function() {
				if($(this).val() == $('#password').val()) {
					$('#submit').removeAttr('disabled', true);
				} else {
					$('#submit').attr('disabled', 'disabled');
				}
			});

			$('#submit').click(function(e) {
				$('#successMessage').html('');
				$('#errMessage').html('');

				e.preventDefault();
				var username = $('input[name=username]').val();
				if($('input[name=id_user]').val() != '') {
					var is_edit = true;
				} else {
					var is_edit = false;
				}

				// check username availibity
				$.ajax({
					url: '../json/user/username-availibility-check.php',
					type: 'post',
					data: {
						'username': username,
						'is_edit' : is_edit
					},
					success: function(res) {
						if (!res.error) {
							$.ajax({
								url: '../json/user/save-user.php',
								type: 'post',
								data: {
									'submit' 	: true,
									'is_edit'	: is_edit,
									'id_user'	: is_edit ? $('input[name=id_user]').val() : null,
									'nama'		: $('input[name=nama]').val(),
									'username'	: username,
									'email'		: $('input[name=email]').val(),
									'password'	: $('input[name=password]').val(),
									'role'		: $('select[name=role] option:selected').val(),
								},
								success: function(res) {
									$('#addModal').modal('hide');
									refresh_data();
									$('#successMessage').show();
									$('#successMessage').html(res.msg);
								},
								error: function(err) {
									$('#errMessage').show();
									$('#errMessage').html(err.msg);
								}
							});	
						} else {
							$('#errMessage').show();
							$('#errMessage').html(res.msg);
						}
					},
					error: function(err) {
						console.log(err);
					}
				})
			});
		});

		function refresh_data() {
			var roles = {
				'1' : 'Administrator',
				'2' : 'Pelatih',
				'3' : 'Viewer'
			};
			
			$.ajax({
				url: '../json/user/list-user.php',
				type: 'get',
				success: function(res) {
					var data = '';
					res.forEach(function(datas,key) {
						data += '<tr id="selectTR-'+datas.id_user+'" onClick="selectTR('+datas.id_user+')">'+
									'<td>'+parseInt(key+1)+'</td>'+
									'<td>'+datas.nama+'</td>'+
									'<td>'+datas.username+'</td>'+
									'<td>'+roles[datas.role]+'</td>'+
							    '</tr>';
					});
					$('#listData').html(data);
				}
			})
		}

		function selectTR(id_user) {
			id = id_user;

			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_user).css('background-color', '#ededed');
		}

	</script>

<?php
require_once('../template-parts/footer.php');