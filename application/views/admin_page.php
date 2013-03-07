<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?= $sitetitle; ?></title>
<link rel="stylesheet" href="<?= $base_url; ?>css/reset.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/text.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/960.css" />
<link rel="stylesheet" href="<?= $base_url; ?>css/lifecodes.css" />
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