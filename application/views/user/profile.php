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
				<div class="sniplet_profile_showheader"><a id="sniplet_profile_header" href="#">(show)</a></div>
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
					<li class="sniplet_profile_menu_item"><a id="sniplet_settings_button" href="#"> account settings</a></li>
					<li class="sniplet_profile_menu_item"><a id="sniplet_bookmarklet_button" href="#"> bookmarklet</a></li>
					<li class="sniplet_profile_menu_item"><a id="sniplet_create_button" href="#"> create sniplet</a></li>
					<li class="sniplet_profile_menu_item"><a id="sniplet_dashboard_button" href="#"> dashboard</a></li>
				</ul>
			</div>
		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_tags">
				<div id="sniplet_your_tags">
					<a id="tags_secret_refresh" title="edit your tags" href="#">your tags</a> | <a id="tags_your_tags_used" title="tags you used, but can't edit." href="#">other tags</a> 
				</div>
			</div>
			<div class="sniplet_profile_tags">	
				<?php if(isset($user_tags)){ echo $user_tags; }?>
			</div>
		</div>
		<div class="sniplet_profile_float">	
			<div class="sniplet_profile_alphabet">	
				<ul class="sniplet_profile_alphalist_ul">
				<?php foreach ($alphabet_list as $abc) { ?>
					<li class="sniplet_profile_alphalist_li"><a class="sniplet_profile_alphabet_click" id="<?php echo strtoupper($abc); ?>" href="#"> <?php echo $abc; ?></a></li>
				<?php	} ?>
				</ul>
			</div>
		</div>
		<div class="sniplet_profile_float">
			<div class="sniplet_profile_title_sniplet">
				<div class="sniplet_title_t" id="sniplet_your_sniplets">
					<a id="sniplet_secret_refresh" title="edit your sniplets" href="#">your sniplets</a> 
					<span id="your_loader_sniplet"><span>

				</div>
			</div>		
			<div class="sniplet_profile_sniplets">	
				<?php if(isset($user_snips)){echo $user_snips; }?>
			</div>
		</div>
	</div>

</div>
