<h3><a href="index.php"><?=$cluster_name?></a> &gt; JMX Stats</h3>

<script type="text/javascript">
	var d = new Date();	
	
	var data_refresh_interval = $('#data_refresh_interval').val();
	
	var heap_memory_states = [];
	heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val() * 2,"<?=$heap_memory_usage['used']?>"]);
	heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val(),"<?=$heap_memory_usage['used']?>"]);
	heap_memory_states.push([d.getTime(),"<?=$heap_memory_usage['used']?>"]);
	
	var non_heap_memory_states = []
	non_heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val() * 2,"<?=$non_heap_memory_usage['used']?>"]);
	non_heap_memory_states.push([d.getTime() -  $('#data_refresh_interval').val(),"<?=$non_heap_memory_usage['used']?>"]);
	non_heap_memory_states.push([d.getTime(),"<?=$non_heap_memory_usage['used']?>"]);
	
	$(document).ready(function() {  
		// Heap Memory Usage Graph
		doPlotHeapMemory('right');
		getHeapMemoryUsage();
		
		// Non Heap Memory Usage Graph
		doPlotNonHeapMemory('right');
		getNonHeapMemoryUsage();
	});
</script>

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
	<h3>Real Time Heap Memory Usage</h3>
	<div id="heap_memory_usage_graph" style="width:400px; height:200px;margin-bottom: 20px;"></div>

	<table>
		<tr>
			<td colspan="2">Heap Memory Usage</td>
		</tr>
		<tr>
			<td>Committed</td>
			<td id="heap_memory_committed"><?=formatBytes($heap_memory_usage['committed'])?></td>
		</tr>
		<tr>
			<td>Init</td>
			<td id="heap_memory_init"><?=formatBytes($heap_memory_usage['init'])?></td>
		</tr>
		<tr>
			<td>Max</td>
			<td id="heap_memory_max"><?=formatBytes($heap_memory_usage['max'])?></td>
		</tr>
		<tr>
			<td>Used</td>
			<td id="heap_memory_used"><?=formatBytes($heap_memory_usage['used'])?></td>
		</tr>
	</table>
</div>

<div class="float_left" style="margin-left: 20px; margin-bottom: 20px;">
	<h3>Real Time Non Heap Memory Usage</h3>
	<div id="non_heap_memory_usage_graph" style="width:400px; height:200px; margin-bottom: 20px;"></div>

	<table>
		<tr>
			<td colspan="2">Non Heap Memory Usage</td>
		</tr>
		<tr>
			<td>Committed</td>
			<td id="non_heap_memory_committed"><?=formatBytes($non_heap_memory_usage['committed'])?></td>
		</tr>
		<tr>
			<td>Init</td>
			<td id="non_heap_memory_init"><?=formatBytes($non_heap_memory_usage['init'])?></td>
		</tr>
		<tr>
			<td>Max</td>
			<td id="non_heap_memory_max"><?=formatBytes($non_heap_memory_usage['max'])?></t
		</tr>
		<tr>
			<td>Used</td>
			<td id="non_heap_memory_used"><?=formatBytes($non_heap_memory_usage['used'])?></td>
		</tr>
	</table>
</div>

<div class="clear_left"></div>

<table>
	<tr>
		<td>Name</td>
		<td>Active Count</td>
		<td>Completed Tasks</td>
		<td>Pending Tasks</td>
	</tr>
	<?php
		foreach ($tp_stats as $one_tp_stat):
			echo '<tr><td>'.$one_tp_stat['name'].'</td><td>'.$one_tp_stat['active_count'].'</td><td>'.$one_tp_stat['completed_tasks'].'</td><td>'.$one_tp_stat['pending_tasks'].'</td>';
		endforeach;
	?>
</table>

<p>Number of loaded class: <?=$nb_loaded_class?></p>

<?php
	if ($trigger_gc === true):
		echo displaySuccessMessage('invoke_garbage_collector');
	elseif ($trigger_gc === false):
		echo displayErrorMessage('invoke_garbage_collector');
	endif;
?>
<div><input type="button" value="Trigger Garbage Collector" onclick="triggerGarbageCollector();"/></div>