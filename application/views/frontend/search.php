<div id="search_container">
	<div id="search_form_container">
	<?php echo form_open('', 'id="search_form" name="search_add"'); ?> 
		<?php echo form_input('search', '', 'id="search_sniplets" class="sniplets" size="50"'); ?>
		
		<?php echo form_submit('submit_sniplets','Search', 'id="submit_search" class="sniplets" '); ?>
	<?php echo form_close(); ?>
	</div>	
	<div id="search_tags">
		<?php if(isset($tag_top_ten)){echo $tag_top_ten;}?>
	</div>
	<div id="search_messages"></div>
	
</div>
<div id="tags_slider" style="display:none;">

</div>