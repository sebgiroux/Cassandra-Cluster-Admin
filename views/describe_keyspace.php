<h3><a href="index.php"><?=$cluster_name?></a> &gt; <?=$keyspace_name?></h3>
<div id="menu">
	<div class="menu_item" style="width: 220px;" onclick="location.href='keyspace_action.php?action=create_cf&keyspace_name=<?=$keyspace_name?>'">
		<div class="icon create_column_family"></div> Create New Column Family<br />
	</div>
	<div class="menu_item" onclick="location.href='keyspace_action.php?action=edit&keyspace_name=<?=$keyspace_name?>'">
		<div class="icon edit_keyspace"></div> Edit Keyspace<br />
	</div>
	<div class="menu_item" onclick="return dropKeyspace('<?=$keyspace_name?>');">
		<div class="icon drop_keyspace"></div> Drop Keyspace<br />
	</div>
	<div class="clear_left"></div>
</div>

<h3>Keyspace Details</h3>
<p>
	<table>
		<tr><td>Strategy Class:</td><td><?=$strategy_class?></td></tr>
		<tr><td>Strategy Options:</td><td><?if (empty($strategy_options)): echo 'None'; else: echo $strategy_options; endif;?></td></tr>
		<tr><td>Replication Factor:</td><td><?=$replication_factor?></td></tr>
	</table>
</p>

<h3>List of Column Families in Keyspace</h3>
<?=$list_column_families?>

<h3>Ring</h3>
	
<?
	if (is_array($ring)) {
		foreach ($ring as $node) {
			$vw_vars['start_token'] = $node->start_token;
			$vw_vars['end_token'] = $node->end_token;
			$vw_vars['endpoints'] = $node->endpoints;
		
			echo getHTML('describe_ring.php',$vw_vars);
		}
	}
	else {
		echo $ring;
	}

?>
