<table border="1" cellpadding="5" style="margin-bottom: 5px;" class="table table-bordered table-striped">
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
			<td><pre><?php
				if (is_array($value)) { // not sure of a better way to determine if composite or not
					
					foreach ($value as &$valItem) {
						$valItem = htmlentities($valItem,ENT_COMPAT,'UTF-8');
					}
				
					echo '<div class="composite_value" title="CompositeType"><b>CompositeType [</b> <i>'.
						implode('</i> <b>,</b> <i>', $value).
						'</i> <b>]</b></div>';
				} else {
					echo htmlentities($value,ENT_COMPAT,'UTF-8');
				}
			?></pre></td>
		</tr>
	<?php endforeach; ?>
</table>