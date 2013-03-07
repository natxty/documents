<?php

//this doesn't add a ton of security since this code is visible to user, but it helps a little...
if ($_REQUEST['auth'] != 'ZG5IxGRGtwlLTNfW') { die('Unauthorized.'); }

//connect to the database
define('BASEPATH', '../../'); //just setting this so that CodeIgniter won't kill access to the DB info
require '../../application/config/database.php';
$dbcerror = false;
$link = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']);
if (!$link) { $dbcerror = true; }
mysql_select_db($db['default']['database'], $link) or ($dbcerror = true);
if ($dbcerror) { die('Could not connect to database!'); }
unset($db);

$i = 0;
foreach ($_POST as $key => $array) {
    if (in_array(substr($key,0,3),array('lot','doc'))) {
        $i++;
        $type = substr($key,0,3);
        $id = intval(substr($key,3));
        if (!$id) { die("No ID for spot #".$i."."); }
        $old_order = intval($array[0]); //this isn't really used...
        $new_order = $i;
        $success = updateOrder($type,$id,$new_order);
        if (!$success) { die("Could not save the order for spot #".$i."."); }
    }
}

//let the client know that everything worked
echo 'Success';

function updateOrder($type,$id,$order) {
    global $link;
    if ($id < 1) { return false; }
    if ($order < 1) { return false; }
    $error = false;
    if ($type == 'lot') { $query = "UPDATE `lc_lots` SET `order` = '$order' WHERE `lot_id` = '$id' LIMIT 1"; } //this updates both live and preview lots (since the order of lots is not a previewable element)
    elseif ($type == 'doc') { $query = "UPDATE `lc_documents` SET `order` = '$order' WHERE `doc_id` = '$id' AND `status` = 'p' LIMIT 1"; } //this is saving the order only on the preview version of the document row
    else { die('No order type selected.'); }
    mysql_query($query,$link) or ($error = mysql_error());
    if ($error) { echo $query.' | '.$error; return false; }
    else { return true; }
}
?>