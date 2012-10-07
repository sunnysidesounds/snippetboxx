
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<!--Backend CSS-->
	<link rel='stylesheet' type='text/css' href='<?= base_url() ?>css/styles.css' />
	<link rel="stylesheet" href="<?= base_url() ?>js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?= base_url() ?>css/smoothness/jquery-ui.css" type="text/css" media="screen" />

	<!--Backend JS -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.scrollTo.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.ui.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/validation.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>js/scrollpagination.js"></script>
	
	


	<script type="text/javascript">		
		CI_ROOT = "<?=base_url() ?>";	
		CI_SITE =  "<?=site_url() ?>";	
	</script>
	<script type="text/javascript" src="<?= base_url() ?>js/jscript.js"></script> 

	<title>Snippetboxx.com: <?php echo $title; ?></title>
</head>
<body>
	<div id="container">
	<div id="bk_header">
			<div id="bk_header_menu">
				<ul class="bk_header_menu_ul">
					<li class="bk_header_menu_li header_home">
						<a href="<?= base_url() ?>"> home</a>
					</li>					
					<?php if($show_log){ ?>
					<li class="bk_header_menu_li header_changelog">
						<a href="<?= base_url() ?>base/changelog/">changelog</a>
					</li>
					<?php }?>
					<li class="bk_header_menu_li software_version">
						(<?php if(isset($software_version)){echo $software_version;} ?>)
					</li>
					<li class="bk_header_menu_li">
						&nbsp;
					</li>
				</ul>
			</div>
		<div id="bk_inner_header">
		<a href="<?= base_url() ?>" border="0"><img id="sniplet_logo" src="<?= base_url() . $site_logo ?>" alt="Snippetboxx.com" border="0" /></a>
		<?php $this->load->view('/backend/messager', null); ?>
			<div id="bk_sniplet_messager">				
			</div>
		
		</div>
	</div>



