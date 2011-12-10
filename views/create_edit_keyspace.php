<h3><a href="index.php"><?php echo $cluster_name; ?></a> <?php if(!empty($keyspace_name)): echo '&gt; <a href="describe_keyspace.php?keyspace_name='.$keyspace_name.'">'.$keyspace_name.'</a>'; endif;?><?php if($mode=='create'): echo '&gt; Create Keyspace'; else: echo ' &gt; Edit Keyspace'; endif;?></h3>

<?php echo $success_message; ?>
<?php echo $error_message; ?>

<form method="post" action="">
	<div>
		<label for="keyspace_name">Keyspace Name:</label>
		<input type="text" id="keyspace_name" name="keyspace_name" value="<?php echo $keyspace_name; ?>" <?php if($mode == 'edit'): echo 'disabled="disabled"'; endif; ?> />
	</div>
	
	<div>
		<label for="replication_factor">Replication Factor:</label>
		<input type="text" id="replication_factor" name="replication_factor" value="<?php echo $replication_factor; ?>" />
	</div>
	
	<div>
		<label for="strategy">Strategy:</label>
		<select id="strategy" name="strategy">
			<option value="org.apache.cassandra.locator.SimpleStrategy">Simple Strategy</option>
			<option value="org.apache.cassandra.locator.LocalStrategy">Local Strategy</option>
			<option value="org.apache.cassandra.locator.NetworkTopologyStrategy">Network Topology Strategy</option>
			<option value="org.apache.cassandra.locator.OldNetworkTopologyStrategy">Old Network Topology Strategy</option>
		</select>
	</div>
	
	<div>
		<input type="submit" name="<?php if($mode == 'edit'): echo 'btn_edit_keyspace'; else: echo 'btn_create_keyspace'; endif; ?>" value="<?php if($mode == 'edit'): echo 'Edit Keyspace'; else: echo 'Create Keyspace'; endif; ?>" />
	</div>	
	
	<?php if($mode == 'edit'): ?>
	<input type="hidden" name="keyspace_name" value="<?php echo $keyspace_name; ?>" />
	<?php endif; ?>
	
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#strategy').val('<?php echo $strategy_class; ?>');
	});
</script>