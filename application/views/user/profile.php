
<div id="sniplet_user" class="sniplet_min_height">
	<!--User profile information -->
	<div id="sniplet_profile_vcard">
		<a class="sniplet_profile_picture" href="#">
			<?php echo '<img src="'.$gravatar.'" alt="'.$user.'\'s profile picture"/>'; ?>
		</a>
		<div class="sniplet_profile_content">
			<div class="sniplet_profile_name"><?php echo trim($user); ?>'s profile</div>
			<div class="sniplet_profile_year">member since <?php echo $user_year; ?></div>
			<div class="sniplet_profile_data">
				<ul class="sniplet_profile_dataul">
					<li class="sniplet_profile_datali">
						<div class="single_profile_datacon">
							<div class="totals">total sniplets</div>
							<div class="totals_num"><?php echo $sniplets_count;?></div>
						</div>
					</li>
					<li class="sniplet_profile_datali">
						<div class="single_profile_datacon">
							<div class="totals">total tags</div>
							<div class="totals_num"><?php echo $tags_count; ?></div>
						</div>
					</li>
				</ul>	
			</div>

		</div>
		<!--<div class="sniplet_bookmarklet_container">
			<a href="#" id="sniplet_bookmarklet_download">download your bookmarklet!</a>
		</div>-->
	</div>
	<!--User profile sniplets & tags -->
	<div class="sniplet_profile_data_container">
		<div class="sniplet_profile_header">

		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_tags">
				<div id="sniplet_your_tags">your tags</div>
			</div>
			<div class="sniplet_profile_tags">	
				<?php if(isset($user_tags)){ echo $user_tags; }?>
			</div>
		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_sniplet">
				<div id="sniplet_your_sniplets">your sniplets</div>
			</div>		
			<div class="sniplet_profile_sniplets">	
				<?php if(isset($user_snips)){echo $user_snips; }?>
			</div>
		</div>
	<!--	<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_editor">
				<div id="sniplet_your_editor">your sniplet editor</div>
			</div>		
			<div class="sniplet_profile_editor">	

			</div>
		</div> -->


		<!--<ul class="sniplet_user_ul">
			<li class="snplet_user_li sniplet_user_tags">			
				<h4>your tags</h4>	

			<?php if(isset($user_tags)){ echo $user_tags; }?>
			</li>
			<li class="snplet_user_li sniplet_user_sniplets">			
				<h4>your sniplets</h4>		
				<?php if(isset($user_snips)){echo $user_snips; }?>
			</li>
		</ul>-->
	</div>

</div>
