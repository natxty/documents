<?php

//simply prints out the cleaned up version of the filename (with the . replaced by __)
//called via ajax from edit_docs.php
require_once '../../application/helpers/cleanup_filename_helper.php';
echo cleanup_filename($_REQUEST['filename'],true);

?>