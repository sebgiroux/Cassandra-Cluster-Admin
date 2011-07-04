<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <?=$columnfamily_name?></h3>

<div id="menu">
	<?php if ($partitioner == 'org.apache.cassandra.dht.OrderPreservingPartitioner'): ?>
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=browse_data&keyspace_name=<?php echo $keyspace_name; ?>&columnfamily_name=<?php echo $columnfamily_name; ?>'">
		<div class="icon browse_data"></div> Browse Data
	</div>
	<?php endif; ?>
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=create_secondary_index&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>'">
		<div class="icon create_secondary_index"></div> Create Secondary Index
	</div>
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=get_key&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>'">
		<div class="icon get_key"></div> Get Key
	</div>
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=insert_row&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>'">
		<div class="icon insert_row"></div> Insert Row
	</div>
	<div class="menu_item" onclick="location.href='columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>'">
		<div class="icon edit_column_family"></div> Edit Column Family
	</div>
	<div class="menu_item" onclick="return truncateColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">
		<div class="icon truncate_column_family"></div> Truncate Column Family
	</div>
	<div class="menu_item" onclick="return dropColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">
		<div class="icon drop_column_family"></div> Drop Column Family
	</div>
	<div class="clear_left"></div>
</div>

<h3>Column Family Details</h3>
<?php echo $columnfamily_def; ?>

<?php
	if (count($secondary_indexes) > 0) {
		echo '<h3>Secondary Indexes</h3>';
	
		foreach ($secondary_indexes as $one_si) {
			echo '<table class="table_secondary_index">';
				echo '<tr><td>Column Name:</td><td>'.$one_si['name'].'</td></tr>';
				echo '<tr><td>Validation Class:</td><td>'.$one_si['validation_class'].'</td></tr>';
				echo '<tr><td>Index Type:</td><td>'.$one_si['index_type'].'</td></tr>';
				echo '<tr><td>Index Name:</td><td>'. $one_si['index_name'].'</td></tr>';
			echo '</table>';
		}
	}
?>