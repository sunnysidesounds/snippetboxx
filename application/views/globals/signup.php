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
					<label for="username">Username:</label>	
					<input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" /><?php echo form_error('username'); ?>
				</div>
				<div class="signup_block">
					<label for="password">Email:</label>	
					<input type="text" name="email" value="<?php echo set_value('email'); ?>" id="password" /><?php echo form_error('password'); ?>
				</div>
				<div class="signup_block">
					<label for="password">Password:</label>	
					<input type="password" name="password" value="<?php echo set_value('password'); ?>" id="password" /><?php echo form_error('password'); ?>
				</div>
				<div class="signup_block">
					<label for="re_password">Re-enter Password:</label>	
					<input type="password" name="re_password" value="<?php echo set_value('re_password'); ?>" id="password" /><?php echo form_error('re_password'); ?>
				</div>
				<div class="signup_block signup_submit">		
						<input type="hidden" value="0" name="default_group" />
						<input type="hidden" value="<?php echo $date_created; ?>" name="date_created" />
						<input type="hidden" value="<?php echo $ip_address; ?>" name="ip_address" />
						<input type="submit" value="Sign-up!" name="Signup" />
						<!--Add captcha -->
				</div>	
				<?php echo form_close(); ?>
			</div>
	</div>
</div>
