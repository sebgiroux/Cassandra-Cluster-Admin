<div id="cluster_info">
	<h3>Cluster Name: <?=$cluster_name;?></h3>

	Cluster Partitioner: <?=$partitioner;?><br />
	Cluster Snitch: <?=$snitch;?><br />
	Thrift API Version: <?=$thrift_api_version?><br />
	Schema Version:
	<?php 
		foreach ($schema_version as $version => $servers) {
			foreach ($servers as $one_server) {
				echo $version.' ';
			}
		}
	?>
</div>

<?=$success_message?>
<?=$error_message?>

<div id="menu">
	<div class="menu_item" onclick="location.href='keyspace_action.php?action=create'">
		<div class="icon create_keyspace"></div> Create New Keyspace
	</div>
	<div class="clear_left"></div>
</div>

<h3>Keyspaces and Column Families</h3>	
<ul id="keyspaces">
	<?
		$nb_ks = count($keyspaces_name);
		for ($i = 0; $i < $nb_ks; $i++):
			$keyspace_name = $keyspaces_name[$i];
			
			echo '<li><a href="describe_keyspace.php?keyspace_name='.$keyspace_name.'">'.$keyspace_name.'</a>';
				echo '<ul>';
					$nb_cf = count($keyspaces_details[$i]['columnfamilys_name']);
					for ($j = 0; $j < $nb_cf; $j++):
						$columnfamily_name = $keyspaces_details[$i]['columnfamilys_name'][$j];
						echo '<li><a href="describe_columnfamily.php?keyspace_name='.$keyspace_name.'&amp;columnfamily_name='.$columnfamily_name.'">'.$columnfamily_name.'</a></li>';
					endfor;						
				echo '</ul>';				
			echo '</li>';
		endfor;
	?>
</ul>		



