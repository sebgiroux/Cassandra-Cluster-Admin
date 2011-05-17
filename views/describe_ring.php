<ul>
	<li>Start Token: <?=$start_token?></li>
	<li>End Token: <?=$end_token?></li>
	<?php
		foreach($endpoints as $endpoint): 
			echo '<li>Endpoints: '.$endpoint.'</li>';
		endforeach;
	?>
</ul>