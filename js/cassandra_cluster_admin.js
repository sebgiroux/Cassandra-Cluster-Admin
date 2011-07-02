function dropKeyspace(keyspace_name) {
	if (confirm('Are you sure you want to drop keyspace ' + keyspace_name + '?')) {
		location.href = 'keyspace_action.php?action=drop&keyspace_name=' + keyspace_name;
	}
	
	return false;
}

function dropColumnFamily(keyspace_name,columnfamily_name) {
	if (confirm('Are you sure you want to drop the column family ' + columnfamily_name + ' of the keyspace ' + keyspace_name + '?')) {
		location.href = 'columnfamily_action.php?action=drop&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name;
	}
	
	return false;
}

function truncateColumnFamily(keyspace_name,columnfamily_name) {
	if (confirm('Are you sure you want to truncate (delete all data) the column family ' + columnfamily_name + ' of the keyspace ' + keyspace_name + '?')) {
		location.href = 'columnfamily_action.php?action=truncate&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name;
	}
	
	return false;
}

function deleteRow(keyspace_name,columnfamily_name,key) {
	if (confirm('Are you sure you want to delete the row ' + key + '?')) {
		location.href = 'columnfamily_action.php?action=delete_row&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '&key=' + key;
	}
	
	return false;
}