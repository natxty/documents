<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">

<title><?=$pagetitle?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 	

<!-- // loading the Blueprint CSS Framework -->
<link rel="stylesheet" href="/_shared/css/blueprint/screen.css" type="text/css" media="screen, projection" />
<link rel="stylesheet" href="/_shared/css/blueprint/css/print.css" type="text/css" media="print" />

<!-- // loading the Page Specific CSS  -->
<link rel="stylesheet" href="assets/css/screen.css" type="text/css" media="screen, projection" />

<!-- // extra IE overrides -->
<!--[if lt IE 8]><link rel="stylesheet" href="/_shared/css/blueprint/css/ie.css" type="text/css" media="screen, projection"><![endif]-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script>
    $(document).ready(function() {
    	//good stuff here
    });
</script>

</head>

<body>

<div id="top">
      <div id="header">

          <a id="logo" href=""><?=$pageheader?></a>
         

    </div><!--/#header -->
</div><!--/#top -->

<div class="container">

	<?php 
	if(is_array($content)) {
	    print "<pre>\n";
	    print_r($content); 
	    print "</pre>\n";
	}
	else {
	    echo $content;
	}
	?>
       
</div>

</body>

</html>
