
<div id="sniplet_user" class="sniplet_min_height">


	<div id="sniplet_profile_vcard">
		<a class="sniplet_profile_picture" href="">
			<?php echo '<img src="'.$gravatar.'" alt="'.$user.'\'s profile picture"/>'; ?>
		</a>
		<div class="sniplet_profile_content">
			<div class="sniplet_profile_name"><?php echo trim($user); ?>'s profile</div>
			<span>member since <?php echo $user_year; ?></span>
		</div>
	</div>

<!--
	<ul class="sniplet_user_ul">
	<li class="snplet_user_li sniplet_user_profile" id="profiler">			
		<ul id="snplet_profile_vcard">		
			<li id="snplet_profile_picture" class="profile_vcard">
				<?php echo '<img src="'.$gravatar.'" alt="'.$user.'\'s profile picture"/>'; ?>
			</li>
			<li id="snplet_profile_overview" class="profile_vcard">
				<h4><?php echo $user; ?>'s profile</h4>
			</li>
		</ul>
	</li>
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


				
</ul>
-->
</div>
