<div id="bk_content">
		<div id="sniplet_login">
		
		<div id="login_form">
			<?php 
			if(isset($login_error)){ ?>
				<div id="login_error"><?php echo $login_error; ?></div>
			
			<?php
			}
			echo form_open('backend/verify'); 	
			?>
			<div class="login_block">
				<label for="username">Username:</label>	
				<input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" /><?php echo form_error('username'); ?>
			</div>
			<div class="login_block">
				<label for="password">Password:</label>	
				<input type="password" name="password" value="<?php echo set_value('password'); ?>" id="password" /><?php echo form_error('password'); ?>
			</div>
			<div class="login_block login_submit">		
					<input type="submit" value="Login" name="login" />
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

