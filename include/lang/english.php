<?php
	/*
		Cassandra Cluster Admin
		
		@author Sébastien Giroux
		@copyright All rights reserved - 2011
	*/	

	/*
		Form sucess
	*/
	
	$lang['form_success_create_keyspace'] = 'Keyspace "%keyspace_name%" has been created successfully!<br />Query took %query_time%';
	$lang['form_success_edit_keyspace'] = 'Keyspace "%keyspace_name%" has been edited successfully!<br />Query took %query_time%';
	$lang['form_success_drop_keyspace'] = 'Keyspace "%keyspace_name%" has been dropped successfully!<br />Query took %query_time%';
	$lang['form_success_create_columnfamily'] = 'Column family "%columnfamily_name%" has been created successfully!<br />Query took %query_time%';
	$lang['form_success_edit_columnfamily'] = 'Column family "%columnfamily_name%" has been edited successfully!<br />Query took %query_time%';
	$lang['form_success_drop_columnfamily'] = 'Column family dropped successfully!';
	$lang['form_success_get_key'] = 'Successfully got key "%keys%"<br />Query took %query_time%';
	$lang['form_success_create_secondary_index'] = 'Secondary index on column %column_name% has been created succesfully!<br />Query took %query_time%';
	$lang['form_success_insert_row'] = 'Row inserted successfully!<br />Query took %query_time%';
	$lang['form_success_edit_row'] = 'Row "%key%" edited successfully!<br />Query took %query_time%';
	$lang['form_success_edit_counter'] = 'Counter row edited successfully. Value is now %value%!';
	$lang['form_success_invoke_garbage_collector'] = 'Garbage collector was invoked succesfully!';
	$lang['form_success_invoke_force_major_compaction'] = 'Force major compaction was invoked succesfully!';
	$lang['form_success_invoke_invalidate_key_cache'] = 'Invalidate key cache was invoked succesfully!';
	$lang['form_success_invoke_invalidate_row_cache'] = 'Invalidate row cache was invoked succesfully!';
	$lang['form_success_invoke_force_flush'] = 'Force flush was invoked succesfully!';
	$lang['form_success_invoke_disable_auto_compaction'] = 'Disable auto compaction was invoked succesfully!';
	$lang['form_success_invoke_estimate_keys'] = 'Estimate keys was invoked succesfully! Estimated keys value is : %nb_keys%';
	$lang['form_success_query_secondary_index'] = 'Successfully got %nb_results% rows from secondary index<br />Query took %query_time%';
	
	/*
		Form Info
	*/
	
	$lang['form_info_edit_keyspace_increased_replication_factor'] = 'Tips: Looks like you increased the replication factor.<br />You might want to run "nodetool -h localhost repair" on all your Cassandra nodes.';
	$lang['form_info_edit_keyspace_decreased_replication_factor'] = 'Tips: Looks like you decreased the replication factor.<br />You might want to run "nodetool -h localhost cleanup" on all your Cassandra nodes.';
	$lang['form_info_get_key_doesnt_exists'] = 'Key "%key%" doesn\'t exists';
	$lang['form_info_insert_row_not_empty'] = 'Key must not be empty';
	
	/*
		Form error
	*/
	
	$lang['form_error_create_keyspace'] = 'Keyspace "%keyspace_name%" couldn\'t be created.<br /> Reason: %message%';
	$lang['form_error_edit_keyspace'] = 'Keyspace "%keyspace_name%" couldn\'t be edited.<br /> Reason: %message%';
	$lang['form_error_drop_keyspace'] = 'Keyspace "%keyspace_name%" couldn\'t be dropped.<br /> Reason: %message%';
	$lang['form_error_create_columnfamily'] = 'Column family "%columnfamily_name%" couldn\'t be created.<br /> Reason: %message%';
	$lang['form_error_edit_columnfamily'] = 'Column family "%columnfamily_name%" couldn\'t be edited.<br /> Reason: %message%';
	$lang['form_error_get_key'] = 'Error while getting key: %message%';
	$lang['form_error_insert_row'] = 'Error while inserting row: %message%';
	$lang['form_error_create_secondary_index'] = 'Couldn\'t create secondary index on column %column_name%<br /> Reason: %message%';
	$lang['form_error_keyspace_doesnt_exists'] = 'Keyspace "%keyspace_name%" doesn\'t exists';
	$lang['form_error_columnfamily_doesnt_exists'] = 'Column family "%column_name%" doesn\'t exists';
	$lang['form_error_keyspace_name_must_be_specified'] = 'You must specify a keyspace name';
	$lang['form_error_columnfamily_name_must_be_specified'] = 'You must specify a column family name';
	$lang['form_error_login_wrong_username_password'] = 'Wrong username and/or password!';
	$lang['form_error_you_must_be_logged'] = 'You must be logged to access Cassandra Cluster Admin!';
	$lang['form_error_invalid_action_specified'] = 'Invalid action: %action%';
	$lang['form_error_no_action_specified'] = 'No action specified';
	$lang['form_error_cassandra_server_error'] = 'An error occured while connecting to your Cassandra server: %error_message%';
	$lang['form_error_insert_row_incomplete_fields'] = 'Some fields are empty';
	$lang['form_error_drop_columnfamily'] = 'Error while dropping column family: %message%';
	$lang['form_error_something_wrong_happened'] = 'Something wrong happened: %message%';
	$lang['form_error_invoke_garbage_collector'] = 'Invoking garbage collector failed.';
	$lang['form_error_invoke_force_major_compaction'] = 'Invoking Force major compaction failed.';
	$lang['form_error_invoke_invalidate_key_cache'] = 'Invoking invalidate key cache failed.';
	$lang['form_error_invoke_invalidate_row_cache'] = 'Invoking invalidate row cache failed.';
	$lang['form_error_invoke_force_flush'] = 'Invoking force flush failed.';
	$lang['form_error_invoke_disable_auto_compaction'] = 'Invoking disable auto compaction failed.';
	$lang['form_error_invoke_estimate_keys'] = 'Invoking estimate keys failed.';
	$lang['form_error_query_secondary_index'] = 'Error while querying secondary index: %message%';
	
?>