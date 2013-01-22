<div id="pop-up-snipletiter">
	
	<?php if($id != 0){ ?>
		<!-- Editing and Updating sniplets -->
		<h3 class="edit_this_sniplet_h3">edit your sniplet / <?php echo $sniplet_title; ?></h3>
	<?php } else { ?>
		<!-- Creating new sniplets -->
		<h3 class="edit_this_sniplet_h3">create new sniplet</h3>
	<?php } ?>
	

	<?php echo form_open('#', 'id="editor_sniplet_form" name="editor_sniplet_update"'); ?> 
		<!--TODO: Dude need to change this div names -->
		<div id="edit_sniplet_container">
			<label for="edit_sniplet">title:</label><br />
			<?php echo form_input('edit_sniplet_title', $sniplet_title, 'id="edit_sniplet" class="edit_sniplet_input" size="75"'); ?>
			<br />
		
			<?php if($id != 0){ ?>
				<!-- Editing and Updating sniplets -->
				<label for="edit_sniplet">your sniplet's url:</label><br />
				<div id="sniplet_url_edit"><?php echo $tag_url; ?></div>
			<?php } ?>

		</div>
		<div id="edit_sniplet_container_area">
			<label for="edit_sniplet_text">your sniplet:</label><br/>
			<textarea id="edit_sniplet_text" name="edit_sniplet_text"><?php echo $sniplet_content; ?></textarea>
		</div>
		<div id="edit_sniplet_container_spot">
			<label for="edit_sniplet_tags">your sniplet's tags:</label><br/>
			<?php echo form_input('edit_sniplet_tags', 'Loading...', 'id="edit_tags_sniplet" class="edit_tags_sniplet_input"'); ?>
			
		</div>
		<?php if($id != 0){ ?>
		<div id="edit_sniplet_container_created">created on: <?php echo $creation_date; ?></div>
		<?php } ?>
		<div id="edit_sniplet_container_submit">
			<?php if($id != 0){ ?>
				<!-- Editing and Updating sniplets -->
				<input id="sniplet_id" type="hidden" name="edit_sniplet_id" value="<?php echo $id; ?>">
				<input id="sniplet_username_id" type="hidden" name="edit_sniplet_username" value="<?php echo $sniplet_username; ?>">
				<input id="sniplet_update_time" type="hidden" name="edit_sniplet_update_time" value="<?php echo date('m-d-Y-g:ia'); ?>">
				<input id="edit_submit" type="submit" value="Update" />
			
			<?php } else { ?>
				<!-- Creating new sniplets -->
				<input id="sniplet_username_id" type="hidden" name="sniplet_username" value="<?php echo $sniplet_username; ?>">
				<input id="sniplet_url" type="hidden" name="sniplet_url" value="<?php echo base_url(); ?>">
				<input id="sniplet_creation_time" type="hidden" name="sniplet_creation_time" value="<?php echo date('m-d-Y-g:ia'); ?>">
				<input id="create_submit" type="submit" value="Create" />
			<?php } ?>
		
		</div>
	<?php echo form_close(); ?>
</div>
