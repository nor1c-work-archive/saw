<?php
	require_once('../includes/init.php');
	$judul_page = 'Grafik Kelulusan Tahunan';
	require_once('../template-parts/header.php');
?>
    
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

    <!-- <div id="custom-full-container">
		<div>
			<select name="" id="periodeID" class="form-control"></select>
		</div>
	</div> -->
	<br>
	<div id="custom-full-container">
        <div id="modify">
			<ul>
                <?php
                    $year = $pdo->prepare('SELECT start_date
                                           FROM periode
                                           GROUP BY DATE_FORMAT(start_date, "%Y")
                                           ORDER BY start_date DESC');
                    $year->execute();			
                    $year = $year->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <li style="float:right">
                    <select name="year" id="year" class="form-control">
                        <?php
                            foreach ($year as $key => $value) {
                                echo '<option value="'.date('Y', strtotime($value['start_date'])).'">'.date('Y', strtotime($value['start_date'])).'</option>';
                            }
                        ?>
                    </select>
                </li>
				<?php require_once('../template-parts/kyu.php') ?>
			</ul>
		</div>
        <br><br>
        <h4>GRAFIK KELULUSAN TAHUNAN</h4>
        <br>
        <div id="resultChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>


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
			refresh_data_ranking();

			$('select[name=kyu_select]').change(function() {
				kyu_id = $(this).val();
				refresh_data_ranking();
				$('#title_with_kyu').html($('#kyu_select :selected').text());
				$('input[name=kyu_id]').val($(this).val());
			});

            $('#periodeID').change(function() {
                selectedPeriode = $(this).val();
				refresh_data_ranking();
            });

            $('#year').change(function() {
                refresh_data_ranking();
            });
		});

		function refresh_data_ranking() {

            var periodeCategories = [];
            var valueOfCategories = [];

            $.ajax({
                url: '../json/graphic/list-periode-of-year.php',
                type: 'post',
                data: {
                    'kyu_id'    : kyu_id,
                    'year'      : $('#year').val()
                },
                async: false,
                success: function(res) {
                    console.log(res);

                    var chartCategories = [];
                    var chartValues = {
                        totalKenshi: [],
                        L: [],
                        tL: []
                    };

                    no = 1;
                    for (var periode in res) {
                        chartCategories.push(periode);

                        chartValues.totalKenshi[no]    = res[periode].totalKenshi;
                        chartValues.L[no]              = res[periode].L;
                        chartValues.tL[no]             = res[periode].tL;
                        no++;
                    }

                    periodeCategories = chartCategories;
                    valueOfCategories = chartValues;

                }
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
                    categories: periodeCategories,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'jumlah'
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
                        pointPadding: 0,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Total',
                    data: valueOfCategories.totalKenshi.slice(1),
                }, {
                    name: 'Lulus',
                    data: valueOfCategories.L.slice(1),
                }, {
                    name: 'Tidak Lulus',
                    data: valueOfCategories.tL.slice(1),
                }],
                colors: ['#575e5b', '#42ce91', '#ad5d5d'],
            });
            }

</script>


<!-- CATEGORIES : [ Periode 1, Periode 2, Periode 3, Periode 4 ]

                                      [ p1, p2, p3, p4 ]
SERIES     : [ Total Kenshi yg ikut : [ xx, xx, xx, xx ] ]
             [ Total Lulus          : [ xx, xx, xx, xx ] ]
             [ Total Tidak Lulus    : [ xx, xx, xx, xx ] ] -->

