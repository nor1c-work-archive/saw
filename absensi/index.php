<?php require_once('../includes/init.php'); ?>


	<?php
		$judul_page = 'Seleksi UKT';
		require_once('../template-parts/header.php');
	?>
	
	<div id="custom-full-container">
		<div>
			<select name="" id="periodeID" class="form-control"></select>
		</div>
	</div>
	<br>
	<div id="custom-full-container">
		
		<h4>Nilai per-Kriteria <span id="title_with_kyu"></span> - <span id="title_with_periode"></span></h4>

		<div id="successMessage" class="alert alert-success" role="alert"></div>
		<?php // require_once('../template-parts/action.php'); ?>
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

				<?php require_once('../template-parts/kyu.php') ?>
			</ul>
		</div>
		
		<br><br><br>
		<div id="filter">
			Filter by: <select name="searchType" id="searchType" class="form-control" style="display:inline-block;width:150px;">
				<option value="nama_kenshin">Nama</option>
			</select>
			<input type="text" name="searchKeyword" id="searchKeyword" value="" style="display:inline-block;width:200px;" class="form-control">
			<button class="btn btn-primary" id="filterBtn">Filter</button>
		</div>
		<hr>

		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500"
		>
			<thead id="dateOfMonth"></thead>
			<tbody id="listData"></tbody>
		</table>

		<!-- <button class="btn btn-primary" id="prev">PREV</button>
		<button class="btn btn-primary" id="next">NEXT</button> -->

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
						<input type="hidden" name="id_nilai">
						<input type="hidden" name="is_edit">
						<input type="hidden" name="periode" value="">
						<input type="hidden" name="kyu_id" value="">
						<tr id="trKenshin">
							<td>Peserta UKT<span class="required">*</span></td>
							<td>
                                <select id="list-kenshin" name="id_kenshin" class="form-control">
                            </td>
						</tr>
                        <tr id="trKenshin">
							<td>Tanggal<span class="required">*</span></td>
							<td>
                                <input type="text" name="tanggal" class='datepicker-here form-control' data-language='en' value="<?=date('Y-m-d')?>"/>
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

	<script>

		$('#successMessage').hide();
		$('#errMessage').hide();
        $('#kriteriaVariable').hide();

		var id = '';
        var addmore_count = 1;
        var selectedPeriode = '';
        var kyu_id = '';

		var dateOfMonth = [];
		var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

		var start = 0;
		var offset = 10;
        
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

			$('#prev').attr('disabled', true);

			$('#prev').click(function() {
				start-=10;
				offset-=10;
				refresh_data();

				if(start == 0) {
					$('#prev').attr('disabled', true);
				}
			});

			$('#next').click(function() {
				start+=10;
				offset+=10;
				refresh_data();
				$('#prev').removeAttr('disabled', 'disabled');
			});

            $('#btn-detail').hide();
            $('#btn-edit').hide();
			
			$('select[name=kyu_select]').find('[value=""]').remove();
			$('select[name=kyu_select] option:first').attr('selected', true);
			$('input[name=kyu_id]').val($('#kyu_select :selected').val());
			$('#title_with_kyu').html($('#kyu_select :selected').text());

			kyu_id = $('#kyu_select :selected').val();
			get_date_of_month();
			refresh_data(kyu_id);

			$('select[name=kyu_select]').change(function() {
				kyu_id = $(this).val();
				get_date_of_month();
				refresh_data($('#kyu_select :selected').val());
				$('#title_with_kyu').html($('#kyu_select :selected').text());
				$('input[name=kyu_id]').val($(this).val());

				dateOfMonth = [];
			});

            get_periode();

            $('#periodeID').change(function() {
                selectedPeriode = $(this).val();
				get_date_of_month();
                refresh_data(kyu_id);
            });

			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				// reset
				// $('input[name=is_edit]').val('');
				
				var startDate = '<?=date('Y-m-d', time());?>'.split('-');
				$('input[name=tanggal]').datepicker({
					language: 'en',
					minDate: new Date(startDate[0], parseInt(startDate[1]) - 1, parseInt(startDate[2])),
				});
                
				$('input[name=is_edit]').val('0');
				$('input[name=id_nilai]').val('');
				$('#form-add')[0].reset();

				$('input[name=username]').removeAttr('disabled');
				$('#modalInputTitle').html('Absen Peserta UKT');
				$('#kriteriaVariable').hide();
				$('#tbodyVariable').html('');

                $.ajax({
                    url: '../json/peserta/list-kenshin.php',
                    type: 'post',
                    data: {
                        kyu_id
                    },
                    success: function(res) {
                        var data = '';
                        if(res.length > 0) {
                            res.forEach(function(datas, key) {
                                data += '<option value="'+datas.id_kenshin+'">'+datas.nama_kenshin+'</option>';
                            });
                            $('#submit').removeAttr('disabled', true);
                        } else {
                            data += '<option selected disabled readonly>Tidak ada Peserta</option>';
                            $('#submit').attr('disabled', 'disabled');
                        }
                        $('#list-kenshin').html(data);
                    }
                });
            });
            
            $('select[name=cara_penilaian]').change(function() {
                if($(this).val() == '1') {
                    $('#kriteriaVariable').show();
                } else {
                    $('#kriteriaVariable').hide();
                }
            });

			$('#btn-edit').click(function() {
                
				$('input[name=is_edit]').val('1');
                $('#list-kenshin').attr('readonly', true);

				if(id == '') {
					alert('Silahkan pilih data terlebih dahulu!');
				} else {
					$.ajax({
						url: '../json/seleksi/single-nilai.php',
                        type: 'post',
                        data: {
                            id: id,
                            periode: selectedPeriode,
                            kyu_id: kyu_id
                        },
						success: function(res) {
                            
                            $.ajax({
                                url: '../json/peserta/list-kenshin.php',
                                success: function(res_kenshin) {
                                    var data = '';
                                    res_kenshin.forEach(function(datas, key) {
                                        data += '<option value="'+datas.id_kenshin+'" ' + (datas.id_kenshin == res.peserta[0].id_kenshin ? 'selected' : '') + '>'+datas.nama_kenshin+'</option>';
                                    });
                                    $('#list-kenshin').html(data);
                                }
                            });
							$('#modalInputTitle').html('Ubah detail data nilai Peserta <b>' + res.peserta[0].nama_kenshin + '</b>');

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
                            url: '../json/absensi/delete-absensi.php',
                            type: 'post',
                            data: {
                                id,
                                'periode': selectedPeriode,
                                'kyu_id' : kyu_id
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

            $('#list-kenshin').change(function() {
                $('select[name=id_kenshin] :selected').val();
            });

			$('#submit').click(function(e) {
				$('#successMessage').html('');
				$('#errMessage').html('');

				e.preventDefault();

				$.ajax({
					url: '../json/absensi/save-absensi.php',
					type: 'post',
					data: $('#addModalBody').find("select, textarea, input").serialize(),
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

			$('#filterBtn').click(function() {
				refresh_data($('#searchType').val(), $('#searchKeyword').val());
			});

		}); // end of jquery

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
                    $('input[name=periode]').val(selectedPeriode);

					get_date_of_month();
                    refresh_data();
                }
            });

        }

		function refresh_data(searchType, searchKeyword) {

            $.ajax({
                url: '../json/absensi/list-absensi.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
					'periode'   : selectedPeriode,
					searchType, 
					searchKeyword,
					start, 
					offset
                },
                success: function(res) {
					data = '';
					no = 1;
					for(var key in res) {
						data += '<tr id="selectTR-'+res[key].id_kenshin+'" onClick="selectTR('+res[key].id_kenshin+')">'+
                                        '<td>'+no+'</td>'+
										'<td>'+res[key].nama+'</td>';

							totalAttendance = 0;
							dateOfMonth.forEach(date => {
								if(res[key].tanggal.indexOf(date) >= 0) {
									data += '<td style="text-align:center"><i style="color:green" class="fas fa-check"></i></td>';
									totalAttendance++;
								} else {
									data += '<td style="text-align:center"><i style="color:red" class="fas fa-close"></i></td>';
								}
							});
						
						data += '<td style="text-align:center">'+totalAttendance+'</td>';
						data += '</tr>';
						no++;
					}
					
                    $('#listData').html(data);
                }
            });

		}

		function get_date_of_month() {

			$.ajax({
				url: '../json/absensi/get-date-of-month.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode
                },
				success: function(res) {
					th = '';
					
					th += 	'<tr>'+
								'<th rowspan="2" style="vertical-align:middle;text-align:center;" width="50">No</th>'+
								'<th rowspan="2" style="vertical-align:middle;text-align:center;" width="250">Nama Peserta</th>';
					
					for (var key in res.month) {
						th += '<th colspan="'+res.month[key].length+'" style="text-align:center">'+months[parseInt(key-1, 10)]+'</th>';
					}

					th +=		'<th rowspan="2" style="vertical-align:middle;text-align:center;" width="100" style="text-align:center;">Attendance Total</th>'+
							'</tr>';

					th += '<tr>';
					for (let index = 0; index < res.date.length; index++) {
						th += '<th style="text-align:center">'+res.date[index].tanggal.split('-')[2]+'</th>';
						dateOfMonth.push(res.date[index].tanggal);
					}
					th += '</tr>';

					$('#dateOfMonth').html(th);
				}
			});

		}

		function selectTR(id_absensi) {
			id = id_absensi;
			
			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_absensi).css('background-color', '#ededed');
		}

        function removeAddMore(number) {
            $('#trVariable-'+number).remove();
        }

    </script> 
    
<?php
require_once('../template-parts/footer.php');