<?php
function ambil_template($nama_template = '') {
	if($nama_template) {
		require_once('template-parts/'.$nama_template.'.php');
	}	
}

function selected($param1='', $param2='') {
	if($param1 == $param2) {
		echo 'selected="selected"';
	}
}

function redirect_to($url = '') {
	header('Location: '.$url);
	exit();
}

function cek_login($role = array()) {
	
	if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && in_array($_SESSION['role'], $role)) {
		
		// redirect_to($base_path . 'user/dashboard');
		
		// $base_path = "http://".$_SERVER['HTTP_HOST'];
		// $base_path .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
		// $base_path = explode('/', str_ireplace(array('http://', 'https://'), '', $base_path));
		// $last_path = $base_path[1];
		// $base_path = "http://".$_SERVER['HTTP_HOST'] . '/' . $base_path[1];
		
	} else {
		redirect_to("user/login.php");
	}	
}

function get_role() {
	
	if(isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
		if($_SESSION['role'] == '1') {
			return 'admin';
		} else if ($_SESSION['role'] == '3') {
			return 'viewer';
		} else {
			return 'pelatih';
		}
	} else {
		return false;
	}	
}