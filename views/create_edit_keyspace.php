<h3><a href="index.php"><?=$cluster_name?></a> <?if(!empty($keyspace_name)): echo '&gt; '.$keyspace_name; endif;?><?if($mode=='create'): echo '&gt; Create Keyspace'; endif;?></h3>

<?=$success_message?>
<?=$error_message?>

<form method="post" action="">
	<div>
		<label for="keyspace_name">Keyspace Name:</label>
		<input type="text" id="keyspace_name" name="keyspace_name" value="<?=$keyspace_name?>" />
	</div>
	
	<div>
		<label for="replication_factor">Replication Factor:</label>
		<input type="text" id="replication_factor" name="replication_factor" value="<?=$replication_factor?>" />
	</div>
	
	<div>
		<label for="strategy">Strategy:</label>
		<select id="strategy" name="strategy">
			<option value="org.apache.cassandra.locator.LocalStrategy">Local Strategy</option>
			<option value="org.apache.cassandra.locator.NetworkTopologyStrategy">Network Topology Strategy</option>
			<option value="org.apache.cassandra.locator.OldNetworkTopologyStrategy">Old Network Topology Strategy</option>
			<option value="org.apache.cassandra.locator.SimpleStrategy">Simple Strategy</option>
		</select>
	</div>
	
	<div>
		<input type="submit" name="<?if($mode == 'edit'): echo 'btn_edit_keyspace'; else: echo 'btn_create_keyspace'; endif; ?>" value="<?if($mode == 'edit'): echo 'Edit Keyspace'; else: echo 'Create Keyspace'; endif; ?>" />
	</div>	
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('#strategy').val('<?=$strategy_class?>');
	});
</script>