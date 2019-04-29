<?php require_once('../includes/init.php'); ?>


	<?php
		$judul_page = 'Peserta UKT';
		require_once('../template-parts/header.php');
	?>
			
	<div id="custom-full-container">
	
		<h4>Daftar <?=$judul_page?></h4>

		<div id="successMessage" class="alert alert-success" role="alert"></div>
		<?php require_once('../template-parts/action.php'); ?>
		<br><br><br>
		<div id="filter">
			Filter by: <select name="searchType" id="searchType" class="form-control" style="display:inline-block;width:150px;">
				<option value="nama_kenshin">Nama</option>
				<option value="jurusan">Jurusan</option>
			</select>
			<input type="text" name="searchKeyword" id="searchKeyword" value="" style="display:inline-block;width:200px;" class="form-control">
			<button class="btn btn-primary" id="filterBtn">Filter</button>
		</div>
		<hr>
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
                    <th>Jurusan</th>
                    <th>Tingkatan</th>
                    <th>Email</th>
				</tr>
			</thead>
			<tbody id="listData"></tbody>
		</table>

	</div>

	<!-- tambah Peserta -->
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
						<input type="hidden" name="id_kenshin">
						<tr>
							<td>Nama Lengkap<span class="required">*</span></td>
							<td width="500">
								<input type="text" name="nama_kenshin" class="form-control" required/>
							</td>
						</tr>
						<!-- <tr>
							<td>Username<span class="required">*</span></td>
							<td>
								<input type="text" name="username" class="form-control" required/>
							</td>
						</tr>
						<tr id="password">
							<td>
								<label for="password">Password<span class="required">*</span></label>
							</td>
							<td>
								<input type="password" id="password" name="password" class="form-control" minLength="6" required/>
							</td>
						</tr>
						<tr id="confirmation_password">
							<td>
								<label for="confirmation_password">Password Lagi<span class="required">*</span></label>
							</td>
							<td>
								<input type="password" id="confirmation_password" name="confirmation_password" class="form-control" minLength="6" required/>
							</td>
						</tr> -->
						<tr>
							<td>NIM</td>
							<td>
								<input type="text" name="nim" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>NIK Kempo</td>
							<td>
								<input type="text" name="nik" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Fakultas/Jurusan</td>
							<td>
								<select name="jurusan" class="form-control">
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Manajemen Informatika">Manajemen Informatika</option>
                                    <option value="Ekonomi Akuntansi">Ekonomi Akuntansi</option>
                                    <option value="Teknik Sistem Perkapalan">Teknik Sistem Perkapalan</option>
                                </select>
							</td>
						</tr>
						<tr>
							<td>Tingkatan</td>
							<td>
								<select name="tingkatan" id="" class="form-control">
									<?php
										$query = $pdo->prepare('SELECT *
																FROM kyu 
																ORDER BY id_kyu ASC');
										$query->execute();
										$query->setFetchMode(PDO::FETCH_ASSOC);
										$kriterias = $query->fetchAll();
										foreach ($kriterias as $key => $value) {
											echo '<option value="'.$value['id_kyu'].'">'.$value['kyu_title'] . ' | ' . $value['kyu_description'].'</option>';
										}
									?>
                                </select>
							</td>
						</tr>
						<tr>
							<td>No. Telp</td>
							<td>
								<input type="text" name="hp" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Email<span class="required">*</span></td>
							<td>
								<input type="text" name="email" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td>
								<textarea name="alamat" cols="30" rows="10" class="form-control"></textarea>
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

	<!-- detail per Peserta -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalTitle">Detail Peserta <b><span id="nama_title"></b></span></h5>
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
						<td width="150">NIM</td>
						<td width="2">:</td>
						<td>
							<span id="nim"></span>
						</td>
					</tr>
					<tr>
						<td width="150">NIK Kempo</td>
						<td width="2">:</td>
						<td>
							<span id="nik"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Fakultas/Jurusan</td>
						<td width="2">:</td>
						<td>
							<span id="jurusan"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Tingkatan</td>
						<td width="2">:</td>
						<td>
							<span id="tingkatan"></span>
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
						<td width="150">No. Telp</td>
						<td width="2">:</td>
						<td>
							<span id="hp"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Alamat</td>
						<td width="2">:</td>
						<td>
							<span id="alamat"></span>
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
			$('select[name=kyu_select]').change(function() {
				refresh_data($('#kyu_select :selected').val());
			});

			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				$('#form-add')[0].reset();
				$('input[name=username]').removeAttr('disabled');
				$('#modalInputTitle').html('Tambah Peserta');

				$('input[name=id_kenshin]').val('');

                $('#password').show();
			    $('#confirmation_password').show();
			});

			$('#btn-edit').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					$.ajax({
						url: '../json/peserta/single-kenshin.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#modalInputTitle').html('Ubah detail data peserta <b>' + res[0].nama_kenshin + '</b>');

							$('#password').hide();
							$('#confirmation_password').hide();

							$('input[name=id_kenshin]').val(res[0].id_kenshin);
							$('input[name=nama_title]').val(res[0].nama_kenshin);
							$('input[name=username]').attr('disabled', true);
							$('input[name=username]').val(res[0].username);
							$('input[name=nama_kenshin]').val(res[0].nama_kenshin);
							$('input[name=nim]').val(res[0].nim);
							$('input[name=nik]').val(res[0].nik);
							$('select[name=jurusan]').val(res[0].jurusan).change();
							$('select[name=tingkatan]').val(res[0].kyu_id).change();
							$('input[name=hp]').val(res[0].hp);
							$('input[name=email]').val(res[0].email);
							$('textarea[name=alamat]').val(res[0].alamat);
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
						url: '../json/peserta/single-kenshin.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#detailModalTitle').html(res[0].title);
							$('#nama').html(res[0].nama_kenshin);
							$('#nama_title').html(res[0].nama_kenshin);
							$('#nim').html(res[0].nim);
							$('#nik').html(res[0].nik);
							$('#jurusan').html(res[0].jurusan);
							if(res[0].tingkatan == null) {
                                $('#tingkatan').html('<i>Belum memiliki tingkatan</i>');
                            } else {
                                $('#tingkatan').html(res[0].kyu_title + ' / ' + res[0].tingkatan);
                            }
							$('#email').html(res[0].email);
							$('#hp').html(res[0].hp);
							$('#alamat').html(res[0].alamat);
						}
					});
				}
			});

			$('#btn-delete').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					if(confirm('Are you sure want to delete this data? Note that this action will delete the student scores too!')) {
						$.ajax({
                            url: '../json/peserta/delete-kenshin.php',
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
				if($('input[name=id_kenshin]').val() != '') {
					var is_edit = true;
				} else {
					var is_edit = false;
				}

				// check username availibity
				$.ajax({
					url: '../json/peserta/username-availibility-check.php',
					type: 'post',
					data: {
						'username': username,
						'is_edit' : is_edit
					},
					success: function(res) {
						if (!res.error) {
							$.ajax({
								url: '../json/peserta/save-kenshin.php',
								type: 'post',
								data: {
									'submit' 	    : true,
									'is_edit'	    : is_edit,
									'id_kenshin'    : is_edit ? $('input[name=id_kenshin]').val() : null,
									'nama'		    : $('input[name=nama_kenshin]').val(),
									'username'	    : username,
									'password'	    : $('input[name=password]').val(),
									'nim'	        : $('input[name=nim]').val(),
									'nik'	        : $('input[name=nik]').val(),
									'jurusan'	    : $('select[name=jurusan]').val(),
									'tingkatan'	    : $('select[name=tingkatan]').val(),
									'hp'	        : $('input[name=hp]').val(),
									'email'	        : $('input[name=email]').val(),
									'alamat'		: $('textarea[name="alamat"]').val(),
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

			$('#filterBtn').click(function() {
				refresh_data($('#kyu_select :selected').val(), $('#searchType').val(), $('#searchKeyword').val());
			});
		});

		function refresh_data(kyu_id, searchType, searchKeyword) {
			var roles = {
				'1' : 'Administrator',
				'2' : 'Pelatih',
				'3' : 'Anggota'
			};
			
			$.ajax({
				url: '../json/peserta/list-kenshin.php',
				type: 'post',
				data: {
					kyu_id,
					searchType,
					searchKeyword
				},
				success: function(res) {
					if(res.length > 0) {
						var data = '';
						res.forEach(function(datas,key) {
							data += '<tr id="selectTR-'+datas.id_kenshin+'" onClick="selectTR('+datas.id_kenshin+')">'+
										'<td>'+parseInt(key+1)+'</td>'+
										'<td>'+datas.nama_kenshin+'</td>'+
										'<td>'+datas.jurusan+'</td>'+
										'<td>'+datas.tingkatan+'</td>'+
										'<td>'+datas.email+'</td>'+
									'</tr>';
						});
					} else {
						var data =  '<tr>'+
										'<td class="td-empty" colspan="10">Belum ada data</td>'+
									'</tr>';
					}
					$('#listData').html(data);
				}
			})
		}

		function selectTR(id_kenshin) {
			id = id_kenshin;

			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_kenshin).css('background-color', '#ededed');
		}

	</script>

<?php
require_once('../template-parts/footer.php');