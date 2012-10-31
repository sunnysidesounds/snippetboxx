<div id="spboxx_container">
	<div id="spboxx_search_container">
		<div id="search_load" style="display:none;">
			Loading ...
		</div>
		<div id="search_results">
			<div id="sniplet_login">
				
				<div id="login_form">
					<?php 
					if(isset($login_error)){ ?>
						<div id="login_error_error">ERROR: <?php echo $login_error; ?></div>
					
					<?php
					}
					?>
					<ul class="error_in_login_ul">
						<li class="error_in_login_li"><a href="<?php echo base_url() . 'login/' ?>">try logging in again?</a></li>
						<li class="error_in_login_li"><a href="<?php echo base_url() . 'reset/' ?>">reset your password?</a></li>
					</ul>




				</div>
			</div>
		</div>
	</div>
</div>


