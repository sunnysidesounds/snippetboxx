<div id="pop-up-snipletiter">
<h3>edit your tag / <?php echo $tag_content; ?></h3>
	<?php echo form_open('#', 'id="editor_tag_form" name="editor_tag_update"'); ?> 
		<div id="edit_tag_container">
			<?php echo form_input('edit_tag', $tag_content, 'id="edit_tag" class="edit_tag_input" size="40"'); ?>
		</div>
		<?php echo form_submit('submit_tag_edit','Update', 'id="submit" '); ?>
	<?php echo form_close(); ?>

	<div id="tags_metadata">
		<div class="metadata"><b><?php echo $tag_total; ?></b> sniplets have this tag</div>
		<div class="metadata">created on <b><?php echo $tag_date_created; ?></b></div>
	</div>
</div>