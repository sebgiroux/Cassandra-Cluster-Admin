<h3><a href="index.php"><?php echo $cluster_name; ?></a> &gt; JMX Stats</h3>

<script type="text/javascript">
	var d = new Date();	
	
	var data_refresh_interval = $('#data_refresh_interval').val();
	
	var heap_memory_states = [];
	heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val() * 2,"<?php echo $heap_memory_usage['used']; ?>"]);
	heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val(),"<?php echo $heap_memory_usage['used']; ?>"]);
	heap_memory_states.push([d.getTime(),"<?php echo $heap_memory_usage['used']?>"]);
	
	var non_heap_memory_states = []
	non_heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val() * 2,"<?php echo $non_heap_memory_usage['used']; ?>"]);
	non_heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val(),"<?php echo $non_heap_memory_usage['used']; ?>"]);
	non_heap_memory_states.push([d.getTime(),"<?php echo $non_heap_memory_usage['used']?>"]);
	
	// Build array of keyspaces and column families
	var keyspaces_name = ['<?php echo implode('\',\'',$ks_and_cf_details['keyspaces_name']); ?>'];
	
	var keyspaces_details = [];
	
	<?php 
		foreach ($ks_and_cf_details['keyspaces_details'] as $key => $one_keyspace):
			echo 'keyspaces_details['.$key.'] = [];';
			foreach ($one_keyspace['columnfamilies_name'] as $cf_key => $cf_name):
	?>
			keyspaces_details[<?php echo $key; ?>][<?php echo $cf_key; ?>] = '<?php echo $cf_name; ?>';
	<?php 
			endforeach;
		endforeach;
	?>
	
	$(document).ready(function() {  
		// Heap Memory Usage Graph
		doPlotHeapMemory('right');
		getHeapMemoryUsage();
		
		// Non Heap Memory Usage Graph
		doPlotNonHeapMemory('right');
		getNonHeapMemoryUsage();
		
		getTPStats();
		
		buildDropDownOfKeyspaces();
		
		changeColumnFamily();
	});
</script>

<h3>Cluster Stats</h3>

<div style="margin-bottom: 20px;">
	Select a node:
	<select id="node" onchange="changeMX4JNode();">
		<?php
			foreach ($all_nodes as $one_node):
				list($host,$port) = explode(':',$one_node);				
				echo '<option value="'.$host.'">'.$host.'</option>';
			endforeach;
		?>
	</select>
</div>

<div class="float_left">
	Data Refresh Interval:
	<select id="data_refresh_interval" style="width: 100px;">
		<option value="2000">2 seconds</option>
		<option value="4000" selected="selected">4 seconds</option>
		<option value="10000">10 seconds</option>
		<option value="30000">30 seconds</option>
		<option value="500000">5 minutes</option>
	</select>
</div>
<div class="clear_left"></div>

<div class="float_left">
	<h4>Real Time Heap Memory Usage</h4>
	<div id="heap_memory_usage_graph"></div>

	<table>
		<tr>
			<td colspan="2">Heap Memory Usage</td>
		</tr>
		<tr>
			<td>Committed</td>
			<td id="heap_memory_committed"><?php echo formatBytes($heap_memory_usage['committed']); ?></td>
		</tr>
		<tr>
			<td>Init</td>
			<td id="heap_memory_init"><?php echo formatBytes($heap_memory_usage['init']); ?></td>
		</tr>
		<tr>
			<td>Max</td>
			<td id="heap_memory_max"><?php echo formatBytes($heap_memory_usage['max']); ?></td>
		</tr>
		<tr>
			<td>Used</td>
			<td id="heap_memory_used"><?php echo formatBytes($heap_memory_usage['used']); ?></td>
		</tr>
	</table>
</div>

<div class="float_left" style="margin-left: 40px; margin-bottom: 20px;">
	<h4>Real Time Non Heap Memory Usage</h4>
	<div id="non_heap_memory_usage_graph"></div>

	<table>
		<tr>
			<td colspan="2">Non Heap Memory Usage</td>
		</tr>
		<tr>
			<td>Committed</td>
			<td id="non_heap_memory_committed"><?php echo formatBytes($non_heap_memory_usage['committed']); ?></td>
		</tr>
		<tr>
			<td>Init</td>
			<td id="non_heap_memory_init"><?php echo formatBytes($non_heap_memory_usage['init']); ?></td>
		</tr>
		<tr>
			<td>Max</td>
			<td id="non_heap_memory_max"><?php echo formatBytes($non_heap_memory_usage['max']); ?></t
		</tr>
		<tr>
			<td>Used</td>
			<td id="non_heap_memory_used"><?php echo formatBytes($non_heap_memory_usage['used']); ?></td>
		</tr>
	</table>
</div>

