<div class="sniplet_settings_display">
	<div class="settings_container">
		<a href="#" title="edit_your_settings" id="sniplet_edit_settings">your account settings</a>
	</div>
<?php
echo '<pre>';
//print_r($field_describe);
echo '</pre>';
?>

	<div class="settings_sub_container">
	<?php foreach ($field_sets as $field_label => $field_value) { ?>
		<div class="sniplet_config_field">
			<label for="edit_<?php echo $field_label; ?>"><?php echo $field_label; ?>:</label><br>
			<?php 
				//Set max and min of input size.
				$field_length = strlen($field_value);
				if($field_length > 130){$field_length = 130;} 
				else if($field_length < 75){$field_length = 75;} 

			?>
			<input type="text" size="<?php echo $field_length; ?>" class="edit_sniplet_settings" id="edit_sniplet_<?php echo $field_label; ?>" value="<?php echo $field_value; ?>" name="edit_sniplet_<?php echo $field_label; ?>">
			<a class="sniplet_settings_update" href="#">update</a>
			<div class="sniplet_setting_describe">
			<?php
				if(array_key_exists($field_label, $field_describe)){
					echo '<span>descriptions: </span>' .  $field_describe[$field_label];
				}

				
			?>
			</div>
			<br />
		</div>
	<?php } ?>
	</div>
</div>
