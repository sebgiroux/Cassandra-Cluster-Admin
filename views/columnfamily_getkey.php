<h3><a href="index.php"><?php echo $cluster_name; ?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?php echo $keyspace_name; ?>"><?php echo $keyspace_name; ?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> &gt; Get Key</h3>

<script type="text/javascript">
	var num_key = 0;
	var num_index_expression = 0;
	
	function addKey() {
		$('#keys_list').append('<div>' +
									'<label for="key">Key:</label>' +
									'<input id="key" name="key_' + num_key + '" type="text" /><input type="button" value="Add..." id="btn_add_key_' + num_key + '" onclick="addKey();" />' +
								'</div>');
								
		var prev_num_key = num_key - 1;			
		$('#btn_add_key_' + prev_num_key).remove();
								
		num_key++;
	}
	
	function addIndexExpression() {
		$('#index_expression_list').append('<div class="one_index_expression">' +
			'<label for="index_expression_' + num_index_expression + '">Index Expression:</label>' +
			'<select id="index_expression_' + num_index_expression + '" name="index_name_' + num_index_expression + '" style="width: 100px;">' +
				<?php foreach ($index_name as $one_index_name): ?>
			'		<option value="<?php echo $one_index_name; ?>"><?php echo $one_index_name; ?></option>' +
				<?php endforeach; ?>
			'</select>' +
			'<select style="width: 50px;" name="operator_' + num_index_expression + '">' +
			'	<option value="eq">=</option><option value="gte">&gt;=</option><option value="gt">&gt;</option><option value="lte">&lt;=</option><option value="lt">&lt;</option>' +
			'</select>' +
			'<input type="text" name="column_value_' + num_index_expression + '" style="width: 100px;" />' +
			'<input type="button" value="Add..." id="btn_add_index_expression_' + num_index_expression + '" onclick="addIndexExpression();" />' +
		'</div>');
		
		var prev_num_index_expression = num_index_expression - 1;
		$('#btn_add_index_expression_' + prev_num_index_expression).remove();
		
		num_index_expression++;
	}
	
	$(document).ready(function() {
		addKey();
		addIndexExpression();
	});
</script>

<?php echo $success_message; ?>
<?php echo $error_message; ?>

<?php if ($results != ''): ?>
<table border="1" style="min-width: 500px; margin-bottom: 20px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
		<td>Actions</td>
	</tr>
	<?php echo $results; ?>
</table>
<?php endif; ?>

<form method="post" action="">
	<div id="keys_list"></div>

	<div>
		<input type="submit" name="btn_get_key" value="Get" />
	</div>	
</form>

<?php if (count($index_name) > 0): ?>

<h3 style="margin-top: 45px;">Or Query Secondary Index</h3>

<?php echo $success_message_secondary_index; ?>
<?php echo $error_message_secondary_index; ?>

<?php if ($results_secondary_index != ''): ?>
<table border="1" style="min-width: 500px; margin-bottom: 20px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
	</tr>
	<?php echo $results_secondary_index; ?>
</table>
<?php endif; ?>

<form method="post" action="">
	<div id="index_expression_list"></div>
	
	<div>
		<label for="nb_rows">Number of rows to get:</label>
		<input type="text" id="nb_rows" name="nb_rows" style="width: 100px;" value="10" />
	</div>
	
	<div>
		<input type="submit" name="btn_query_secondary_index" value="Get" />
	</div>
</form>

<?php endif; ?>