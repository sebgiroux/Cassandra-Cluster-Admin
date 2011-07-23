<h3><a href="index.php"><?=$cluster_name?></a> &gt; <a href="describe_keyspace.php?keyspace_name=<?=$keyspace_name?>"><?=$keyspace_name?></a> &gt; <a href="describe_columnfamily.php?keyspace_name=<?=$keyspace_name?>&amp;columnfamily_name=<?=$columnfamily_name?>"><?=$columnfamily_name?></a> &gt; Get Key</h3>

<script type="text/javascript">
	var num_index_expression = 0;
	function addIndexExpression() {
		$('#index_expression_list').append('<div class="one_index_expression">' +
			'<label for="index_expression_' + num_index_expression + '">Index Expression:</label>' +
			'<select id="index_expression_' + num_index_expression + '" name="index_name_' + num_index_expression + '" style="width: 100px;">' +
				<?php foreach ($index_name as $one_index_name): ?>
			'		<option value="<?=$one_index_name?>"><?=$one_index_name?></option>' +
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
		addIndexExpression();
	});
</script>

<?=$success_message?>
<?=$error_message?>

<?php if ($results != ''): ?>
<table border="1" style="min-width: 500px; margin-bottom: 20px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
	</tr>
	<?=$results?>
</table>
<?php endif; ?>

<form method="post" action="">
	<div>
		<label for="key">Key:</label>
		<input id="key" name="key" type="text" />
	</div>

	<div>
		<input type="submit" name="btn_get_key" value="Get" />
	</div>	
</form>

<?php if (count($index_name) > 0): ?>

<h3 style="margin-top: 45px;">Or Query Secondary Index</h3>

<?=$success_message_secondary_index?>
<?=$error_message_secondary_index?>

<?php if ($results_secondary_index != ''): ?>
<table border="1" style="min-width: 500px; margin-bottom: 20px;" cellpadding="5">
	<tr>
		<td>Key</td>
		<td>Value</td>
	</tr>
	<?=$results_secondary_index?>
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