
<div id="spboxx_container">

	<div id="spboxx_search_container">
		<h3>Changelog: <?php echo $s_version; ?></h3><br />
		<?php
			 if(isset($display_changelog)){
				foreach ($display_changelog as $changes) {
					echo $changes;
				}

			}


		?>

	


	</div>
	
</div>
