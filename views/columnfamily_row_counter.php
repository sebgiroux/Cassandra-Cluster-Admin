<table border="1" cellpadding="5">
	<?php if (!is_null($scf_key)): ?><tr><td colspan="3"><?=$scf_key?></td></tr><?php endif; ?>

	<?php foreach ($row as $column => $value): ?>
		<tr>
			<td><?=$column?></td>
			<td><pre><?=$value?></pre></td>
			<td>
				<div class="increment_counter_icon"></div>
				<div class="float_left"><a href="columnfamily_action.php?action=browse_data&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>&row_key=<?=$row_key?>&column=<?=$column?><?if (!is_null($scf_key)): echo '&super_column='.$scf_key; endif;?>&increment=1">Increment</a> |</div>
				<div class="small_horizontal_spacer"></div>
				<div class="decrement_counter_icon"></div>
				<div class="float_left"><a href="columnfamily_action.php?action=browse_data&keyspace_name=<?=$keyspace_name?>&columnfamily_name=<?=$columnfamily_name?>&row_key=<?=$row_key?>&column=<?=$column?><?if (!is_null($scf_key)): echo '&super_column='.$scf_key; endif;?>&decrement=1">Decrement</a></div>
				<div class="clear_left"></div>
			</td>
		</tr>
	<?php endforeach; ?>
</table>