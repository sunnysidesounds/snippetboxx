<div id="bk_content">
		<div id="sniplet_signup">		
			<div id="signup_form">
				<?php 		
				if(isset($signup_error)){ ?>
					<div id="signup_error"><?php echo $signup_error; ?></div>
				
				<?php
				}
				echo form_open('backend/signup'); 	
				?>
				<div class="signup_block">


				</div>	
				<?php echo form_close(); ?>
			</div>
	</div>
</div>