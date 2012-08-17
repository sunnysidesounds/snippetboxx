
<div id="sniplet_user" class="sniplet_min_height">
<ul class="sniplet_user_ul">

		<!--	<li class="snplet_user_li">		
			<h4>Your Profile</h4>			
			</li> -->

	<li class="snplet_user_li sniplet_user_tags">			
	<h4><?php echo $user; ?>'s tags</h4>	
	<?php 
	if(isset($user_tags)){
		echo $user_tags; 
	}?>
	</li>

	<li class="snplet_user_li sniplet_user_sniplets">			
	<h4><?php echo $user; ?>'s sniplets</h4>		
		<?php 
	if(isset($user_snips)){
		echo $user_snips; 
	}?>
	</li>
	<li class="snplet_user_li sniplet_user_profile">			
	<h4><?php echo $user; ?>'s profile</h4>		
		<?php 
		echo '<img src="'.$gravatar.'" alt="" />';
		
	/*if(isset($user_snips)){
		echo $user_snips; 
	}*/?>
	</li>

				
</ul>
</div>
		