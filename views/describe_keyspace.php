<ul class="breadcrumb">
	<li>
		<a href="index.php"><?php echo $cluster_name; ?></a> <span class="divider">/</span>
	</li>
	<li class="active">
		<?php echo $keyspace_name; ?>
	</li>
</ul>


<?php if (!$is_read_only_keyspace): ?>
	<a href="keyspace_action.php?action=create_cf&amp;keyspace_name=<?php echo $keyspace_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Create New Column Family</a>
	<a href="keyspace_action.php?action=edit&amp;keyspace_name=<?php echo $keyspace_name; ?>" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Edit Keyspace</a>
	<a href="#" onclick="return dropKeyspace('<?php echo $keyspace_name; ?>');" class="btn btn-large btn-primary" style="color: #fff; text-decoration: none;">Drop Keyspace</a>
<?php endif; ?>

<?php echo $added_cf; ?>
<?php echo $deleted_cf; ?>

<h3>Keyspace Details</h3>
<div>
	<table>
		<tr>
			<td>Strategy Class:</td>
			<td><?php echo $strategy_class; ?></td>
		</tr>
		<tr>
			<td>Strategy Options:</td>
			<td>
				<?php
					if (empty($strategy_options)): echo 'None';
					elseif (is_array($strategy_options)):
						foreach ($strategy_options as $one_strategy => $value):
							echo $one_strategy.' => '.$value.'<br />';
						endforeach;
					else: echo $strategy_options; endif;
				?>
			</td>
		</tr>
		<tr>
			<td>Replication Factor:</td><td><?php echo $replication_factor; ?></td>
		</tr>
	</table>
</div>

<h3>List of Column Families in Keyspace</h3>
<?php echo $list_column_families; ?>

<h3>Ring</h3>
	
<?php
	if (is_array($ring)) {
		foreach ($ring as $node) {
			$vw_vars['start_token'] = $node->start_token;
			$vw_vars['end_token'] = $node->end_token;
			$vw_vars['endpoints'] = $node->endpoints;
		
			echo getHTML('describe_ring.php',$vw_vars);
		}
	}
	else {
		echo '<p>'.$ring.'</p>';
	}

?>
