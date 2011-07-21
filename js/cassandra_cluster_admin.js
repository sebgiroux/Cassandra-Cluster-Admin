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

function deleteSuperColumn(keyspace_name,columnfamily_name,key,super_column) {
	if (confirm('Are you sure you want to delete the super column ' + super_column + ' of the row ' + key + '?')) {
		location.href = 'columnfamily_action.php?action=delete_row&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '&key=' + key + '&super_column_key=' + super_column;
	}
	
	return false;
}

function changeRowsPerPage(keyspace_name,columnfamily_name,offset_key) {
	location.href = 'columnfamily_action.php?action=browse_data&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '&offset_key=' + offset_key + '&nb_rows=' + $('#show_nb_rows').val();
}

function applyClusterChange() {
	var cluster_index = $('#cluster').val();
	location.href = 'index.php?cluster=' + cluster_index;
}

function changeMX4JNode() {
	location.href = 'jmx.php?change_mx4j_node=' + $('#node').val();
}

function triggerGarbageCollector() {
	location.href = 'jmx.php?trigger_gc=1';
}

function doPlotHeapMemory(position) {
	$.plot($('#heap_memory_usage_graph'),
	   [{data: heap_memory_states, label: 'Heap Memory Usage', yaxis: 2}],
	   {xaxes: [{mode: 'time', show: false}],
		yaxes: [{min: 0},{
					  // align if we are to the right
					  alignTicksWithAxis: position == 'right' ? 1 : null,
					  position: position
					}],
		legend: {position: 'se'}
	});	  
}

function doPlotNonHeapMemory(position) {
	$.plot($('#non_heap_memory_usage_graph'),
	   [{data: non_heap_memory_states, label: 'Non Heap Memory Usage', yaxis: 2}],
	   {xaxes: [{mode: 'time', show: false}],
		yaxes: [{min: 0},{
					  // align if we are to the right
					  alignTicksWithAxis: position == 'right' ? 1 : null,
					  position: position
					}],
		legend: {position: 'se'}
	});	  
}

function round(value,precision) {
	var m = Math.pow(10, precision);
    value *= m;
    var sgn = (value > 0) | -(value < 0); // sign of the number
    var is_half = value % 1 === 0.5 * sgn;
	var f = Math.floor(value);
	
	if (is_half) value = f + (f % 2 * sgn); // rouds .5 towards the next even integer

	return (is_half ? value : Math.round(value)) / m;
}

function formatBytes(bytes) {
	if (bytes < 1024) return bytes + ' B';
	else if (bytes < 1048576) return round(bytes / 1024, 2) + ' KB';
	else if (bytes < 1073741824) return round(bytes / 1048576, 2) + ' MB';
	else if (bytes < 1099511627776) return round(bytes / 1073741824, 2) + ' GB';
	else return round(bytes / 1099511627776, 2) + ' TB';
}

function getHeapMemoryUsage() {
	$.getJSON('jmx.php?get_json_heap_memory_usage=1',function(data) {
		var d = new Date();			
		heap_memory_states.push([d.getTime(),data.used]);
		if (heap_memory_states.length > 5) heap_memory_states.shift(); // Make sure array don't grow too big
		
		$('#heap_memory_committed').html(formatBytes(data.committed));
		$('#heap_memory_init').html(formatBytes(data.init));
		$('#heap_memory_max').html(formatBytes(data.max));
		$('#heap_memory_used').html(formatBytes(data.used));
		
		doPlotHeapMemory('right');
	});
	
	setTimeout(function() {
		getHeapMemoryUsage();
	}, $('#data_refresh_interval').val());
}

function getNonHeapMemoryUsage() {
	$.getJSON('jmx.php?get_json_non_heap_memory_usage=1',function(data) {
		var d = new Date();			
		non_heap_memory_states.push([d.getTime(),data.used]);
		if (non_heap_memory_states.length > 5) non_heap_memory_states.shift(); // Make sure array don't grow too big
		
		$('#non_heap_memory_committed').html(formatBytes(data.committed));
		$('#non_heap_memory_init').html(formatBytes(data.init));
		$('#non_heap_memory_max').html(formatBytes(data.max));
		$('#non_heap_memory_used').html(formatBytes(data.used));
		
		doPlotNonHeapMemory('right');
	});
	
	setTimeout(function() {
		getNonHeapMemoryUsage();
	}, $('#data_refresh_interval').val());
}