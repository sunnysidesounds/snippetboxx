<div id="sniplet_user" class="sniplet_min_height">
	<!--User profile information -->
	<div id="sniplet_profile_vcard">
		<a class="sniplet_profile_picture" href="#">
			<?php echo '<img src="'.$gravatar.'" alt="'.$user.'\'s profile picture"/>'; ?>
		</a>
		<div id="sniplet_closer_button">
			<a id="sniplet_button" href="#">X</a>
		</div>
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
	</div>
	<!--User profile sniplets & tags -->
	<div class="sniplet_profile_data_container">
		<div class="sniplet_profile_header">
			<div id="sniplet_mini_profiler">
				<?php
					if(isset($gravatar_mini)){
						echo '<img src="'.$gravatar_mini.'" alt="'.$user.'\'s profile picture" width="30px" />';
					}
				?>
				<div class="sniplet_profile_name_mini"><?php echo trim($user); ?>'s profile</div>
				<!--<div class="sniplet_profile_year_mini">member since <?php echo $user_year; ?></div>-->
				<div class="sniplet_profile_data_mini">
					<div class="sniplet_profile_totalsniplet_mini">
						total sniplets <b><?php echo $sniplets_count;?></b>
					</div>
					<div class="sniplet_profile_totalstags_mini">
						total tags <b><?php echo $tags_count; ?></b>
					</div>
				</div>
			</div>
			<div id="sniplet_bar_menu">
				<ul class="sniplet_profile_menu_hd">
					<li class="sniplet_profile_menu_item">account settings</li>
					<li class="sniplet_profile_menu_item"><a id="sniplet_bookmarklet_button" href="#"> bookmarklet</a></li>
					<li class="sniplet_profile_menu_item"><a id="sniplet_create_button" href="#"> create sniplet</a></li>
				</ul>
			</div>

		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_tags">
				<div id="sniplet_your_tags"><a id="tags_secret_refresh" href="#">your tags</a></div>
			</div>
			<div class="sniplet_profile_tags">	
				<?php if(isset($user_tags)){ echo $user_tags; }?>
			</div>
		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_sniplet">
				<div class="sniplet_title_t" id="sniplet_your_sniplets"><a id="sniplet_secret_refresh" href="#">your sniplets</a><span id="your_loader_sniplet"><span></div>
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
