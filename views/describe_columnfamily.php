<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <?=$columnfamily_name?></h3>

<h3>Column Family Details</h3>
<?php echo $columnfamily_def; ?>

<?php
	if (count($secondary_indexes) > 0) {
		echo '<h3>Secondary Indexes</h3>';
	
		foreach ($secondary_indexes as $one_si) {
			echo 'Column Name: '.$one_si['name'].'<br />';
			echo 'Validation Class: '.$one_si['validation_class'].'<br />';
			echo 'Index Type: '.$one_si['index_type'].'<br />';
			echo 'Index Name: '. $one_si['index_name'].'<br />';
		}
	}
?>

<p>
	<h3>Actions</h3>
	
	<?if ($partitioner == 'org.apache.cassandra.dht.OrderPreservingPartitioner'): echo '- <a href="columnfamily_action.php?action=browse_data&keyspace_name='.$keyspace_name.'&columnfamily_name='.$columnfamily_name.'">Browse Data</a><br/>'; endif;?>
	- <a href="columnfamily_action.php?action=create_secondary_index&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Create a Secondary Index</a><br />
	- <a href="columnfamily_action.php?action=get_key&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Get Key</a><br />
	- <a href="columnfamily_action.php?action=insert_row&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Insert a Row</a><br />
	- <a href="columnfamily_action.php?action=edit&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>">Edit Column Family</a><br />
	- <a href="#" onclick="return truncateColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Truncate Column Family</a><br />
	- <a href="#" onclick="return dropColumnFamily('<?=$keyspace_name?>','<?=$columnfamily_name?>');">Drop Column Family</a>
</p>