<h3><a href="index.php"><?=$cluster_name?></a> &gt; <?=$keyspace_name?></h3>

<p>
	Strategy Class: <?=$strategy_class?><br />
	Strategy Options: <?if (empty($strategy_options)): echo 'None'; else: echo $strategy_options; endif;?><br />
	Replication Factor: <?=$replication_factor?>
</p>

List of Column Families in Keyspace: 
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

<h3>Actions</h3>

- <a href="keyspace_action.php?action=create_cf&keyspace_name=<?=$keyspace_name?>">Create a New Column Family</a><br />
- <a href="keyspace_action.php?action=edit&keyspace_name=<?=$keyspace_name?>">Edit Keyspace</a><br />
- <a href="#" onclick="return dropKeyspace('<?=$keyspace_name?>');">Drop keyspace</a>