<div class="jmx_tp_stats_container">
	<h4>TP Stats</h4>

	<table>
		<tr>
			<td>Name</td>
			<td>Active Count</td>
			<td>Completed Tasks</td>
			<td>Pending Tasks</td>
		</tr>
		<?php foreach ($tp_stats as $one_tp_stat): ?>
				<tr>
					<td><?php echo $one_tp_stat['name']; ?></td>
					<td id="tp_stats_<?php echo str_replace(' ','_',strtolower($one_tp_stat['name'])); ?>_active_count"><?php echo $one_tp_stat['active_count']; ?></td>
					<td id="tp_stats_<?php echo str_replace(' ','_',strtolower($one_tp_stat['name'])); ?>_completed_tasks"><?php echo $one_tp_stat['completed_tasks']; ?></td>
					<td id="tp_stats_<?php echo str_replace(' ','_',strtolower($one_tp_stat['name'])); ?>_pending_tasks"><?php echo $one_tp_stat['pending_tasks']; ?></td>
				</tr>
		<?php endforeach; ?>
	</table>
</div>

<div class="clear_left"></div>

<p>Number of loaded class: <?php echo $nb_loaded_class; ?></p>

<h4>Trigger</h4>

<?php
	if ($trigger_gc === true):
		echo displaySuccessMessage('invoke_garbage_collector');
	elseif ($trigger_gc === false):
		echo displayErrorMessage('invoke_garbage_collector');
	endif;
?>
<div><input type="button" value="Trigger Garbage Collector" onclick="triggerJMXInvoke('garbage_collector');"/></div>

<hr />
<h3>Column Families Stats</h3>

<form>
	<div><label for="keyspace_list">Select a Keyspace:</label> <select id="keyspace_list" onchange="buildDropDownOfColumnFamilies();"></select></div>

	<div><label for="columnfamily_list">Select a Column Family:</label> <select id="columnfamily_list" onchange="changeColumnFamily();"></select></div>
</form>

<h4><a name="trigger_invoke" style="color: #000;">Triggers</a></h4>

<?php
	if ($trigger_force_major_compaction === true):
		echo displaySuccessMessage('invoke_force_major_compaction');
	elseif ($trigger_force_major_compaction === false):
		echo displayErrorMessage('invoke_force_major_compaction');
	endif;

	if ($trigger_invalidate_key_cache === true):
		echo displaySuccessMessage('invoke_invalidate_key_cache');
	elseif ($trigger_invalidate_key_cache === false):
		echo displayErrorMessage('invoke_invalidate_key_cache');
	endif;

	if ($trigger_invalidate_row_cache === true):
		echo displaySuccessMessage('invoke_invalidate_row_cache');
	elseif ($trigger_invalidate_row_cache === false):
		echo displayErrorMessage('invoke_invalidate_row_cache');
	endif;

	if ($trigger_force_flush === true):
		echo displaySuccessMessage('invoke_force_flush');
	elseif ($trigger_force_flush === false):
		echo displayErrorMessage('invoke_force_flush');
	endif;

	if ($trigger_disable_auto_compaction === true):
		echo displaySuccessMessage('invoke_disable_auto_compaction');
	elseif ($trigger_disable_auto_compaction === false):
		echo displayErrorMessage('invoke_disable_auto_compaction');
	endif;
	
	if (is_array($trigger_estimate_keys) && $trigger_estimate_keys['result'] === true):
		echo displaySuccessMessage('invoke_estimate_keys',array('nb_keys' => $trigger_estimate_keys['return']));
	elseif (is_array($trigger_estimate_keys) && $trigger_estimate_keys['result'] === false):
		echo displayErrorMessage('invoke_estimate_keys');
	endif;
?>

<div class="jmx_trigger_button"><input type="button" value="Trigger Force Major Compaction" onclick="triggerJMXInvoke('force_major_compaction');"/></div>
<div class="jmx_trigger_button"><input type="button" value="Trigger Invalidate Key Cache" onclick="triggerJMXInvoke('invalidate_key_cache');"/></div>
<div class="jmx_trigger_button"><input type="button" value="Trigger Invalidate Row Cache" onclick="triggerJMXInvoke('invalidate_row_cache');"/></div>
<div class="clear_left"></div>

<div class="jmx_trigger_button"><input type="button" value="Trigger Force Flush" onclick="triggerJMXInvoke('force_flush');"/></div>
<div class="jmx_trigger_button"><input type="button" value="Trigger Disable Auto Compaction" onclick="triggerJMXInvoke('disable_auto_compaction');"/></div>
<div class="jmx_trigger_button"><input type="button" value="Trigger Estimate Keys" onclick="triggerJMXInvoke('estimate_keys');"/></div>
<div class="clear_left"></div>

<div class="float_left">
	<h4>Details</h4>
	
	<!-- Column Family Details -->
	<table id="columnfamily_details"></table>
</div>

<div class="float_left" style="margin-left: 40px;">
	<h4>Key Cache</h4>

	<!-- Column Family Key Cache Details -->
	<table id="column_family_key_cache_details"></table>

	<h4>Row Cache</h4>

	<!-- Column Family Row Cache Details -->
	<table id="column_family_row_cache_details"></table>
</div> 

<div class="clear_left"></div>