<div id="menu">
	<div class="menu_item" onclick="location.href='describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&action=drop_index&column=<?php echo $one_si['name']; ?>'"><div class="icon drop_secondary_index"></div> Drop Index</div><div class="clear_left"></div>
</div>
<table class="table_secondary_index">
	<tr><td>Column Name:</td><td><?php echo $one_si['name']; ?></td></tr>
	<tr><td>Validation Class:</td><td><?php echo $one_si['validation_class']; ?></td></tr>
	<tr><td>Index Type:</td><td><?php echo $one_si['index_type']; ?></td></tr>
	<tr><td>Index Name:</td><td><?php echo $one_si['index_name']; ?></td></tr>
</table>