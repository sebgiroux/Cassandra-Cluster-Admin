<ul class="breadcrumb">
	<li>
		<a href="index.php"><?php echo $cluster_name; ?></a> <span class="divider">/</span>
	</li>
	<li>
		<a href="describe_keyspace.php?keyspace_name=<?php echo $keyspace_name; ?>"><?php echo $keyspace_name; ?></a> <span class="divider">/</span>
	</li>
	<li class="active">
		<?php echo $columnfamily_name; ?>
	</li>
</ul>

<a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Browse Data</a>

<?php if (!$is_read_only_keyspace): ?>
	<a href="columnfamily_action.php?action=create_secondary_index&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Create Secondary Index</a>
<?php endif; ?>

<a href="columnfamily_action.php?action=get_key&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Get Key</a>

<?php if (!$is_read_only_keyspace && $is_counter_column): ?>
	<a href="counters.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Counters</a>
<?php endif; ?>

<?php if (!$is_read_only_keyspace && !$is_counter_column): ?>
	<a href="columnfamily_action.php?action=insert_row&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Insert Row</a>
<?php endif; ?>

<?php if (!$is_read_only_keyspace): ?>
	<a href="columnfamily_action.php?action=edit&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Edit Column Family</a>

	<a href="#" onclick="return truncateColumnFamily('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>');" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Truncate Column Family</a>
	
	<a href="#" onclick="return dropColumnFamily('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>');" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Drop Column Family</a>
<?php endif; ?>

<h3>Column Family Details</h3>
<?php echo $columnfamily_def; ?>

<?php if (count($secondary_indexes) > 0): ?>
	<h3>Secondary Indexes</h3>
		
	<?php 
		foreach ($secondary_indexes as $one_si): 
			echo getHTML('describe_secondary_index.php',array('one_si' => $one_si,
														  'keyspace_name' => $keyspace_name,
														  'columnfamily_name' => $columnfamily_name));
		endforeach;
	?>
<?php endif; ?>