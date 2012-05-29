<table border="1" cellpadding="5" style="margin-bottom: 5px;">
	<?php if (!is_null($scf_key)): ?>
		<tr>
			<td colspan="2">
				<div class="float_left"><?php echo htmlentities($scf_key,ENT_COMPAT,'UTF-8'); ?></div>
				<div class="float_right" style="margin-left: 20px;">
					<div class="delete_row_icon"></div>
					<div class="float_left">
						<a href="#" onclick="deleteSuperColumn('<?php echo $keyspace_name; ?>','<?php echo $columnfamily_name; ?>','<?php echo escapeNameForJs($row_key); ?>','<?php echo escapeNameForJs($scf_key);?>')">Delete</a>
					</div>
					<div class="clear_both"></div>
				</div>
			</td>
		</tr>
	<?php endif; ?>
			
	<?php foreach ($row as $column => $value): ?>
		<tr>
			<td><?php echo htmlentities($column,ENT_COMPAT,'UTF-8'); ?></td>
			<td><pre><?php echo htmlentities($value,ENT_COMPAT,'UTF-8'); ?></pre></td>
		</tr>
	<?php endforeach; ?>
</table>