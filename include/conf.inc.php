<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/

	/*
		Add as many Cassandra nodes as you want in the nodes array like so: array('10.70.80.90:9160','10.70.80.100:9160','10.70.80.110:9160')
		
		Multi cluster is also supported:
		
		$CASSANDRA_CLUSTERS = array(array('nodes' => array('127.0.0.1:9160'),
										 'username' => '',
										 'password' => ''),
								   array('nodes' => array('10.12.13.14:9160','10.13.12.14:9160'),
										 'username' => '',
										 'password' => ''));
	*/
	
	$CASSANDRA_CLUSTERS = array(array('nodes' => array('127.0.0.1:9160'),
								     'username' => '',
								     'password' => ''));
	
	/*
		Enter your Cassandra Cluster Admin credentials here
	*/
	define('CCA_LOGIN_REQUIRED',false);
	define('CCA_USERNAME','root'); 	
	define('CCA_PASSWORD','');
?>