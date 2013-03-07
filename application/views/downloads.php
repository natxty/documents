<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?= $sitetitle; ?></title>
<link rel="stylesheet" href="<?= $base_url; ?>css/reset.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/text.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/960.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/lifecodes.css" />

<!--// jQuery -->
<script type="text/javascript" src="<?= $base_url; ?>scripts/uploadify/jquery-1.4.2.min.js"></script>

<!--// jQuery UI, scripts and css -->
<script type="text/javascript" src="<?= $base_url; ?>scripts/jquery-ui-1.8.12.custom.min.js"></script>
<link rel="stylesheet" href="<?= $base_url; ?>_shared/css/nsc_smooth/jquery-ui-1.8.12.custom.css" type="text/css" />

<!--// jQuery Uploadify, scripts and css -->
<script type="text/javascript" src="<?= $base_url; ?>scripts/uploadify/swfobject.js"></script>
<script type="text/javascript" src="<?= $base_url; ?>scripts/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
<link rel="stylesheet" href="<?= $base_url; ?>scripts/uploadify/uploadify.css" type="text/css" />


<script type="text/javascript">
$(document).ready(function() {
	
	$( "#uploadform" ).dialog({
		autoOpen: false,
		height: 200,
		width: 450,
		modal: true,
		
		buttons: {
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
		
	});
	
	$( "#mkdirform" ).dialog({
		autoOpen: false,
		height: 200,
		width: 450,
		modal: true,
		
		buttons: {
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
		
	});
	
  
	$("#uploadbutton").button().click(function() {
		$("#uploadform").dialog("open");
	});
	
	$("#mkDirButton").button().click(function() {
		$("#mkdirform").dialog("open");
	});
	
	$("#mkDirSubmit").button().click(function() {
		//cool stuff								  
	});
	

	$(".delete").click(function() {
		var path = $(this).attr("href");
		confirmDelete(path);
		return false;
	});	
  
});

function confirmDelete(path) {
	/* Launch the Confirm Modal Dialog */
	$("#dialog-confirm").dialog({
		autoOpen: false,
		resizable: false,
		height:190,
		width:300,
		modal: true,
		close: function() {
			window.location.href = document.location.href; 
		},
		buttons: {
			"Delete": function() {
					/* add function to delete // AJAXify */
					$.ajax({
						type	: "POST",
						cache	: false,
						url		: "<?= site_url("/downloads/delete") ?>",
						data	: "path=" + path,
						success: function(data) {
							/* Change the Form HTML to the message from script */
							$("#dialog-confirm").html(data);
							/* If we're not successful */
							if(data.indexOf("SUCCESS") >= 0) {
								
							} else {
								/* else we are!! */
								$("#dialog-confirm").dialog('close');
							}
						}
					});
			},
			Cancel: function() {
					$(this).dialog("close");
					return false;
			}
		}
	});
	
	$("#dialog-confirm").dialog('open');
	
}
</script>

<?php if (@$head) { echo $head; } ?>
</head>
<body>
<div class="container_12">

    <div id="header">
    	<img src="<?= $base_url; ?>images/hdr_gp_admin.jpg" alt="Gen Probe LIFECODES Lot Admin" width="" height="" />
    </div><!--// end #header -->
    
    <div id="pagewrap">
    	<div id="innerwrap">
            <?php
            if ($title) { echo "<h1>$sitetitle &raquo; $title</h1>\n"; }
            else { echo "<h1>$sitetitle</h1>"; }
            
            echo $nav_menu;
            
            if (@$error) { echo "<p class=\"alert\">Error: $error</p>"; }
            if (@$errors) { echo "<p class=\"alert\">$errors</p>"; }
            if (@$message) { echo "<p class=\"status_message\">$message</p>"; }
            //if (@$GLOBALS['debug']) { echo "<div style=\"border:2px blue solid;padding:8px;\">".$GLOBALS['debug']."</div>"; }
            
            echo @$content;
            ?><!--// end #pagewrap -->
		</div><!--// end #innerwrap -->
	</div>

</div>
</body>
</html>