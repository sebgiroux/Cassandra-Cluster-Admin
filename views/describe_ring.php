<ul>
	<li>Start Token: <?php echo $start_token; ?></li>
	<li>End Token: <?php echo $end_token; ?></li>
	<?php
		foreach($endpoints as $endpoint): 
			echo '<li>Endpoints: '.$endpoint.'</li>';
		endforeach;
	?>
</ul>