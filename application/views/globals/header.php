
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<!--Frontend CSS-->
	<link rel='stylesheet' type='text/css' href='<?= base_url() ?>css/styles.css' />
	<link rel='stylesheet' type='text/css' href='<?= base_url() ?>js/autosuggest/autoSuggest.css' />
	<link rel="stylesheet" href="<?= base_url() ?>js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<!--<link rel="stylesheet" href="<?= base_url() ?>js/fancybox_2/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />-->

	<link rel="stylesheet" href="<?= base_url() ?>css/smoothness/jquery-ui.css" type="text/css" media="screen" />

	<!--Frontend JS -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.ui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.base64.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/validation.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<!--<script type="text/javascript" src="<?= base_url() ?>js/fancybox_2/jquery.fancybox.pack.js?v=2.1.3"></script>-->

	<script type="text/javascript" src="<?= base_url() ?>js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/scrollpagination.js"></script>
	<script type="text/javascript" src="<?= base_url()?>js/autosuggest/jquery.autoSuggest.js"></script>

	<script type="text/javascript">		
		CI_ROOT = "<?=base_url() ?>";	
		CI_SITE =  "<?=site_url() ?>";	
	</script>
	<script type="text/javascript" src="<?= base_url() ?>js/jscript.js"></script> 

	<title>Snippetboxx.com: <?php echo $title; ?></title>
</head>
<body>
	<div id="container">
	<div id="header">
			<div id="header_menu">
				<ul class="header_menu_ul">			
					<?php
						//if($this->session->userdata('login_state') == FALSE){
						
						$user_array = explode(", ", $this->input->cookie('user_tracker_info', TRUE));
						$username = $user_array[0];
						if(isset($username) && $this->session->userdata('login_state') == TRUE){
					?>					
					<li id="<?php echo base64_encode($username); ?>" class="header_menu_li header_username">
						<a href="#"><?php echo $username; ?></a>
					</li>	
					<?php } ?>					

					<?php if($show_login){ ?>
					
					<?php if($this->session->userdata('login_state') == FALSE){ ?>
						<li class="header_menu_li header_login">
							<a href="<?= base_url() ?>login/"> login</a>
							<?php if($show_signup){ ?>
						</li>	
						<li class="header_menu_li header_signup">
							<a href="<?= base_url() ?>signup/">sign-up</a>
						<?php }?>					
										
						<?php } else { ?>
					<li class="header_menu_li header_logout">	
						<a href="<?= base_url() ?>logout/"> logout</a>
						<?php } ?>						
					</li>
					<?php }?>				


						
					<li class="header_menu_li header_home">
						<a href="<?= base_url() ?>"> home</a>
					</li>											
					<?php if($show_about){ ?>
					<li class="header_menu_li header_about">
						<a href="#">about</a>
					</li>
					<?php }?>
					<li class="header_menu_li header_tags">
						<a href="#">tags</a>
					</li>
					<?php if($show_log){ ?>
					<li class="header_menu_li header_changelog">
						<a href="<?= base_url() ?>changelog/">changelog</a>
					</li>
					<?php }?>
					<li class="header_menu_li software_version">
						(<?php if(isset($software_version)){echo $software_version;} ?>)
					</li>
					<li class="header_menu_li">
						&nbsp;
					</li>
				</ul>
			</div>
		<div id="inner_header">
		<a href="<?= base_url() ?>" border="0"><img id="sniplet_logo" src="<?= base_url() . $site_logo ?>" alt="Snippetboxx.com" border="0" /></a>
		<?php $this->load->view('/frontend/search', null); ?>
			<div id="sniplet_messager">				
			</div>
		
		</div>
	</div>



