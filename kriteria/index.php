<?php require_once('../includes/init.php'); ?>


	<?php
		$judul_page = 'Kriteria';
		require_once('../template-parts/header.php');
	?>
	
	<div id="custom-full-container">
		<div>
			<select name="" id="periodeID" class="form-control"></select>
		</div>
	</div>
	<br>
	<div id="custom-full-container">
		
		<h4>Daftar <?=$judul_page?> <span id="title_with_kyu"></span> </h4>

		<div id="successMessage" class="alert alert-success" role="alert"></div>
		

		<div id="minus100" class="alert alert-danger">Bobot tidak boleh kurang dari 100</div>
		<div id="plus100" class="alert alert-danger">Bobot tidak boleh lebih dari 100</div>

		<div id="modify">
			<ul>
				<?php if($user_role == 'admin') { ?>
					<li><a id="btn-add" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> TAMBAH</a></li>
					<li><a id="btn-edit" data-toggle="modal" data-target="#addModal"><i class="fas fa-pen"></i> UBAH</a></li>
				<?php } ?>
				<li><a id="btn-detail" data-toggle="modal" data-target="#detailModal"><i class="fas fa-search"></i> DETIL</a></li>
				<?php if($user_role == 'admin') { ?>
					<li><a id="btn-delete"><i class="fas fa-trash"></i> HAPUS</a></li>
				<?php } ?>

				<?php require_once('../template-parts/kyu.php') ?>
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
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot</th>
					<th>Cara Penilaian</th>
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
						<input type="hidden" name="id_kriteria">
						<input type="hidden" name="is_edit">
						<input type="hidden" name="kyu_id" value="">
						<input type="hidden" name="periode" value="">
						<tr>
							<td>Nama Kriteria<span class="required">*</span></td>
							<td width="500">
								<input type="text" name="nama" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Tipe Kriteria<span class="required">*</span></td>
							<td>
                                <select name="type" class="form-control">
                                    <option value="c1">C-1</option>
                                    <option value="c2">C-2</option>
                                    <option value="c3">C-3</option>
                                    <option value="c4">C-4</option>
                                    <option value="c5">C-5</option>
                                    <option value="c6">C-6</option>
                                    <option value="c7">C-7</option>
                                </select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="bobot">Bobot<span class="required">*</span></label>
							</td>
							<td>
								<input type="text" name="bobot" class="form-control" minLength="6" required/>
							</td>
						</tr>
						<tr>
							<td>
								<label for="bobot">Urutan Order<span class="required">*</span></label>
							</td>
							<td>
								<input type="text" name="urutan_order" class="form-control" required/>
							</td>
						</tr>
						<tr>
							<td>Cara Penilaian<span class="required">*</span></td>
							<td>
                                <select name="cara_penilaian" class="form-control">
                                    <!-- <option value="2">Inputan Langsung</option> -->
                                    <option value="0">Inputan</option>
                                    <option value="1">Pilihan</option>
                                </select>
							</td>
						</tr>

                        <table id="kriteriaVariable" class="table table-lg table-hover tab-ins-modal" 
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
                                    <th>Nama Variable</th>
                                    <th>Nilai</th>
                                    <th>Urutan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyVariable">
                                <tr id="trVariable-1">
                                    <td>#</td>
                                    <td>
                                        <input type="text" name="variable[1][nama]" required>
                                    </td>
                                    <td>
                                        <input type="number" name="variable[1][nilai]" required>
                                    </td>
                                    <td>
                                        <input type="text" name="variable[1][urutan_order]">
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-default" onclick="removeAddMore('1')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
							<!-- <tbody id="tbodyVariableTotal">
                                <tr id="trVariable-total">
                                    <td>Total</td>
                                    <td></td>
                                    <td>
                                        <input type="number" disabled readonly name="inputVariable-total">
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody> -->
                            <tbody>
                                <tr>
                                    <td colspan="5">
                                        <span class="btn btn-primary" id="addMore">Add</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

	<!-- detail per user -->
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
						<td width="150">Nama Kriteria</td>
						<td width="2">:</td>
						<td>
							<span id="nama_kriteria"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Tipe</td>
						<td width="2">:</td>
						<td>
							<span id="tipe"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Bobot</td>
						<td width="2">:</td>
						<td>
							<span id="bobot"></span>
						</td>
					</tr>
					<tr>
						<td width="150">Cara Penilaian</td>
						<td width="2">:</td>
						<td>
							<span id="cara_penilaian"></span>
						</td>
					</tr>

                    <!-- variables -->
                    <table id="tabVariables" class="table table-lg tab-ins-modal" 
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
                                <th>Variable Penilaian</th>
                                <th>Nilai</th>
                                <!-- <th>Modify</th> -->
                            </tr>
                        </thead>
                        <tbody id="listVariable"></tbody>
                    </table>

				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>

	<script>

		// init periode
		$.ajax({
			url: '../json/periode/list-periode.php',
			type: 'get',
			success: function(res) {
				var periodeData = '';
				res.forEach(function(datas, key) {
					// periodeData += '<option value="'+datas[key].id_periode+'">'+datas[key].periode_title+'</option>';
				});
			}
		})

		$('#successMessage').hide();
		$('#errMessage').hide();

		$('#minus100').hide();
		$('#plus100').hide();

        // $('#kriteriaVariable').hide();

		var id = '';
        var addmore_count = 1;
		var kyu_id = '';
		var selectedPeriode = '';
        var kyu_id = '';
		var totalNilai = 0;
		var countID = [];

        // init enum
		var types = {
			'c1' : 'C-1',
			'c2' : 'C-2',
			'c3' : 'C-3',
			'c4' : 'C-4',
			'c5' : 'C-5',
			'c6' : 'C-6',
			'c7' : 'C-7',
		};

        var is_optional = {
            '0' : 'Inputan',
            '1' : 'Pilihan',
            // '2' : 'Nilai Real'
        }
            
        var is_optional_icon = {
            '0' : '<i class="fas fa-pen"></i>',
            '1' : '<i class="fas fa-clipboard-list"></i>'
        }

		$(document).ready(function() {
			
			$('select[name=kyu_select]').find('[value=""]').remove();
			$('select[name=kyu_select] option:first').attr('selected', true);
			$('input[name=kyu_id]').val($('#kyu_select :selected').val());
			$('#title_with_kyu').html($('#kyu_select :selected').text());

			kyu_id = $('#kyu_select :selected').val();
			refresh_data();

			$('select[name=kyu_select]').change(function() {
				kyu_id = $(this).val();
				refresh_data($('#kyu_select :selected').val());
				$('#title_with_kyu').html($('#kyu_select :selected').text());
				$('input[name=kyu_id]').val($(this).val());
			});

            get_periode();

            $('#periodeID').change(function() {
                selectedPeriode = $(this).val();
                refresh_data();
				$('input[name=periode]').val(selectedPeriode);
            });
			
			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				// reset
				$('input[name=is_edit]').val('');
				$('input[name=id_kriteria]').val('');
				$('#form-add')[0].reset();

				$('input[name=username]').removeAttr('disabled');
				$('#modalInputTitle').html('Tambah Kriteria');
				// $('#kriteriaVariable').hide();
				$('#tbodyVariable').html('');
			});

            $('select[name=cara_penilaian]').change(function() {
                // if($(this).val() == '1') {
                //     $('#kriteriaVariable').show();
                // } else {
                //     $('#kriteriaVariable').hide();
                // }
            });

            $('#addMore').click(function() {
                var new_input = '';
                addmore_count += 1;
                new_input +=    '<tr id="trVariable-'+addmore_count+'">'+
                                    '<td>#</td>'+
									'<td>'+
                                        '<input type="text" name="variable['+addmore_count+'][nama]" required>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="number" id="variable'+addmore_count+'" name="variable['+addmore_count+'][nilai]" onfocusout="calculateTotal('+addmore_count+')" required>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="text" name="variable['+addmore_count+'][urutan_order]">'+
                                    '</td>'+
                                    '<td>'+
                                        '<a href="#" class="btn btn-default" onclick="removeAddMore('+addmore_count+')"><i class="fas fa-trash"></i></a>'+
                                    '</td>'+
                                '</tr>';
				countID.push(addmore_count);
				$('#tbodyVariable').append(new_input);
            });

			$('#btn-edit').click(function() {
				totalNilai = 0;
				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					$.ajax({
						url: '../json/kriteria/single-kriteria.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#modalInputTitle').html('Ubah detail data user <b>' + res['kriteria'][0].nama + '</b>');

							$('input[name=id_kriteria]').val(res['kriteria'][0].id_kriteria);
							$('input[name=username]').attr('disabled', true);
							$('input[name=nama]').val(res['kriteria'][0].nama);
							$('select[name=type]').val(res['kriteria'][0].type).change();
							$('input[name=nama_title]').val(res['kriteria'][0].nama);
							$('input[name=bobot]').val(res['kriteria'][0].bobot);
							$('input[name=urutan_order]').val(res['kriteria'][0].urutan_order);
							$('select[name=cara_penilaian]').val(res['kriteria'][0].ada_pilihan).change();

							// if(res['kriteria'][0].ada_pilihan == '1' || res['kriteria'][0].ada_pilihan == '0') {
								$('#tbodyVariable').html('');
								
                                for (let index = 0; index < res['variables'].length; index++) {
									var new_input = '';
									addmore_count += 1;
									new_input +=    '<tr id="trVariable-'+addmore_count+'">'+
														'<td>'+parseInt(index+1)+'</td>'+
														'<input type="hidden" name="variable['+addmore_count+'][id]" value="'+res['variables'][index].id_pil_kriteria+'">'+
														'<td>'+
															'<input type="text" name="variable['+addmore_count+'][nama]" value="'+res['variables'][index].nama+'" required>'+
														'</td>'+
														'<td>'+
															'<input type="number" name="variable['+addmore_count+'][nilai]" value="'+res['variables'][index].nilai+'" required>'+
														'</td>'+
														'<td>'+
															'<input type="text" name="variable['+addmore_count+'][urutan_order]" value="'+res['variables'][index].urutan_order+'">'+
														'</td>'+
														'<td>'+
															'<a href="#" class="btn btn-default" onclick="removeAddMore('+addmore_count+')"><i class="fas fa-trash"></i></a>'+
														'</td>'+
													'</tr>';
									$('#tbodyVariable').append(new_input);
									totalNilai += Number(res['variables'][index].nilai);
								}
								$('input[name=inputVariable-total]').val(totalNilai);
								// if (totalNilai > 100) {
								// 	$('#submit').attr('disabled', true);
								// } else if (totalNilai < 100) {
								// 	$('#submit').attr('disabled', true);
								// } else {
								// 	$('#submit').removeAttr('disabled');
								// }
							// } else {
							// 	$('#tbodyVariable').html('');
							// }
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
						url: '../json/kriteria/single-kriteria.php?id='+id,
						type: 'get',
						success: function(res) {
							$('#nama_title').html(res['kriteria'][0].nama);
							$('#nama_kriteria').html(res['kriteria'][0].nama);
							$('#tipe').html(types[res['kriteria'][0].type]);
							$('#bobot').html(res['kriteria'][0].bobot);
							$('#cara_penilaian').html(is_optional[res['kriteria'][0].ada_pilihan]);
                            
                            // if (res['kriteria'][0].ada_pilihan == '1' || res['kriteria'][0].ada_pilihan == '0') {
                                $('#tabVariables').show();
                                $.ajax({
                                    url: '../json/kriteria/kriteria-variables.php?id='+id,
                                    type: 'get',
                                    success: function(res) {
                                        var data = '';
                                        if(res.length > 0) {
                                            res.forEach(function(datas, key) {
                                            data += '<tr>'+
                                                        '<td>'+parseInt(key+1)+'</td>'+
                                                        '<td>'+datas.nama+'</td>'+
                                                        '<td>'+datas.nilai+'</td>'+
														// '<td width="200">'+
														// 	'<span class="btn-modify"><i class="fas fa-pen"></i> Edit</span>'+
														// 	'<span class="btn-modify"><i class="fas fa-trash"></i> Delete</span>'+
														// '</td>'+
                                                    '</tr>';
                                            });
                                            $('#listVariable').html(data);
                                        } else {
                                            data += '<tr>'+
                                                        '<td class="td-empty" colspan="4">Belum ada data</td>'+
                                                    '</tr>';
                                            $('#listVariable').html(data);
                                        }
                                    }
                                })
                            // } else {
                            //     $('#tabVariables').hide();
                            // }
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
                            url: '../json/kriteria/delete-kriteria.php',
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
				if($('input[name=id_kriteria]').val() != '') {
					$('input[name=is_edit]').val('1');
				} else {
					$('input[name=is_edit]').val('0');
				}

				$.ajax({
					url: '../json/kriteria/save-kriteria.php',
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

			// $('#listData>tr').hover(function() {
			// 	$(this).css('background-color', '#ededed');
			// }, function() {
			// 	$(this).css('background-color', '#ffffff');
			// });

		}); // end of jquery

		function calculateTotal(idvariable) {
			countID.forEach(res => {
				totalNilai += Number($('#variable'+res).val());
			});
			$('input[name=inputVariable-total]').val(totalNilai);

				// if (totalNilai > 100) {
				// 	alert('Total Nilai harus kurang dari/sama dengan 100');
				// 	$('#submit').attr('disabled', true);
				// } else if (totalNilai < 100) {
				// 	alert('Total Nilai tidak boleh kurang dari 100');
				// 	$('#submit').attr('disabled', true);
				// } else {
				// 	$('#submit').removeAttr('disabled');
				// }
		}

		function get_periode() {
            $.ajax({
                url: '../json/periode/list-periode.php',
                type: 'get',
                success: function(res) {
                    var data = '';
                    res.forEach(function(datas, key) {
                        data += '<option value="'+datas.id_periode+'" ' + (key == 0 ? 'selected' : '') + '>'+datas.periode_title+'</option>';
                    });
                    $('#periodeID').html(data);
                    
                    selectedPeriode = $('#periodeID').val();
                    $('#title_with_periode').html($('#periodeID :selected').text());
					// $('input[name=kyu_id]').val($('#kyu_select :selected').val());
                    $('input[name=periode]').val(selectedPeriode);

                    refresh_data();
                }
            });

        }

		function refresh_data() {
			
			$.ajax({
				url: '../json/kriteria/list-kriteria.php',
				type: 'post',
                data: {
                    'kyu_id' : kyu_id,
					'periode': selectedPeriode
                },
				success: function(res) {
					var data = '';
					totalBobot = 0;
					if(res.length > 0) {
                        res.forEach(function(datas, key) {
						data += '<tr id="selectTR-'+datas.id_kriteria+'" onClick="selectTR('+datas.id_kriteria+')">'+
									'<td>'+parseInt(key+1)+'</td>'+
									'<td>'+datas.nama+'</td>'+
									'<td>'+types[datas.type]+'</td>'+
									'<td>'+datas.bobot+'</td>'+
									'<td>'+ is_optional[datas.ada_pilihan] +'</td>'+
								'</tr>';
						totalBobot += parseInt(datas.bobot);
						});
						if(totalBobot < 100) {
							$('#minus100').show();
							$('#btn-add').unbind('click');
							$('#btn-add').removeAttr("style", "pointer-events:none;cursor:not-allowed");
						} else if (totalBobot >= 100) {
							$('#minus100').hide();
							// $('#plus100').show();
							$('#btn-add').bind('click', false);
							$('#btn-add').attr("style", "pointer-events:none;cursor:not-allowed;background-color:#eee;");
						} else {
							$('#plus100').hide();
							$('#minus100').hide();
							$('#btn-add').unbind('click');
							$('#btn-add').removeAttr("style", "pointer-events:none;cursor:not-allowed;background-color:#eee;");
						}
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

		function selectTR(id_kriteria) {
			id = id_kriteria;
			
			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_kriteria).css('background-color', '#ededed');
		}

        function removeAddMore(number) {
            $('#trVariable-'+number).remove();
        }

	</script>
	
<?php
require_once('../template-parts/footer.php');