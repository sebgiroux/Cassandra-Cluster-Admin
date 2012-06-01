<?php if ($show_edit_link): ?>
	<h4>
		<a href="describe_columnfamily.php?keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>"><?php echo $columnfamily_name; ?></a> 
		<?php if (!$is_read_only_keyspace): ?>- <a href="columnfamily_action.php?action=edit&amp;keyspace_name=<?php echo $keyspace_name; ?>&amp;columnfamily_name=<?php echo $columnfamily_name; ?>">Edit</a><?php endif; ?>
	</h4>
<?php endif; ?>

<table class="table table-bordered table-striped">
<?php
	$class_vars = get_object_vars($cf_def);
	
	foreach ($class_vars as $key => $value):
		if ($value == '') continue;
		$output_value = '';
		if (is_array($value)):
			if (count($value) > 0):
				$i = 0;
				foreach ($value as $key => $sub_value):
					if (is_array($sub_value)):
						foreach ($sub_value as $key => $one_value):
							$output_value .= $key.': '.$sub_value.'<br />';
						endforeach;
					elseif (is_object($sub_value)):
						$class_vars = get_object_vars($sub_value);
						
						foreach ($class_vars as $key => $sub_value):
							$output_value .= $key.': '.$sub_value.'<br />';
						endforeach;
					else:
						$output_value .= $sub_value.'<br />';
					endif;
					
					if ($i < count($value) - 1):
						$output_value .= '<hr size="1" />';
					endif;
					
					$i++;
				endforeach;
			else:
				$output_value = 'None';
			endif;
		else:
			$output_value = $value;
		endif;

		echo '<tr><td valign="top">'.displayOneCfDef($key).'</td><td>'.$output_value.'</td></tr>';
	endforeach;
?>
</table>