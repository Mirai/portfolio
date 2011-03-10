<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>
<title>Buzy Bee Inc. | Quick Pak Bags</title>
<?= css_asset('reset.css') ?>
<?= css_asset('styles.css') ?>

<!-- JQUERY -->
<script type="text/javascript" src="/assets/js/jquery-1.3.2.min.js"></script>

<!-- LIGHTBOX -->
<link rel="stylesheet" href="/assets/js/prettyphoto/css/prettyPhoto.css" type="text/css" media="screen" charset="utf-8" />
<script src="/assets/js/prettyphoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

<!-- REMOVE CONFLICTS W/NON-JQUERY LIBS -->
<script type="text/javascript">jQuery.noConflict();</script>

<!-- VIDEO OVERLAY -->
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	jQuery("area[rel^='prettyPhoto']").prettyPhoto();
});
</script>

<?php
if(empty($_POST)) { ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery.prettyPhoto.open('/assets/swf/mediaplayer.swf?file=video.flv&autostart=true');
		});
	</script>
<?php
} ?>

<!--SLIDESHOW JS-->
<?= js_asset('fadeslideshow.js') ?>
<script type="text/javascript">
		var fadeSlideShow_descpanel={
			controls: [		//full URL and dimensions of close, restore, and loading images
					['/assets/js/fadeslideshow_images/x.png',7,7], 
					['/assets/js/fadeslideshow_images/restore.png',10,11], 
					['/assets/js/fadeslideshow_images/loading.gif',54,55]
			], 
			fontStyle: 'normal 11px Verdana', //font style for text descriptions
			slidespeed: 200 //speed of description panel animation (in millisec)
		}
		var mygallery=new fadeSlideShow({
			wrapperid: "fadeshow1", //ID of blank DIV on page to house Slideshow
			dimensions: [553, 410], //width/height of gallery in pixels. Should reflect dimensions of largest image
			imagearray: [
				["/assets/image/black_bag.jpg"],
				["/assets/image/blue_bag.jpg"],
				["/assets/image/pink_bag.jpg"],
				["/assets/image/camo_bag.jpg"]
			],
 			displaymode: {type:'auto', pause:8500, cycles:0, wraparound:false}
// 			persist: false, //remember last viewed slide and recall within same session?
// 			fadeduration: 500, //transition duration (milliseconds)
// 			descreveal: "ondemand",
// 			togglerid: ""
		});	
</script>
<!--(END SLIDESHOW JS)-->


</head>
<body>

    <DIV ID="CONTAINER">
		
		<?= $this->load->view('main/header.php') ?><DIV style="clear:both"></DIV>		
		
		<DIV ID="CONTENT"><?= $this->load->view($page) ?></DIV>
        
    	<?= $this->load->view('main/footer.php') ?>
     
         
    </DIV>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-2607830-22");
pageTracker._trackPageview();
} catch(err) {}</script>
    
</body>
</html>