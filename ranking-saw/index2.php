<?php
/* ---------------------------------------------
 * SPK SAW
 * Author: Wawan Edan
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
// echo error_reporting(0);
require_once('../includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode SAW';
require_once('../template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria 
	WHERE kyu_id = ' . $_GET['kyu'] . '
	ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua kenshin (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_kenshin, nama_kenshin FROM kenshin WHERE kyu_id = ' . $_GET['kyu']);
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$kenshins = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
$list_kriteria = array();
foreach($kriterias as $kriteria):
	$list_kriteria[$kriteria['id_kriteria']] = $kriteria;
	foreach($kenshins as $kenshin):
		
		$id_kenshin = $kenshin['id_kenshin'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		// Fetch nilai dari db
		$query3 = $pdo->prepare('SELECT nilai FROM nilai_kenshin
			WHERE id_kenshin = :id_kenshin AND id_kriteria = :id_kriteria');
		$query3->execute(array(
			'id_kenshin' => $id_kenshin,
			'id_kriteria' => $id_kriteria,	
		));			
		$query3->setFetchMode(PDO::FETCH_ASSOC);
		if($nilai_kenshin = $query3->fetch()) {
			// Jika ada nilai kriterianya
			$matriks_x[$id_kriteria][$id_kenshin] = $nilai_kenshin['nilai'];
		} else {			
			$matriks_x[$id_kriteria][$id_kenshin] = 0;
		}

	endforeach;
endforeach;

/* >>> STEP 3 ===================================
 * Matriks Ternormalisasi (R)
 * ------------------------------------------- */
$matriks_r = array();
foreach($matriks_x as $id_kriteria => $nilai_kenshins):
	
	$tipe = $list_kriteria[$id_kriteria]['type'];
	foreach($nilai_kenshins as $id_alternatif => $nilai) {
		if($tipe == 'c1') {
			$nilai_normal = $nilai / max($nilai_kenshins);
		} elseif($tipe == 'c2') {
			// $nilai_normal = min($nilai_kenshins) / $nilai;
		} elseif($tipe == 'c3') {
			$nilai_normal = min($nilai_kenshins) / $nilai;
		} elseif($tipe == 'c4') {
			$nilai_normal = min($nilai_kenshins) / $nilai;
		} elseif($tipe == 'c5') {
			$nilai_normal = min($nilai_kenshins) / $nilai;
		}
		
		$matriks_r[$id_kriteria][$id_alternatif] = $nilai_normal;
	}
	
endforeach;


/* >>> STEP 4 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($kenshins as $kenshin):

	$total_nilai = 0;
	foreach($list_kriteria as $kriteria) {
	
		$bobot = $kriteria['bobot'];
		$id_kenshin = $kenshin['id_kenshin'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_kenshin];
		$total_nilai = $total_nilai + ($bobot * $nilai_r);

	}
	
	$ranks[$kenshin['id_kenshin']]['id_kenshin'] = $kenshin['id_kenshin'];
	$ranks[$kenshin['id_kenshin']]['nama_kenshin'] = $kenshin['nama_kenshin'];
	$ranks[$kenshin['id_kenshin']]['nilai'] = $total_nilai;
	
endforeach;
 
?>


<div id="custom-full-container">
		
		<div id="modify">
			<?php require_once('../template-parts/kyu.php') ?>
		</div>
		
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
            data-height="500">
			<thead>
				<tr class="super-top">
					<th style="text-align:center;vertical-align:middle" rowspan="2" class="super-top-left">Nama Kenshi</th>
					<th style="text-align:center;vertical-align:middle" colspan="<?php echo count($kriterias); ?>">Nilai per-Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($kenshins as $kenshin): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<?php			
						foreach($kriterias as $kriteria):
							$id_kenshin = $kenshin['id_kenshin'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_kenshin];
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
        
        
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
			<thead>
				<tr>
					<th>Nama Kriteria</th>
					<th>Type</th>
					<th>Bobot (W)</th>						
				</tr>
			</thead>
			<tbody>
				<?php foreach($kriterias as $hasil): ?>
					<tr>
						<td><?php echo $hasil['nama']; ?></td>
						<td>
						<?php
						if($hasil['type'] == 'c1') {
							echo 'C-1';
						} elseif($hasil['type'] == 'c2') {
							echo 'C-2';
						} elseif($hasil['type'] == 'c3') {
							echo 'C-3';
						} elseif($hasil['type'] == 'c4') {
							echo 'C-4';
						} elseif($hasil['type'] == 'c5') {
							echo 'C-5';
						}							
						?>
						</td>
						<td><?php echo $hasil['bobot']; ?></td>							
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
        
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
			<thead>
				<tr class="super-top">
					<th style="text-align:center;vertical-align:middle" rowspan="2" class="super-top-left">Nama Kenshin</th>
					<th style="text-align:center;vertical-align:middle" colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($kenshins as $kenshin): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_kenshin = $kenshin['id_kenshin'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo round($matriks_r[$id_kriteria][$id_kenshin], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>		
		
		
		<br>
		<!-- Step 4: Perangkingan ==================== -->
		<?php		
		$sorted_ranks = $ranks;		
		// Sorting
		if(function_exists('array_multisort')):
			$nama_kenshin = array();
			$nilai = array();
			foreach ($sorted_ranks as $key => $row) {
				$nama_kenshin[$key]  = $row['nama_kenshin'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nama_kenshin, SORT_ASC, $sorted_ranks);
		endif;
		?>		
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
					<th class="super-top-left">Nama Kenshin</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sorted_ranks as $kenshin ): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<td><?php echo round($kenshin['nilai'], $digit); ?></td>											
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>			
		
</div><!-- .main-content-row -->
<br><br>

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
                refresh_data(kyu_id);
            });
		});

</script>