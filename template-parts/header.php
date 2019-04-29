<?php
	$base_path = "http://".$_SERVER['HTTP_HOST'];
	$base_path .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	$base_path = explode('/', str_ireplace(array('http://', 'https://'), '', $base_path));
	$base_path = "http://".$_SERVER['HTTP_HOST'] . '/' . $base_path[1];
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta charset="UTF-8" />
	<title><?php
		if(isset($judul_page)) {
			echo $judul_page;
		}
	?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />

	<!-- css -->
	<link rel="stylesheet" href="<?=$base_path?>/stylesheets/style.css">
	<link rel="stylesheet" href="<?=$base_path?>/stylesheets/custom_bootstrap.css">
	<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.13.3/dist/bootstrap-table.min.css">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		
	<!-- bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">

	<link href="<?=$base_path?>/stylesheets/datepicker.min.css" rel="stylesheet" type="text/css">

	<!-- bootstrap -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<script src="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.js"></script>
	<script src="https://rawgit.com/wenzhixin/colResizable/master/source/colResizable-1.5.source.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	
	<script type="text/javascript" src="<?=$base_path?>/js/superfish.min.js"></script>	
    <script src="<?=$base_path?>/js/datepicker.min.js"></script>
	<script src="http://momentjs.com/downloads/moment.js"></script>
	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<!-- <script type="text/javascript" src="<?=$base_path?>/js/main.js"></script> -->

	<style>
		.container { 
			max-width: 95% !important;
		}
		.datepicker {
			z-index: 99999 !important;
		}
	</style>

	<script>
		;(function ($) { $.fn.datepicker.language['en'] = {
			days: ['Minggu', 'Senen', 'Selasa', 'Rebo', 'Kemis', 'Jumat', 'Sabtu'],
			daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
			months: ['Januari','Februari','Maret','April','Mei','Juni', 'Juli','Agustus','September','Oktober','Nopember','Desember'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			today: 'Today',
			clear: 'Clear',
			dateFormat: 'yyyy-mm-dd',
			timeFormat: 'hh:ii aa',
			firstDay: 0,
		}; })(jQuery);
	</script>
</head>
<body class style>
	<div id="">

		<!-- start of bootstrap menu -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="#"> <img src="../images/perkemi_logo.png" alt="" style="width:30px;">&nbsp; PERKEMI S.A.W</a>

			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
					<?php $user_role = get_role(); ?>
					<?php if($user_role == 'admin' || $user_role == 'pelatih' || $user_role == 'viewer') { ?>
						<li class="nav-item">
							<a class="nav-link" href="<?=$base_path?>/user/dashboard">Home</a>
						</li>
						<?php if($user_role == 'admin') { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$base_path?>/user">Users</a>
							</li>
						<?php } ?>
						<?php if($user_role == 'admin' || $user_role == 'viewer') { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$base_path?>/peserta">Anggota</a>
							</li>
						<?php } ?>
						<?php if($user_role == 'pelatih' || $user_role == 'admin') { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$base_path?>/absensi">Absensi</a>
							</li>
						<?php } ?>
						<?php if($user_role == 'admin') { ?>
							<li class="nav-item">
								<a class="nav-link" href="<?=$base_path?>/periode">Periode</a>
							</li>
						<?php } ?>
						<li class="nav-item">
							<a class="nav-link" href="<?=$base_path?>/kriteria">Kriteria</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=$base_path?>/seleksi">Seleksi UKT</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=$base_path?>/ranking-saw">Ranking SAW</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?=$base_path?>/graphic">Grafik</a>
						</li>
					<?php } ?>
				</ul>				
				<?php if(isset($_SESSION['user_id'])): ?>
					<div class="form-inline my-2 my-lg-0">
						<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
							<li class="nav-item">
								<a class="nav-link" href="<?=$base_path?>/logout.php" tabindex="-1" aria-disabled="true">Logout</a>
							</li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</nav>
		
		<div id="container">