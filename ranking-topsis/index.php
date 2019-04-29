<?php
/* ---------------------------------------------
 * SPK FUZZY TOPSIS
 * Author: Wawan Edan
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
error_reporting(0);
require_once('../includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode Fuzzy TOPSIS';
require_once('../template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot
	FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();

/* ---------------------------------------------
 * Fetch semua kenshin (alternatif)
 * ------------------------------------------- */
$query2 = $pdo->prepare('SELECT id_kenshin, nama_kenshin FROM kenshin');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$kenshins = $query2->fetchAll();


/* >>> STEP 1 ===================================
 * Matrix Keputusan (X)
 * ------------------------------------------- */
$matriks_x = array();
foreach($kriterias as $kriteria):
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
	
	// Mencari akar dari penjumlahan kuadrat
	$jumlah_kuadrat = 0;
	foreach($nilai_kenshins as $nilai_kenshin):
		$jumlah_kuadrat += pow($nilai_kenshin, 2);
	endforeach;
	$akar_kuadrat = sqrt($jumlah_kuadrat);
	
	// Mencari hasil bagi akar kuadrat
	// Lalu dimasukkan ke array $matriks_r
	foreach($nilai_kenshins as $id_kenshin => $nilai_kenshin):
		$matriks_r[$id_kriteria][$id_kenshin] = $nilai_kenshin / $akar_kuadrat;
	endforeach;
	
endforeach;


/* >>> STEP 4 ===================================
 * Matriks Y
 * ------------------------------------------- */
$matriks_y = array();
foreach($kriterias as $kriteria):
	foreach($kenshins as $kenshin):
		
		$bobot = $kriteria['bobot'];
		$id_kenshin = $kenshin['id_kenshin'];
		$id_kriteria = $kriteria['id_kriteria'];
		
		$nilai_r = $matriks_r[$id_kriteria][$id_kenshin];
		$matriks_y[$id_kriteria][$id_kenshin] = $bobot * $nilai_r;

	endforeach;
endforeach;


/* >>> STEP 5 ================================
 * Solusi Ideal Positif & Negarif
 * ------------------------------------------- */
