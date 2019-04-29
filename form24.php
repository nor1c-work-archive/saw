<?php require_once('includes/init.php'); ?>
<?php require_once('includes/fpdf17/fpdf.php'); ?>
<?php cek_login($role = array(1, 2)); ?>
<?php
               
$query = $pdo->prepare ('SELECT kenshin.nama_kenshin AS nama, kenshin.nik AS nik, kenshin.nim AS nim, kenshin.jurusan AS jurusan, kenshin.tingkatan AS tingkatan, kenshin.email AS email, kenshin.alamat AS alamat, kenshin.hp AS hp FROM kenshin');
$query->execute();
$query->setFetchMode(PDO::FETCH_ASSOC);
$kenshins = $query->fetchAll();

//Create a new PDF file
$pdf = new FPDF('P','mm',array(210,297)); //L For Landscape / P For Portrait
$pdf->AddPage();

$pdf->SetFont('Arial','B',13);
$pdf->Cell(80);
$pdf->Cell(30,10,'FORM IKUT UJIAN KEMPO',0,0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','I',10);
$pdf->Cell(80);
$pdf->Cell(30,10,'Private & Confidential',0,0,'C');

?>
