<a href="#" onclick="location.href='describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&action=drop_index&column=<?php echo $one_si['name']; ?>'" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Drop Index</a>

<table class="table table-bordered table-striped" style="margin-top: 10px;">
	<tr><td>Column Name:</td><td><?php echo $one_si['name']; ?></td></tr>
	<tr><td>Validation Class:</td><td><?php echo $one_si['validation_class']; ?></td></tr>
	<tr><td>Index Type:</td><td><?php echo $one_si['index_type']; ?></td></tr>
	<tr><td>Index Name:</td><td><?php echo $one_si['index_name']; ?></td></tr>
</table>