$solusi_ideal_positif = array();
$solusi_ideal_negatif = array();
foreach($kriterias as $kriteria):

	$id_kriteria = $kriteria['id_kriteria'];
	$type_kriteria = $kriteria['type'];
	
	$nilai_max = max($matriks_y[$id_kriteria]);
	$nilai_min = min($matriks_y[$id_kriteria]);
	
	if($type_kriteria == 'c1'):
		$s_i_p = $nilai_max;
		$s_i_n = $nilai_min;
	elseif($type_kriteria == 'c2'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
    elseif($type_kriteria == 'c3'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
    elseif($type_kriteria == 'c4'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
    elseif($type_kriteria == 'c5'):
		$s_i_p = $nilai_min;
		$s_i_n = $nilai_max;
	endif;
	
	$solusi_ideal_positif[$id_kriteria] = $s_i_p;
	$solusi_ideal_negatif[$id_kriteria] = $s_i_n;

endforeach;


/* >>> STEP 6 ================================
 * Jarak Ideal Positif & Negatif
 * ------------------------------------------- */
$jarak_ideal_positif = array();
$jarak_ideal_negatif = array();
foreach($kenshins as $kenshin):

	$id_kenshin = $kenshin['id_kenshin'];		
	$jumlah_kuadrat_jip = 0;
	$jumlah_kuadrat_jin = 0;
	
	// Mencari penjumlahan kuadrat
	foreach($matriks_y as $id_kriteria => $nilai_kenshins):
		
		$hsl_pengurangan_jip = $nilai_kenshins[$id_kenshin] - $solusi_ideal_positif[$id_kriteria];
		$hsl_pengurangan_jin = $nilai_kenshins[$id_kenshin] - $solusi_ideal_negatif[$id_kriteria];
		
		$jumlah_kuadrat_jip += pow($hsl_pengurangan_jip, 2);
		$jumlah_kuadrat_jin += pow($hsl_pengurangan_jin, 2);
	
	endforeach;
	
	// Mengakarkan hasil penjumlahan kuadrat
	$akar_kuadrat_jip = sqrt($jumlah_kuadrat_jip);
	$akar_kuadrat_jin = sqrt($jumlah_kuadrat_jin);
	
	// Memasukkan ke array matriks jip & jin
	$jarak_ideal_positif[$id_kenshin] = $akar_kuadrat_jip;
	$jarak_ideal_negatif[$id_kenshin] = $akar_kuadrat_jin;
	
endforeach;


/* >>> STEP 7 ================================
 * Perangkingan
 * ------------------------------------------- */
$ranks = array();
foreach($kenshins as $kenshin):

	$s_negatif = $jarak_ideal_negatif[$kenshin['id_kenshin']];
	$s_positif = $jarak_ideal_positif[$kenshin['id_kenshin']];	
	
	$nilai_v = $s_negatif / ($s_positif + $s_negatif);
	
	$ranks[$kenshin['id_kenshin']]['id_kenshin'] = $kenshin['id_kenshin'];
	$ranks[$kenshin['id_kenshin']]['nama_kenshin'] = $kenshin['nama_kenshin'];
	$ranks[$kenshin['id_kenshin']]['nilai'] = $nilai_v;
	
endforeach;
 
?>

<div id="custom-full-container">
		
		<h4><?php echo $judul_page; ?></h4>
        <br>
		
		<!-- STEP 1. Matriks Keputusan(X) ==================== -->		
		<h4>Step 1: Matriks Keputusan (X)</h4>
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Kenshin</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
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
					<th rowspan="2" class="super-top-left">Nama Kenshin</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
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
		
		
		<!-- Step 4: Matriks Y ==================== -->
		<h4>Step 4: Matriks Y</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>
				<tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama Kenshin</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
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
							echo round($matriks_y[$id_kriteria][$id_kenshin], $digit);
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>	
			</tbody>
		</table>	
		
		
		<!-- Step 5.1: Solusi Ideal Positif ==================== -->
		<h4>Step 5.1: Solusi Ideal Positif (A<sup>+</sup>)</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>					
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_positif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>
		
		<!-- Step 5.2: Solusi Ideal negative ==================== -->
		<h4>Step 5.2: Solusi Ideal Negatif (A<sup>-</sup>)</h4>			
		<table id="table" class="table table-lg table-hover table-bordered" 
			data-show-columns="true"
            data-search="true"
            data-show-toggle="true"
            data-pagination="true"
            data-resizable="true"
            data-height="500">
			<thead>					
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<td>
							<?php
							$id_kriteria = $kriteria['id_kriteria'];							
							echo round($solusi_ideal_negatif[$id_kriteria], $digit);
							?>
						</td>
					<?php endforeach; ?>
				</tr>					
			</tbody>
		</table>		
		
		<!-- Step 6.1: Jarak Ideal Positif ==================== -->
		<h4>Step 6.1: Jarak Ideal Positif (S<sub>i</sub>+)</h4>			
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
					<th>Jarak Ideal Positif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($kenshins as $kenshin ): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<td>
							<?php								
							$id_kenshin = $kenshin['id_kenshin'];
							echo round($jarak_ideal_positif[$id_kenshin], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		<!-- Step 6.2: Jarak Ideal Negatif ==================== -->
		<h4>Step 6.2: Jarak Ideal Negatif (S<sub>i</sub>-)</h4>			
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
					<th>Jarak Ideal Negatif</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($kenshins as $kenshin ): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<td>
							<?php								
							$id_kenshin = $kenshin['id_kenshin'];
							echo round($jarak_ideal_negatif[$id_kenshin], $digit);
							?>
						</td>						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		
		
		<!-- Step 7: Perangkingan ==================== -->
		<?php		
		$sorted_ranks = $ranks;	
		
		// Sorting
		if(function_exists('array_multisort')):
			foreach ($sorted_ranks as $key => $row) {
				$nama_kenshin[$key]  = $row['nama_kenshin'];
				$nilai[$key] = $row['nilai'];
			}
			array_multisort($nilai, SORT_DESC, $nama_kenshin, SORT_ASC, $sorted_ranks);
		endif;
		?>		
		<h4>Step 7: Perangkingan (V)</h4>			
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

<?php
require_once('../template-parts/footer.php');