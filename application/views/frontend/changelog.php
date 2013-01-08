
<div class="sniplet_changelog_data_container">

	<div id="spboxx_search_container">
		<h3 class="sniplet_changelog_header">our live commits changelog:</h3>
		<?php
			 if(isset($display_changelog)){
				foreach ($display_changelog as $changes) {
					echo $changes;
				}

			}


		?>
	</div>
	
</div>
