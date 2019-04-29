<?php require_once('../includes/init.php'); ?>


	<?php
		$judul_page = 'Periode';
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
					<th>Periode Title</th>
					<th>Start Date</th>
					<th>End Date</th>
				</tr>
			</thead>
			<tbody id="listData"></tbody>
		</table>

	</div>

	<!-- tambah periode -->
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
						<input type="hidden" name="id_periode">
						<input type="hidden" name="is_edit">
						<tr>
							<td>Periode Title<span class="required">*</span></td>
							<td width="500">
								<input type="text" name="periode_title" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>
								<label for="bobot">Start Date<span class="required">*</span></label>
							</td>
							<td>
								<input type="text" name="start_date" class='datepicker-here form-control' data-language='en' />
							</td>
						</tr>
						<tr>
							<td>
								<label for="bobot">End Date<span class="required">*</span></label>
							</td>
							<td>
								<input type="text" name="end_date" class='datepicker-here form-control' data-language='en' />
							</td>
						</tr>
						<tr>
							<td>
							</td>
							<td>
								<label for="bobot">
									<input type="checkbox" value="" name="is_copy" id="is_copy">
									Copy kriteria from past periode
								</label>
								<select name="past_periode" id="past_periode">
									<option value="" selected readonly disabled>Select Periode</option>
									<?php
										$query = $pdo->prepare('SELECT *
																FROM periode 
																ORDER BY end_date DESC');
										$query->execute();
										$query->setFetchMode(PDO::FETCH_ASSOC);
										$kriterias = $query->fetchAll();
										foreach ($kriterias as $key => $value) {
											echo '<option value="'.$value['id_periode'].'">'.$value['periode_title'].'</option>';
										}
									?>
								</select>
							</td>
						</tr>
				</table>
			</div>
			<div class="modal-footer">
				<input type="submit" id="submit" class="btn btn-primary" value="Simpan">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			<!-- </form> -->
			</div>
		</div>
	</div>

	<!-- detail per periode -->
	<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalTitle">Detail Kriteria <b><span id="nama_title"></b></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detailModalBody">
				<span id="err_message"></span>
				<table class="modal-tab pure-table">
					<tr>
						<td width="150">Periode Title</td>
						<td width="2">:</td>
						<td>
							<span id="periode_title"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Start Date</td>
						<td width="2">:</td>
						<td>
							<span id="start_date"></span>
						</td>
					</tr>
					<tr>
						<td width="150">End Date</td>
						<td width="2">:</td>
						<td>
							<span id="end_date"></span>
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
		$('#past_periode').hide();

		var id = '';
        var addmore_count = 1;

        // init enum
		var types = {
			'c1' : 'C-1',
			'c2' : 'C-2',
			'c3' : 'C-3',
			'c4' : 'C-4',
			'c5' : 'C-5',
		};

        var is_optional = {
            '0' : 'Inputan',
            '1' : 'Pilihan'
        }
            
        var is_optional_icon = {
            '0' : '<i class="fas fa-pen"></i>',
            '1' : '<i class="fas fa-clipboard-list"></i>'
        }

		$(document).ready(function() {
			$('#is_copy').click(function() {
				if($(this).is(':checked')) {
					$('#past_periode').show();
				} else {
					$('#past_periode').hide();
					$('#past_periode').val('');
				}
			});

			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				// reset
				$('input[name=is_edit]').val('');
				$('input[name=id_periode]').val('');
				$('#form-add')[0].reset();

				$('#modalInputTitle').html('Tambah Periode');

				$.ajax({
					url: '../json/periode/last-periode-date.php',
					success: function(res) {
						$('input[name=start_date]').datepicker({
							language: 'en',
							minDate: new Date(res.y, parseInt(res.m) - 1, parseInt(res.d)+1),
						});
					}
				});
			});

			$('input[name=end_date]').click(function() {
				var startDate = $('input[name=start_date]').val().split('-');
				$('input[name=end_date]').datepicker({
					language: 'en',
					minDate: new Date(startDate[0], parseInt(startDate[1]) - 1, parseInt(startDate[2])+1),
				});
			});

			$('#btn-edit').click(function() {
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					$.ajax({
						url: '../json/periode/single-periode.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#modalInputTitle').html('Ubah detail data periode <b>' + res[0].periode_title + '</b>');

							$('input[name=id_periode]').val(res[0].id_periode);
							$('input[name=periode_title]').val(res[0].periode_title);
							$('input[name=start_date]').val(moment(res[0].start_date).format('Y-MM-DD'));
							$('input[name=end_date]').val(moment(res[0].end_date).format('Y-MM-DD'));
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
						url: '../json/periode/single-periode.php?id='+id,
						type: 'get',
						success: function(res) {
							console.log(res);
                            $('#nama_title').html(res[0].periode_title);
							$('#periode_title').html(res[0].periode_title);
							$('#start_date').html(res[0].start_date);
							$('#end_date').html(res[0].end_date);
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
                            url: '../json/periode/delete-periode.php',
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
				if($('input[name=id_periode]').val() != '') {
					$('input[name=is_edit]').val('1');
				} else {
					$('input[name=is_edit]').val('0');
				}

				$.ajax({
					url: '../json/periode/save-periode.php',
					type: 'post',
					data: $('#addModalBody :input').serialize(),
					success: function(res) {
						if(!res.error) {
							$('#addModal').modal('hide');
							$('#successMessage').show();
							$('#successMessage').html(res.msg);
							refresh_data();
						} else {
							$('#errMessage').show();
							$('#errMessage').html(err.msg);
						}
					},
					error: function(err) {
						$('#errMessage').show();
						$('#errMessage').html(err.msg);
					}
				});
			});

		}); // end of jquery

		function refresh_data() {
			
			$.ajax({
				url: '../json/periode/list-periode.php',
				type: 'get',
				success: function(res) {
					var data = '';
					if(res.length > 0) {
                        res.forEach(function(datas, key) {
						data += '<tr id="selectTR-'+datas.id_periode+'" onClick="selectTR('+datas.id_periode+')">'+
									'<td>'+parseInt(key+1)+'</td>'+
									'<td>'+datas.periode_title+'</td>'+
									'<td>'+moment(datas.start_date).format('DD/MM/Y')+'</td>'+
									'<td>'+moment(datas.end_date).format('DD/MM/Y')+'</td>'+
							    '</tr>';
                        });
                        $('#listData').html(data);
                    } else {
                        data += '<tr>'+
                                    '<td class="td-empty" colspan="5">Belum ada data</td>'+
                                '</tr>';
                        $('#listData').html(data);
                    }
				}
			})
		}

		function selectTR(id_periode) {
			id = id_periode;
			
			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_periode).css('background-color', '#ededed');
		}

        function removeAddMore(number) {
            $('#trVariable-'+number).remove();
        }

	</script>
	
<?php
require_once('../template-parts/footer.php');