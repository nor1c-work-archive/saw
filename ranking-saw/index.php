<?php

	require_once('../includes/init.php');

	$judul_page = 'Perankingan Menggunakan Metode SAW';
	require_once('../template-parts/header.php');

?>
    
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>

	<div id="custom-full-container">
		<div>
			<select name="" id="periodeID" class="form-control"></select>
		</div>
	</div>
	<br>
	<div id="custom-full-container">
		
		<div id="modify">
			<ul>
				<?php require_once('../template-parts/kyu.php') ?>
			</ul>
		</div>

		<br><br>
		<h4><?php echo $judul_page; ?></h4>
        <br>
		
		<!-- STEP 1. Matriks Keputusan(X) ==================== -->		
		<h4>Step 1: Matriks Keputusan (X) [Merupakan Nilai dan Bobot Kenshi]</h4>
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
		<!-- END OF STEP 1 -->

		<br>
		<!-- STEP 2. Bobot Preferensi (W) ==================== -->
		<h4>Step 2: Bobot Preferensi (W)</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead id="listHeadBobot">
				<tr>
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot (W)</th>
				</tr>
			</thead>
			<tbody id="listDataBobot"></tbody>
		</table>
		<!-- END OF STEP 2 -->

		<br>
		
		<!-- Step 3: Matriks Ternormalisasi (R) ==================== -->
		<h4>Step 3: Matriks Ternormalisasi (R)</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead id="listHeadMatriks"></thead>
			<tbody id="listDataMatriks"></tbody>
		</table>
		<!-- END OF STEP 3 -->

		<br>
		<!-- Step 4: Perangkingan ==================== -->
		<h4>Step 4: Perangkingan (V)</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>					
				<tr>
                    <th width="30">No</th>
					<th class="super-top-left">Nama Peserta</th>
					<th>Ranking</th>
					<th>Hasil Kelulusan</th>
					<th style="width:30%">Hasil Evaluasi</th>
				</tr>
			</thead>
			<tbody id="listDataRanking"></tbody>
		</table>	

		<br>
		<!-- Step 4: Perangkingan ==================== -->
		<h4>Overall Chart</h4>		
        		
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>					
				<tr>
					<th>
                        <div id="resultChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </th>
				</tr>
			</thead>
		</table>
		<!-- END OF STEP 4 -->

        <?php if ($user_role == 'admin' || $user_role == 'pelatih') { ?>
            <br>
            <button id="proceedKelulusan" class="btn btn-primary"><i class="fas fa-user-ninja"></i>&nbsp; PROSES KELULUSAN</button>
            <button id="cetak" class="btn btn-info"><i class="fas fa-print"></i>&nbsp; CETAK</button>
        <?php } ?>
		
</div><br><br>

