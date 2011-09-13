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

function triggerJMXInvoke(action) {
	var index_keyspace = $('#keyspace_list').val();
	var index_columnfamily = $('#columnfamily_list').val();

	var keyspace_name = keyspaces_name[index_keyspace];
	var columnfamily_name = keyspaces_details[index_keyspace][index_columnfamily];

	if (action == 'garbage_collector') {
		location.href = 'jmx.php?trigger_gc=1#trigger_invoke';
	}
	else if (action == 'force_major_compaction') {
		location.href = 'jmx.php?trigger_force_major_compaction=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
	else if (action == 'invalidate_key_cache') {
		location.href = 'jmx.php?trigger_invalidate_key_cache=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
	else if (action == 'invalidate_row_cache') {
		location.href = 'jmx.php?trigger_invalidate_row_cache=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
	else if (action == 'force_flush') {
		location.href = 'jmx.php?trigger_force_flush=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
	else if (action == 'disable_auto_compaction') {
		location.href = 'jmx.php?trigger_disable_auto_compaction=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
	else if (action == 'estimate_keys') {
		location.href = 'jmx.php?trigger_estimate_keys=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name + '#trigger_invoke';
	}
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

function getTPStats() {
	$.getJSON('jmx.php?get_tp_stats=1', function(data) {
		$.each(data,function(name,value) {
			$.each(value,function(column,value) {
				if (column != 'name') {
					$('#tp_stats_' + name + '_' + column).html(value);
				}
			});
		});
	});

	setTimeout(function() {
		getTPStats();
	}, $('#data_refresh_interval').val());
}

function buildDropDownOfKeyspaces() {
	var dropdown = $('#keyspace_list');
	
	$('#keyspace_list > option').remove();		

	$.each(keyspaces_name,function(index_keyspace) {
		dropdown.append($('<option />').val(index_keyspace).text(keyspaces_name[index_keyspace]));
	}); 
	
	buildDropDownOfColumnFamilies();
}

function buildDropDownOfColumnFamilies() {
	var index_keyspace = $('#keyspace_list').val();
	var dropdown = $('#columnfamily_list');
	
	$('#columnfamily_list > option').remove();	
	
	$.each(keyspaces_details[index_keyspace],function(index_columnfamily) {
		dropdown.append($("<option />").val(index_columnfamily).text(keyspaces_details[index_keyspace][index_columnfamily]));
	});
	
	changeColumnFamily();
}

function changeColumnFamily() {
	var index_keyspace = $('#keyspace_list').val();
	var index_columnfamily = $('#columnfamily_list').val();

	var keyspace_name = keyspaces_name[index_keyspace];
	var columnfamily_name = keyspaces_details[index_keyspace][index_columnfamily];

	$.getJSON('jmx.php?columnfamily_details=1&keyspace_name=' + keyspace_name + '&columnfamily_name=' + columnfamily_name, function(data) {
		$('#columnfamily_details').find('tr').remove();
		$('#column_family_key_cache_details').find('tr').remove();
		$('#column_family_row_cache_details').find('tr').remove();
		
		$('#columnfamily_details').append('<tr><td>Name</td><td>Value</td></tr>');
		$('#column_family_key_cache_details').append('<tr><td>Name</td><td>Value</td></tr>');
		$('#column_family_row_cache_details').append('<tr><td>Name</td><td>Value</td></tr>');
	
		$.each(data.details,function(item) {
			var name = data.details[item].name;
			var value = data.details[item].value;
			
			$('#columnfamily_details').append('<tr><td>' + name + '</td><td>' + value + '</td></tr>');
		});
		
		$.each(data.key_cache,function(item) {
			var name = data.key_cache[item].name;
			var value = data.key_cache[item].value;
			
			$('#column_family_key_cache_details').append('<tr><td>' + name + '</td><td>' + value + '</td></tr>');
		});		
				
		$.each(data.row_cache,function(item) {
			var name = data.row_cache[item].name;
			var value = data.row_cache[item].value;
			
			$('#column_family_row_cache_details').append('<tr><td>' + name + '</td><td>' + value + '</td></tr>');
		});
	});
}

function registerCFFormTooltips() {
	$('#read_repair_chance_tooltip').simpletip({
		content: '<p>Before 0.7, read_repair was either invoked on every read request or on none of them. This is now tunable as a double between 0 and 1 (inclusive on both ends) for the chance of invoking the repair.</p><p>Default is: "1.0", read repair on every read request.</p>',
		offset: [15,0]
	});
	
	$('#gc_grace_seconds_tooltip').simpletip({
		content: '<p>Time to wait before garbage-collection deletion markers. Set this to a large enough value that you are confident that the deletion marker will be propagated to all replicas by the time this many seconds has elapsed, even in the face of hardware failures. The default value is ten days.</p><p>Default is: \'864000\' seconds, or 10 days.</p>',
		offset: [15,0]
	});
	
	$('#memtable_operations_in_millions_tooltip').simpletip({
		content: '<p>The maximum number of columns in millions to store in memory per ColumnFamily before flushing to disk. This is also a per-memtable setting. Use with MemtableSizeInMB to tune memory usage.</p>',
		offset: [15,0]
	});
	
	$('#memtable_throughput_in_mb_tooltip').simpletip({
		content: '<p>The maximum amount of data to store in memory per ColumnFamily before flushing to disk. Note: There is one memtable per column family, and this threshold is based solely on the amount of data stored, not actual heap memory usage (there is some overhead in indexing the columns).</p>',
		offset: [15,0]
	});
	
	$('#memtable_flush_after_mins_tooltip').simpletip({
		content: '<p>The maximum time to leave a dirty memtable unflushed. (While any affected columnfamilies have unflushed data from a commit log segment, that segment cannot be deleted.) This needs to be large enough that it won\'t cause a flush storm of all your memtables flushing at once because none has hit the size or count thresholds yet. For production, a larger value such as 1440 is recommended.</p>', 
		offset: [15,0]
	});
	
	$('#default_validation_class_tooltip').simpletip({
		content: '<p>Used in conjunction with the validation_class property in the per-column settings to guarantee the type of a column value.</p><p>Default is: \'BytesType\', a no-op.</p>',
		offset: [15,0]
	});
	
	$('#min_compaction_threshold_tooltip').add('#max_compaction_threshold_tooltip').simpletip({
		content: '<p>Previously in the CompactionManager, these values tune the size and frequency of minor compactions. The min and max boundaries are the number of tables to attempt to merge together at once. Raising the minimum will make minor compactions take more memory and run less often, lowering the maximum will have the opposite effect.</p><p>Note: Setting minimum and maximum to 0 will disable minor compactions. USE AT YOUR OWN PERIL!</p><p>Defaults are: \'4\' minimum tables to compact at once, and \'32\' maximum.</p>',
		offset: [15,0]
	});
	
	$('#comparator_type_tooltip').add('#subcomparator_type_tooltip').simpletip({
		content: '<p>The CompareWith attribute tells Cassandra how to sort the columns for slicing operations. The default is BytesType, which is a straightforward lexical comparison of the bytes in each column. Other options are AsciiType, UTF8Type, LexicalUUIDType, TimeUUIDType, and LongType. You can also specify the fully-qualified class name to a class of your choice extending org.apache.cassandra.db.marshal.AbstractType.</p><p>SuperColumns have a similar CompareSubcolumnsWith attribute.</p><ul><li>BytesType: Simple sort by byte value. No validation is performed.</li><li>AsciiType: Like BytesType, but validates that the input can be parsed as US-ASCII.</li><li>UTF8Type: A string encoded as UTF8</li><li>LongType: A 64bit long</li><li>LexicalUUIDType: A 128bit UUID, compared lexically (by byte value)</li><li>TimeUUIDType: a 128bit version 1 UUID, compared by timestamp</li></ul><p>These are currently the same types used for validators.</p>',
		offset: [15,0]
	});
	
	$('#row_cache_size_tooltip').simpletip({
		content: '<p>Determines how many rows to cache. The values can either be an absolute value or a double between 0 and 1 (inclusive on both ends) denoting what fraction should be cached.</p><p>Each each row cache hit saves 2 seeks at the minimum, sometimes more. The row cache saves more time then key cache, but must store the whole values of its rows, so it is extremely space-intensive. It\'s best to only use the row cache if you have hot rows or static rows.</p><p>Default is: \'0\', disabled row cache.</p>',
		offset: [15,0]
	});
	
	$('#row_cached_save_period_in_seconds_tooltip').simpletip({
		content: '<p>Determines how often Cassandra saves the cache to the saved_caches_directory. Saved caches greatly improve cold-start speeds. Row cache saving is much more expensive than key cache and has limited use.</p><p>Default is: \'0\' (disabled) row cache saving.</p>',
		offset: [15,0]
	});
	
	$('#key_cache_size_tooltip').simpletip({
		content: '<p>Determines how many keys to cache. The values can either be an absolute value or a double between 0 and 1 (inclusive on both ends) denoting what fraction should be cached.</p><p>Each key cache hit saves 1 seek. The key cache is fairly tiny for the amount of time it saves, so it\'s worthwhile to use it at large numbers all the way up to 1.0 (all keys cached).</p><p>Default is: \'200000\' keys cached</p>',
		offset: [15,0]
	});
	
	$('#key_cached_save_period_in_seconds_tooltip').simpletip({
		content: '<p>Determines how often Cassandra saves the cache to the saved_caches_directory. Saved caches greatly improve cold-start speeds, and is relatively cheap in terms of I/O for the key cache.</p><p>Default is: \'3600\' seconds (1 hour) between saves of the key cache</p>',	
		offset: [15,0]
	});
}