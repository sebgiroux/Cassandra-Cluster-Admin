<tr>
	<td><?=$key?></td>
	<td><pre><?if ($is_super_cf): echo displaySCFRow($value); else: echo displayCFRow($value); endif;?></pre></td>
	<?php if ($show_actions_link): ?>
	<td>
		<a href="columnfamily_action.php?action=edit_row&keyspace_name=<?php echo $keyspace_name; ?>&columnfamily_name=<?php echo $columnfamily_name; ?>&key=<?php echo $key; ?>">Edit</a>
		- <a href="#" onclick="deleteRow('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>','<?php echo $key; ?>')">Delete</a>
	</td>
	<?php endif; ?>
</tr>