<script>

		$('#successMessage').hide();
		$('#errMessage').hide();
        $('#kriteriaVariable').hide();

		var id = '';
        var addmore_count = 1;
        var selectedPeriode = '';
        var kyu_id = '';
        var hasil = [];
        
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
			refresh_data_nilai(kyu_id);
			refresh_data_bobot();
			refresh_data_matriks();
			refresh_data_ranking();

            // refresh_head_evaluasi();
            // refresh_data_evaluasi();

			$('select[name=kyu_select]').change(function() {
				kyu_id = $(this).val();
				refresh_data_nilai($('#kyu_select :selected').val());
                refresh_header_nilai();
				refresh_data_bobot();
				refresh_data_matriks();
				refresh_data_ranking();

                // refresh_head_evaluasi();
                // refresh_data_evaluasi();
				$('#title_with_kyu').html($('#kyu_select :selected').text());
				$('input[name=kyu_id]').val($(this).val());
			});

            get_periode();

            $('#periodeID').change(function() {
                selectedPeriode = $(this).val();
                refresh_data_nilai(kyu_id);
                refresh_header_nilai();
				refresh_data_bobot();
				refresh_data_matriks();
				refresh_data_ranking();

                // refresh_head_evaluasi();
                // refresh_data_evaluasi();
            });

            $('#proceedKelulusan').click(function() {
                $.ajax({
                    url: '../json/seleksi/proceed-kelulusan.php',
                    type: 'post',
                    data: {
                        'kyu_id'    : kyu_id,
                        'periode'   : selectedPeriode,
                        'hasil'     : hasil,
                    },
                    success: function(res) {
                        alert(res.msg);
				        refresh_data_ranking();
                    }
                });
            });

            $('#cetak').click(function() {
                $.post({
                    url: '../json/seleksi/cetak-kelulusan.php',
                    type: 'post',
                    data: {
                        'kyu_id'    : kyu_id,
                        'periode'   : selectedPeriode,
                        'hasil'     : hasil,
                    },
                    success: function(res) {
                        printWindow = window.open('');
                        printWindow.document.write(res);
                        printWindow.print();
                    }
                });
            });
		});

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

                    refresh_header_nilai();
                    refresh_data_nilai();

					refresh_data_bobot();
					refresh_data_matriks();
					refresh_data_ranking();

                    // refresh_head_evaluasi();
                    // refresh_data_evaluasi();
                }
            });

        }

        function refresh_header_nilai() {
            $.ajax({
                url: '../json/seleksi/list-kriteria-for-ranking.php',
                type: 'post',
                data: {
                    'kyu_id'  : kyu_id,
                    'periode' : selectedPeriode
                },
                success: function(res) {
                    var data = '';
                    data    += '<tr>'+
                                    '<th rowspan="2" width="300" style="text-align:center;vertical-align:middle">Nama Peserta</th>'+
                                    '<th colspan="'+res.length+'" style="text-align:center">Kriteria</th>'+
                                '</tr>'+
                                '<tr>';
                    res.forEach(function(datas, key) {
                        data += '<th>'+datas.nama+'</th>';
                    });
                    data    += '</tr>';
                    $('#listHead').html(data);
                    $('#listHeadEvaluasi').html(data);
                    $('#listHeadMatriks').html(data);
                }
            });
        }

		function refresh_data_nilai() {

            $.ajax({
                url: '../json/seleksi/list-nilai-converted.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode
                },
                success: function(res) {
                    if(Object.keys(res).length > 0) {
                        var data = '';

                        for (var key in res) {
                            data += '<tr>'+
                                        '<td>'+res[key].nama_kenshin+'</td>';

                            // print nilai
                            for(var keys in res[key].nilai) {
                                if(res[key].nilai[keys].nilai === undefined) {
                                    data += '<td>0</td>';
                                } else {
                                    data += '<td>'+res[key].nilai[keys].nilai+'</td>';
                                }
                            }

                            data += '</tr>';
                        }
                    } else {
						var data =  '<tr>'+
										'<td class="td-empty" colspan="10">Belum ada data</td>'+
									'</tr>';
                    }

                    $('#listData').html(data);
                }
            });

		}

		function refresh_data_bobot() {

            $.ajax({
                url: '../json/kriteria/list-kriteria.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode
                },
                success: function(res) {
                    if(res.length > 0) {
                        var data = '';
                        res.forEach(function(datas, key) {
                            data += '<tr id="selectTR-'+datas.id_kriteria+'" onClick="selectTR('+datas.id_kriteria+')">'+
										'<td>'+datas.nama+'</td>'+
										'<td>'+types[datas.type]+'</td>'+
										'<td>'+datas.bobot+'</td>'+
									'</tr>';
                        });
                    } else {
						var data =  '<tr>'+
										'<td class="td-empty" colspan="10">Belum ada data</td>'+
									'</tr>';
                    }

                    $('#listDataBobot').html(data);
                }
            });

		}

		function refresh_data_matriks() {

            $.ajax({
                url: '../json/seleksi/list-matriks.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode
                },
                success: function(res) {
                    if(res.length > 0) {
                        var data = '';
                        res.forEach(function(datas, key) {
                            data += '<tr>'+
                                        '<td>'+datas.nama_kenshin+'</td>';

                            // print nilai
                            for(var keys in datas.nilai) {
                                if(datas.nilai[keys] === undefined) {
                                    data += '<td>0</td>';
                                } else {
                                    data += '<td>'+datas.nilai[keys]+'</td>';
                                }
                            }

                            data += '</tr>';
                        });
                    } else {
						var data =  '<tr>'+
										'<td class="td-empty" colspan="10">Belum ada data</td>'+
									'</tr>';
                    }

                    $('#listDataMatriks').html(data);
                }
            });

		}

		function refresh_data_ranking() {

            $.ajax({
                url: '../json/seleksi/list-ranking.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'periode'   : selectedPeriode
                },
                success: function(res) {
                    console.log(res);

                    hasil = res;
                    if(res.length > 0) {
                        var data = '';
                        no = 1;

                        var chartCategories = [];
                        var chartValues = [];

                        res.forEach(function(datas, key) {
                            chartCategories.push(datas.nama_kenshin);
                            chartValues.push(datas.nilai);
                            
                            data += '<tr>'+
                                        '<td>'+no+'</td>'+
                                        '<td>'+datas.nama_kenshin+'</td>'+
                                        '<td>'+datas.nilai+'</td>' +
                                        '<td>'+ (datas.nilai >= 70 ? 'Lulus' : 'Tidak Lulus') +'</td>' +
                                        '<td>'+(datas.terendah != undefined ? datas.terendah : '-')+'</td>'
                                    '</tr>';
                            
                            no++;
                        });

                        Highcharts.chart('resultChart', {
                            credits: {
                                enabled: false
                            },
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: ''
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
                                categories: chartCategories,
                                crosshair: true
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Nilai Ranking'
                                }
                            },
                            tooltip: {
                                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                                footerFormat: '</table>',
                                shared: true,
                                useHTML: true
                            },
                            plotOptions: {
                                column: {
                                    pointPadding: 0.2,
                                    borderWidth: 0
                                }
                            },
                            series: [{
                                name: 'Nilai Ranking',
                                data: chartValues
                            }]
                        });

                    } else {
                        var data =  '<tr>'+
                                        '<td class="td-empty" colspan="10">Belum ada data</td>'+
                                    '</tr>';
                    }

                    $('#listDataRanking').html(data);
                    
                    if(res[0].is_processed == '1') {
                        $('#proceedKelulusan').attr('disabled', true);
                    }
                }
            })

        }

</script>