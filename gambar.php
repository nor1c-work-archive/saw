<?php
/*----------------------------------
* Buat Grafik
----------------------------------*/
/*------connect ke db-------------*/
require_once('includes/init.php');
/*------buat headernya------------*/
$judul_page = 'test gambar';
require_once('template-parts/header.php');
/*------fetch tabel kriteria------*/
$query = $pdo->prepare('SELECT id_kriteria, nama, type, bobot FROM kriteria ORDER BY urutan_order ASC');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kriterias = $query->fetchAll();
/*------fetch tabel kenshin-------*/
$query2 = $pdo->prepare('SELECT id_kenshin, nama_kenshin FROM kenshin');
$query2->execute();			
$query2->setFetchMode(PDO::FETCH_ASSOC);
$kenshins = $query2->fetchAll();
/*---------matriks x--------------*/
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
/*------------matriks r------------------*/
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
/*------------total nilai------------------*/
$jumlah = array();
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

<!---------------bagian bawah------------- -->
<?php
require_once('template-parts/footer.php');