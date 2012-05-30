<table border="1" cellpadding="5" class="table table-bordered table-striped">
	<?php if (!is_null($scf_key)): ?>
		<tr>
			<td colspan="3">
				<?php echo $scf_key; ?>
			</td>
		</tr>
	<?php endif; ?>

	<?php foreach ($row as $column => $value): ?>
		<tr>
			<td><?php echo $column; ?></td>
			<td><pre><?php echo $value; ?></pre></td>
			<td>
				<div class="increment_counter_icon"></div>
				<div class="float_left"><a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&amp;row_key=<?php echo $row_key; ?>&amp;column=<?php echo $column; ?><?php if (!is_null($scf_key)): echo '&super_column='.$scf_key; endif;?>&amp;increment=1">Increment</a> |</div>
				<div class="small_horizontal_spacer"></div>
				<div class="decrement_counter_icon"></div>
				<div class="float_left"><a href="columnfamily_action.php?action=browse_data&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>&amp;row_key=<?php echo $row_key; ?>&amp;column=<?php echo $column; ?><?php if (!is_null($scf_key)): echo '&super_column='.$scf_key; endif;?>&amp;decrement=1">Decrement</a></div>
				<div class="clear_left"></div>
			</td>
		</tr>
	<?php endforeach; ?>
</table>