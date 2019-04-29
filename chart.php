<?php
/* ---------------------------------------------
 * SPK SAW
 * Author: Wawan Edan
 * ------------------------------------------- */

/* ---------------------------------------------
 * Konek ke database & load fungsi-fungsi
 * ------------------------------------------- */
require_once('includes/init.php');

/* ---------------------------------------------
 * Load Header
 * ------------------------------------------- */
$judul_page = 'Perankingan Menggunakan Metode SAW';
require_once('template-parts/header.php');

/* ---------------------------------------------
 * Set jumlah digit di belakang koma
 * ------------------------------------------- */
$digit = 4;

/* ---------------------------------------------
 * Fetch semua kriteria
 * ------------------------------------------- */
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot FROM kriteria WHERE kyu_id = ' . $_GET['kyu'] . ' ORDER BY urutan_order ASC');
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
			$nilai_normal = min($nilai_kenshins) / $nilai;
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

<div class="main-content-row">
	<div class="container clearfix">
		<div class="main-content main-content-full the-content">

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
		<table class="pure-table pure-table-striped">
			<thead>					
				<tr>
					<th class="super-top-left">Nama Kenshin</th>
					<th>Total Nilai Normalisasi</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sorted_ranks as $kenshin ): ?>
					<tr>
						<td><?php echo $kenshin['nama_kenshin']; ?></td>
						<td><?php echo round($kenshin['nilai'], $digit); ?></td>
						<td><?php if (round($kenshin['nilai'], $digit) >= 60){
							echo "<b>LULUS</b>";
						} else {
							echo "Tidak Lulus";
						}; ?></td>											
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="card-body">
		<canvas id="chart" width="100%" height="25"></canvas>
        	<script>
	            var ctx = document.getElementById('chart').getContext('2d');
				var chart = new Chart(ctx, {
				    // The type of chart we want to create
				    type: 'bar',

				    // The data for our dataset
				    data: {
				        labels: [<?php foreach($sorted_ranks as $kenshin){ echo '"'. $kenshin['nama_kenshin']. '",'; } ?>],
				        datasets: [{
				            label: "Grafik Kelulusan Kenshin",
				            backgroundColor: ['rgb(115, 255, 216)','rgb(100, 149, 237)','rgb(169, 169, 169)','rgb(143, 188, 144)','rgb(48, 206, 209)','rgb(43, 191, 254)','rgb(173, 255, 48)','rgb(240, 255, 240)','rgb(124, 252, 2)','rgb(173, 216, 230)','rgb(224, 255, 255)','rgb(144, 238, 144)','rgb(135, 206, 250)'],
				            borderColor: 'rgb(224, 255, 255)',
				            data: [<?php foreach($sorted_ranks as $kenshin){ echo '"'. $kenshin['nilai']. '",'; } ?>],
				        }]
				    },

				    // Configuration options go here
				    options: {}
				});
        	</script>
		</div>
	</div>
</div>
