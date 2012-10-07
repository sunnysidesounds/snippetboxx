<div id="pop-up-snipletiter">
	<h3 class="edit_this_sniplet_h3">edit your sniplet / <?php echo $sniplet_title; ?></h3>
	<?php echo form_open('#', 'id="editor_sniplet_form" name="editor_sniplet_update"'); ?> 
		<!--TODO: Dude need to change this div names -->
		<div id="edit_sniplet_container">
			<label for="edit_sniplet">title:</label><br />
			<?php echo form_input('edit_sniplet', $sniplet_title, 'id="edit_sniplet" class="edit_sniplet_input" size="75"'); ?>
		</div>
		<div id="edit_sniplet_container_area">
			<label for="edit_sniplet_text">your sniplet:</label><br/>
			<textarea id="edit_sniplet_text" name="edit_sniplet_text"><?php echo $sniplet_content; ?></textarea>
		</div>
		<div id="edit_sniplet_container_spot">
			<label for="edit_sniplet_tags">your sniplet's tags:</label><br/>
			<select id="sniplet_multi_tag" name="sniplet_multi_tags"multiple="multiple">
			<?php
			//print_r($tag_multiple_select);

			foreach ($sniplet_multiple_tags as $key => $value) {
				echo '<option selected="selected" value="'.$key.'">'.$value.'</option>';
			}
			//TODO: This is kind of ugly. Cobine these two arrays into a single array to loop out
			foreach ($sniplet_multiple_all_tags as $key => $value) {
				echo '<option value="'.$key.'">'.$value.'</option>';
			}


			?>
			</select> 
		</div>

	<?php echo form_close(); ?>
</div>