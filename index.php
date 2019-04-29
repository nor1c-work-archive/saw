<?php
// require_once('includes/init.php');
// $judul_page = 'SAW Ricky';
// require_once('template-parts/header.php');
// require_once('user/login.php');
// // require_once('template-parts/footer.php');

require_once('includes/init.php');

if($_SESSION['user_id'] == '') {
    redirect_to("user/login.php");
} else {
    redirect_to("user/dashboard");
}