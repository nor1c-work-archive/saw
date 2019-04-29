<?php
	$base_path = "http://".$_SERVER['HTTP_HOST'];
	$base_path .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	$base_path = explode('/', str_ireplace(array('http://', 'https://'), '', $base_path));
	$base_path = "http://".$_SERVER['HTTP_HOST'] . '/' . $base_path[1];
?>
    
    <style>
        a {
            text-decoration: none !important;
        }
    </style>
    <div class="album py-5 bg-light">
        <div class="container">
    
            <div class="row">
			<?php $user_role = get_role(); ?>
			<?php if($user_role == 'admin' || $user_role == 'pelatih' || $user_role == 'viewer') { ?>
            
				<?php if($user_role == 'admin') { ?>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/user">
                        <img src="../images/users.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">USERS</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>

				<?php if($user_role == 'admin' || $user_role == 'viewer') { ?>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/peserta">
                        <img src="../images/anggota.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">ANGGOTA</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>

				<?php if($user_role == 'pelatih' || $user_role == 'admin') { ?>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/absensi">
                        <img src="../images/absensi.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">ABSENSI</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>
                
				<?php if($user_role == 'admin') { ?>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/periode">
                        <img src="../images/periode.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">PERIODE</p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>
                
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/kriteria">
                        <img src="../images/kriteria.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">KRITERIA</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/seleksi">
                        <img src="../images/seleksi.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">SELEKSI</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card mb-2 shadow-md">
                        <a href="<?=$base_path?>/ranking-saw">
                        <img src="../images/ranking.png" alt="">
                            <div class="card-body">
                                <p class="card-text" style="text-align:center;">RANKING</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

</main>