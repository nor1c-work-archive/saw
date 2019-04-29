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
		<?php require_once('../template-parts/action.php'); ?>
        
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
			<thead id="listHead"></thead>
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

                        <table id="nilaiVariable" class="table table-lg table-hover tab-ins-modal" 
                            data-show-columns="true"
                            data-search="true"
                            data-show-toggle="true"
                            data-pagination="true"
                            data-resizable="true"
                            data-height="500"
                        >
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
		<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
						<td width="150">Nama Peserta</td>
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
						<td width="150">Jurusan</td>
						<td width="2">:</td>
						<td>
							<span id="jurusan"></span>
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

                    <!-- variables -->
                    <table id="tabVariables" class="table table-lg tab-ins-modal" 
                        data-show-columns="true"
                        data-search="true"
                        data-show-toggle="true"
                        data-pagination="true"
                        data-resizable="true"
                        data-height="500"
                    >
                        <thead id="listVariableHead"></thead>
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

		$('#successMessage').hide();
		$('#errMessage').hide();
        $('#kriteriaVariable').hide();

		var id = '';
        var addmore_count = 1;
        var selectedPeriode = '';
        var kyu_id = '';
        
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
			
			$('select[name=kyu_select]').find('[value=""]').remove();
			$('select[name=kyu_select] option:first').attr('selected', true);
			$('input[name=kyu_id]').val($('#kyu_select :selected').val());
			$('#title_with_kyu').html($('#kyu_select :selected').text());

			kyu_id = $('#kyu_select :selected').val();
			refresh_data(kyu_id);

			$('select[name=kyu_select]').change(function() {
				kyu_id = $(this).val();
				refresh_data($('#kyu_select :selected').val());
                refresh_header();
				$('#title_with_kyu').html($('#kyu_select :selected').text());
				$('input[name=kyu_id]').val($(this).val());
			});

            get_periode();

            $('#periodeID').change(function() {
                selectedPeriode = $(this).val();
                $('input[name=periode]').val(selectedPeriode);
                refresh_data(kyu_id);
                refresh_header();
            });

            $('#list-kenshin').change(function() {
                count_absensi();
            });

			$('#table tbody>tr').click(function() {
				$('#table tbody>tr').removeClass('tr_highlight');
				$(this).toggleClass('tr_highlight');
			});

			$('#btn-add').click(function() {
				// reset
				// $('input[name=is_edit]').val('');
                
				$('input[name=is_edit]').val('0');
				$('input[name=id_nilai]').val('');
				$('#form-add')[0].reset();

				$('input[name=username]').removeAttr('disabled');
				$('#modalInputTitle').html('Tambah Nilai Peserta UKT');
				$('#kriteriaVariable').hide();
				$('#tbodyVariable').html('');

                $.ajax({
                    url: '../json/seleksi/list-kenshin-for-seleksi.php',
                    type: 'post',
                    data: {
                        kyu_id,
                        'periode' : selectedPeriode
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

                $.ajax({
                    url: '../json/kriteria/list-kriteria.php',
                    type: 'post',
                    data: {
                        'kyu_id'  : kyu_id,
                        'periode' : selectedPeriode
                    },
                    success: function(res) {
                        var data_tab = '';
                        data_tab    += '<thead>'+
                                            '<tr>'+
                                                '<th colspan="2" style="text-align:center">Nilai Kriteria</th>'+
                                            '</tr>'+
                                        '</thead>';
                                        '<tbody>';

                        if(res.length > 0) {
                            res.forEach(function(datas, key) {
                                if(datas.nama.toLowerCase().includes("absensi")) {
                                    data_tab += '<tr>'+
                                                    '<td>'+datas.nama+'</td>'+
                                                    '<td>';
                                                    
                                    count_absensi();
                                    data_tab += '<input type="number" id="nilai_absensi" name="kriteria['+datas.id_kriteria+']"></td>'
                                        
                                    data_tab += '</td></tr>';
                                } else {
                                    if(datas.ada_pilihan == '1') {
                                        data_tab += '<tr>'+
                                                        '<td>'+datas.nama+'</td>'+
                                                        '<td>'+
                                                            '<select name="kriteria['+datas.id_kriteria+']" class="form-control">';
                                            $.ajax({
                                                url: '../json/kriteria/kriteria-variables.php',
                                                type: 'get',
                                                data: {
                                                    id: datas.id_kriteria
                                                },
                                                success: function(res_pilihan) {
                                                    res_pilihan.forEach(function(datas_var, key_var) {
                                                        data_tab += '<option value="'+datas_var.nilai+'">'+datas_var.nama+'</option>';
                                                    });
                                                },
                                                async: false,
                                            });
                                        
                                        data_tab +=         '</select>'+
                                                        '</td>'+
                                                    '</tr>';
                                    } else {
                                        data_tab += '<tr>'+
                                                        '<td>'+datas.nama+'</td>'+
                                                        '<td><input type="number" name="kriteria['+datas.id_kriteria+']"></td>'+
                                                    '</tr>';
                                    }
                                }
                            });
                        } else {
                            data_tab += '<tr>'+
                                            '<td class="td-empty" colspan="10">Belum ada data</td>'+
                                        '</tr>';
                        }
                        
                        data_tab    += '</tbody>';
                        $('#nilaiVariable').html(data_tab);
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

            $('#addMore').click(function() {
                var new_input = '';
                addmore_count += 1;
                new_input +=    '<tr id="trVariable-'+addmore_count+'">'+
                                    '<td>#</td>'+
									'<td>'+
                                        '<input type="text" name="variable['+addmore_count+'][nama]" required>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="number" name="variable['+addmore_count+'][nilai]" required>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="text" name="variable['+addmore_count+'][urutan_order]">'+
                                    '</td>'+
                                    '<td>'+
                                        '<a href="#" class="btn btn-default" onclick="removeAddMore('+addmore_count+')"><i class="fas fa-trash"></i></a>'+
                                    '</td>'+
                                '</tr>';
				$('#tbodyVariable').append(new_input);
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
                            
                            $.ajax({
                                url: '../json/seleksi/list-kriteria-by-nilai.php',
                                type: 'post',
                                data: {
                                    'kyu_id'  : kyu_id,
                                    'periode' : selectedPeriode,
                                    'id_kenshin' : res.peserta[0].id_kenshin
                                },
                                success: function(res) {
                                    var data_tab = '';
                                    data_tab    += '<thead>'+
                                                        '<tr>'+
                                                            '<th colspan="2" style="text-align:center">Nilai Kriteria</th>'+
                                                        '</tr>'+
                                                    '</thead>';
                                                    '<tbody>';

                                    res.forEach(function(datas, key) {
                                        if(datas.nama.toLowerCase().includes("absensi")) {
                                            data_tab += '<tr>'+
                                                            '<td>'+datas.nama+'</td>'+
                                                            '<td>';
                                                            
                                            count_absensi();
                                            data_tab += '<input type="number" id="nilai_absensi" name="kriteria['+datas.id_kriteria+']"></td>'
                                                
                                            data_tab += '</td></tr>';
                                        } else {
                                            if(datas.ada_pilihan == '1') {
                                                data_tab += '<tr>'+
                                                                '<td>'+datas.nama+'</td>'+
                                                                '<td>'+
                                                                    '<select name="kriteria['+datas.id_kriteria+']" class="form-control">';
                                                    $.ajax({
                                                        url: '../json/kriteria/kriteria-variables.php',
                                                        type: 'get',
                                                        data: {
                                                            id: datas.id_kriteria
                                                        },
                                                        success: function(res_pilihan) {
                                                            res_pilihan.forEach(function(datas_var, key_var) {
                                                                if(datas_var.id_kriteria == '39') {
                                                                    console.log(datas.nilai);
                                                                }
                                                                data_tab += '<option value="'+datas_var.nilai+'" ' + (datas.nilai == datas_var.nilai ? 'selected' : '') + '>'+datas_var.nama+'</option>';
                                                            });
                                                        },
                                                        async: false,
                                                    });
                                                
                                                data_tab +=         '</select>'+
                                                                '</td>'+
                                                            '</tr>';
                                            } else {
                                                data_tab += '<tr>'+
                                                                '<td>'+datas.nama+'</td>'+
                                                                '<td><input type="number" name="kriteria['+datas.id_kriteria+']" value="'+datas.nilai+'"></td>'+
                                                            '</tr>';
                                            }
                                        }
                                        
                                    });
                                    
                                    data_tab    += '</tbody>';
                                    $('#nilaiVariable').html(data_tab);
                                }
                            });

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
						url: '../json/seleksi/single-nilai.php',
						type: 'post',
                        data: {
                            id: id,
                            periode: selectedPeriode,
                            kyu_id: kyu_id,
                        },
						success: function(res) {
							$('#nama_title').html(res['peserta'][0].nama_kenshin);
							$('#nama').html(res['peserta'][0].nama_kenshin);
							$('#nim').html(res['peserta'][0].nim);
							$('#nik').html(res['peserta'][0].nik);
							$('#jurusan').html(res['peserta'][0].jurusan);
							$('#email').html(res['peserta'][0].email);
							$('#hp').html(res['peserta'][0].hp);
							$('#alamat').html(res['peserta'][0].alamat);
                            
                            // init kriteria
                            $.ajax({
                                url: '../json/kriteria/list-kriteria',
                                type: 'post',
                                data: {
                                    'kyu_id'  : kyu_id,
                                    'periode' : selectedPeriode
                                },
                                success: function(res) {
                                    var data = '';
                                    data    += '<tr>';
                                    res.forEach(function(datas, key) {
                                        data += '<th>'+datas.nama+'</th>';
                                    });
                                    data    += '</tr>';
                                    $('#listVariableHead').html(data);
                                }
                            });

                            $.ajax({
                                url: '../json/seleksi/list-nilai.php',
                                type: 'post',
                                data: {
                                    id: id,
                                    kyu_id: kyu_id,
                                    periode: selectedPeriode,
                                },
                                success: function(res) {
                                    var data = '';
                                    data    += '<tr>';
                                    res.forEach(function(datas, key) {
                                        // print nilai
                                        for(var keys in datas.nilai) {
                                            if(datas.nilai[keys].nilai === undefined) {
                                                data += '<td>0</td>';
                                            } else {
                                                data += '<td>'+datas.nilai[keys].nilai+'</td>';
                                            }
                                        }
                                    });
                                    data    += '</tr>';
                                    $('#listVariable').html(data);
                                }
                            })
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
                            url: '../json/seleksi/delete-nilai.php',
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
				// if($('input[name=id_nilai]').val() != '') {
				// 	$('input[name=is_edit]').val('1');
				// } else {
				// 	$('input[name=is_edit]').val('0');
				// }

				$.ajax({
					url: '../json/seleksi/save-nilai.php',
					type: 'post',
					data: $('#addModalBody').find("select, textarea, input").serialize(),
					success: function(res) {
						if(!res.error) {
							$('#addModal').modal('hide');
							$('#successMessage').show();
							$('#successMessage').html(res.msg);
							refresh_data();
                            refresh_header();
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

			// $('#listData>tr').hover(function() {
			// 	$(this).css('background-color', '#ededed');
			// }, function() {
			// 	$(this).css('background-color', '#ffffff');
			// });

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

                    refresh_header();
                    refresh_data();
                }
            });

        }

		function refresh_data(searchType, searchKeyword) {

            $.ajax({
                url: '../json/seleksi/list-nilai.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode,
                    searchType, 
                    searchKeyword
                },
                success: function(res) {
                    if(res.length > 0) {
                        var data = '';
                        res.forEach(function(datas, key) {
                            data += '<tr id="selectTR-'+datas.id_kenshin+'" onClick="selectTR('+datas.id_kenshin+')">'+
                                        '<td>'+datas.nama_kenshin+'</td>';

                            // print nilai
                            for(var keys in datas.nilai) {
                                if(datas.nilai[keys].nilai === undefined) {
                                    data += '<td>0</td>';
                                } else {
                                    data += '<td>'+datas.nilai[keys].nilai+'</td>';
                                }
                            }

                            data += '</tr>';
                        });
                    } else {
						var data =  '<tr>'+
										'<td class="td-empty" colspan="10">Belum ada data</td>'+
									'</tr>';
                    }

                    $('#listData').html(data);
                }
            });

		}

        function refresh_header() {
            $.ajax({
                url: '../json/kriteria/list-kriteria.php',
                type: 'post',
                data: {
                    'kyu_id'  : kyu_id,
                    'periode' : selectedPeriode
                },
                success: function(res) {
                    var data = '';
                    data    += '<tr>'+
                                    '<th rowspan="2" width="300" style="text-align:center;vertical-align:middle">Nama Kenshin</th>'+
                                    '<th colspan="'+res.length+'" style="text-align:center">Kriteria</th>'+
                                '</tr>'+
                                '<tr>';
                    res.forEach(function(datas, key) {
                        data += '<th>'+datas.nama+'</th>';
                    });
                    data    += '</tr>';
                    $('#listHead').html(data);
                }
            });
        }

		function selectTR(id_kriteria) {
			id = id_kriteria;
			
			$('tbody tr').css('background-color', '#ffffff');
			$('#selectTR-'+id_kriteria).css('background-color', '#ededed');
		}

        function removeAddMore(number) {
            $('#trVariable-'+number).remove();
        }

        function count_absensi() {
            $.ajax({
                url: '../json/seleksi/if-absensi.php',
                type: 'post',
                data: {
                    'id_kenshin': $('#list-kenshin').val(),
                    kyu_id,
                    'periode': selectedPeriode
                },
                success: function(res) {
                    if(res) {
                        $('#nilai_absensi').val(res.length);
                    } else {
                        $('#nilai_absensi').val('0');
                    }
                }
            });
        }

    </script> 
    
<?php
require_once('../template-parts/footer.php');