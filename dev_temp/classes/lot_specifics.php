<?php

/*
 * Database Info
 */


$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'genpro';
	
/*
 * Utilities
 */

/**
 * remove specified $tag form $content
 */
function removeTag($tag, $content) {
	//$pat = '/<'.$tag.'.*>.*<\/'.$tag.'>/s';
	$pat = '@<'.$tag.'[^>]*?.*?</'.$tag.'>@siu';
	return preg_replace($pat, '',  $content);
}

/*
 * Data for parsing....
 */

$columns = array(
    'catalog number',
    'LIFECODES product',
    'Lot#',
    'Cert.',
    'TT/RS',
    'Panel', 
    'Probe Hit Charts',
    'Core Seq',
    'Exp.');

$tcols = array('cat_no', 'name', 'lot_no', 'cert', 'tt/rs', 'panel', 'probe_hit', 'core', 'exp', 'docs'); //temp table columns, indexed by occurrence on page

$dcols = array('temp_id', 'doc_name', 'doc_filepath', 'doc_filename'); //document table columns




function parseString($string) {
        $pstring1 = "'[^']*'";
        $pstring2 = '"[^"]*"';
        $pnstring = "[^'\">]";
        $pintag   = "(?:$pstring1|$pstring2|$pnstring)*";
        $pattrs   = "(?:\\s$pintag){0,1}";

        $pcomment = $this->enclose("<!--", "-", "->");
        $pscript  = $this->enclose("<script$pattrs>", "<", "\\/script>");
        $pstyle   = $this->enclose("<style$pattrs>", "<", "\\/style>");
        $pexclude = "(?:$pcomment|$pscript|$pstyle)";
        $ptitle   = $this->enclose("<title$pattrs>", "<", "\\/title>");
        $panchor  = "<a(?:\\s$pintag){0,1}>";
        $phref    = "href\\s*=[\\s'\"]*([^\\s'\">]*)";
        
        //find full html anchor:
        preg_match("/$panchor/iX", $string, $anchor);
        preg_match("/$phref/iX", $string, $href);
        
        return $href;
}

//i commented out the mysql stuff to avoid duplicate function names --rjr

/*function insertRecord($table, $data) {
		
	//init sql strings
	$inserts = "insert into $table (";
	$values = "values (";
	
	//flatten array into necessary key/value pairs:
	foreach($data as $key => $value) {
		$inserts .= "$key, ";
		$value = strClean($value); //clean up the insert!
		$values .= "'$value', ";
	}
	
	//cap off sql strings
	$inserts = substr($inserts, 0, -2); //carve off last 2 (comma + space)
	$inserts .= ") "; //keep space!
	$values = substr($values, 0, -2); //carve off last 2 (comma + space)
	$values .= ")";
	
	//perform the query, get result object or error
	$query = $inserts.$values;
	//echo $query."<br />\n\n";
	$result = mysql_query($query) or $error = mysql_error();
	//echo $result;
	//construct the return packet
	if(!$error) {
		$return['status'] = 'success';
		$return['insert_id'] = mysql_insert_id();
	} else {
		$return['status'] = 'error';
		$return['message'] = $error;
	}
	
	return $return;
	
}

function connectDB($dbhost,$dbuser,$dbpass,$dbname) {
	    
    $link = mysql_connect($dbhost,$dbuser,$dbpass);
    if(!$link) {
        $output['success'] = FALSE;
        $output['message'] = mysql_error();
        return $output;
    }
    
    $dbselect = mysql_select_db($dbname, $link);
    if(!$dbselect) {
        $output['success'] = FALSE;
        $output['message'] = mysql_error();
        return $output;
    }
    
    $output['success'] = TRUE;
    $output['message'] = '';
    return $output;
}

function dbError($error=false,$query=false,$debug=false) {
    echo "<p><strong>Error: Database error!</strong></p>\n" ;
    if ($debug) { echo "<p>Query: $query</p>\n<p>Error: $error</p>\n" ; }
    die();
}

function dbQuery($query,$ignore_errors=false) {

    if ($ignore_errors) { $result = mysql_query($query); }
    else { $result = mysql_query($query) or die($this->dbError(mysql_error(),$query)); }
    return $result;
}



function dbGetArray($query,$ignore_errors=false,$amode='assoc') {
    ($amode=='num') ? $mode = MYSQL_NUM : $mode = MYSQL_ASSOC;
    $result = $this->dbQuery($query,$ignore_errors);
    $a = array();
    while ($row = mysql_fetch_array($result, $mode)) {
        $a[] = $row;
    }
    return $a;
}


function dbNumRows($result) {
    $numrows = mysql_num_rows($result);
    return $numrows;
}

function dbAffectedRows() {
    $arows = mysql_affected_rows();
    return $arows;
}

function dbInsertId() {
    $insert_id = mysql_insert_id();
    return $insert_id;
}*/


function strClean($string) {
    //clean linebreaks
    $string = cleanlb($string);
    
	//if magic_quotes_gpc is enabled, we'll need to strip extra slashes...
	if(get_magic_quotes_gpc() == 1) {
		$string = stripslashes($string);
	}
	return mysql_real_escape_string($string);	
}

?>