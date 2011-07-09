<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/
	
	require('include/kernel.inc.php');
	
	/*
		No login required mode, redirect to index
	*/
	if (!CCA_LOGIN_REQUIRED) {
		redirect('index.php');
	}
	
	$vw_vars['login_error'] = '';
	
	/*
		Submit login form
	*/
	if (isset($_POST['btn_login'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if ($username === CCA_USERNAME && $password === CCA_PASSWORD) {
			$_SESSION['cca_login'] = md5($username.$password);
			redirect('index.php');
		}
		else {
			$vw_vars['login_error'] = displayErrorMessage('login_wrong_username_password');
		}
	}
	
	$vw_vars['you_must_be_logged'] = '';
	if (isset($_GET['you_must_be_logged'])) {
		$vw_vars['you_must_be_logged'] = displayErrorMessage('you_must_be_logged');
	}
	
	$current_page_title = 'Cassandra Cluster Admin > Login';
	
	echo getHTML('header.php');
	echo getHTML('login.php',$vw_vars);
	echo getHTML('footer.php');

?>