<?php

//stuff to insert the data into mssql
//note: I created this instead of using the functions in lot_specifics.php since that all seemed to be geared towards MySQL.  --rjr


//LIFECODES FUNCTIONS
//////////////////////////////////////////

function saveParent($args) {
    global $dbhandle;
    $filename = $args['filename'];
    $result = dbQuery("SELECT * FROM lc_parents WHERE lcp_filename = '".dbEscape($filename)."'");
    $row = mysql_fetch_array($result);
    if ($row['lcp_id'] > 0) { return $row['lcp_id']; }
    else {
        dbQuery("INSERT INTO lc_parents (lcp_filename) VALUES ('".dbEscape($filename)."')");
        $result = dbQuery("SELECT * FROM lc_parents WHERE lcp_filename = '".dbEscape($filename)."'");
        $row = mysql_fetch_array($result);
        return $row['lcp_id'];
    }
}

function saveCatalog($args) {
    global $dbhandle;
    $cat_no = $args['cat_no'];
    $lcp_id = $args['lcp_id'];
    if (!$cat_no) { return false; }
    if (!$lcp_id) { return false; }
    $result = dbQuery("SELECT * FROM lc_catalog WHERE cat_no = '".dbEscape($cat_no)."'");
    $row = mysql_fetch_array($result);
    if ($row['cat_id'] > 0) {
        if ($row['lcp_id'] != $lcp_id) { die("<p>saveCatalog() error: \$lcp_id doesn't match. ($lcp_id, $cat_no)</p>"); }
        else { return $row['cat_id']; }
    } else {
        dbQuery("INSERT INTO lc_catalog (cat_no,lcp_id) VALUES ('".dbEscape($cat_no)."','".dbEscape($lcp_id)."')");
        $result = dbQuery("SELECT * FROM lc_catalog WHERE cat_no = '".dbEscape($cat_no)."'");
        $row = mysql_fetch_array($result);
        return $row['cat_id'];
    }
}

function saveLot($args) {
    global $dbhandle;
    //echo "<pre>"; print_r($args); echo "</pre>";
    $lcp_id = $args['lcp_id'];
    $lot_name = $args['lot_name'];
    $lot_no = $args['lot_no'];
    $lot_expiration = $args['lot_expiration'];
    $cat_id = $args['cat_id'];
    if (!$lcp_id) { return false; }
    if (!$lot_name) { return false; }
    if (!$lot_no) { return false; }
    if (!$lot_expiration) { return false; }
    if (!$cat_id) { return false; }
    $query = "SELECT * FROM lc_lots WHERE lcp_id = '".dbEscape($lcp_id)."' AND lot_name = '".dbEscape($lot_name)."' AND lot_no = '".dbEscape($lot_no)."'";
    $result = dbQuery($query);
    $row = mysql_fetch_array($result);
    if ($row['lot_id'] > 0) { return $row['lot_id']; }
    else {
        $query = "INSERT INTO lc_lots (lot_name,lot_no,cat_id,lot_expiration,lcp_id) VALUES ('".dbEscape($lot_name)."','".dbEscape($lot_no)."','".dbEscape($cat_id)."','".dbDate($lot_expiration)."','".dbEscape($lcp_id)."')";
        dbQuery($query);
        $result = dbQuery("SELECT * FROM lc_lots WHERE lcp_id = '".dbEscape($lcp_id)."' AND lot_name = '".dbEscape($lot_name)."' AND lot_no = '".dbEscape($lot_no)."'");
        $row = mysql_fetch_array($result);
        return $row['lot_id'];
    }
}

function saveDocumentType($args) {
    global $dbhandle;
    $dt_code = $args['dt_code'];
    $dt_column = $args['dt_column'];
    if (!$dt_code) { return false; }
    if (!$dt_column) { return false; }
    $result = dbQuery("SELECT * FROM lc_doctypes WHERE dt_code = '".dbEscape($dt_code)."' AND dt_column = '".dbEscape($dt_column)."'");
    $row = mysql_fetch_array($result);
    if ($row['dt_id'] > 0) { return $row['dt_id']; }
    else {
        dbQuery("INSERT INTO lc_doctypes (dt_column,dt_code) VALUES ('".dbEscape($dt_column)."','".dbEscape($dt_code)."')");
        $result = dbQuery("SELECT * FROM lc_doctypes WHERE dt_code = '".dbEscape($dt_code)."' AND dt_column = '".dbEscape($dt_column)."'");
        $row = mysql_fetch_array($result);
        return $row['dt_id'];
    }
}

function saveDocument($args) {
    global $dbhandle;
    $dt_code = $args['dt_code'];
    $dt_column = $args['dt_column'];
    $doc_filename = $args['doc_filename'];
    $doc_filepath = $args['doc_filepath'];
    $lot_id = $args['lot_id'];
    if (!$doc_filename) { return false; }
    if (!$doc_filepath) { return false; }
    if (!$lot_id) { return false; }
    $dt_id = saveDocumentType($args);
    if (!$dt_id) { echo "<p>Error! Could not generate \$dt_id.</p>"; return false; }
    $result = dbQuery("SELECT * FROM lc_documents WHERE dt_id = '".dbEscape($dt_id)."' AND doc_filename = '".dbEscape($doc_filename)."' AND doc_filepath = '".dbEscape($doc_filepath)."'");
    $row = mysql_fetch_array($result);
    if ($row['doc_id'] > 0) { $doc_id = $row['doc_id']; }
    else {
        dbQuery("INSERT INTO lc_documents (dt_id,doc_filename,doc_filepath) VALUES ('".dbEscape($dt_id)."','".dbEscape($doc_filename)."','".dbEscape($doc_filepath)."')");
        $result = dbQuery("SELECT * FROM lc_documents WHERE dt_id = '".dbEscape($dt_id)."' AND doc_filename = '".dbEscape($doc_filename)."' AND doc_filepath = '".dbEscape($doc_filepath)."'");
        $row = mysql_fetch_array($result);
        $doc_id = $row['doc_id'];
    }
    $result = dbQuery("SELECT * FROM lc_relationships WHERE doc_id = '".dbEscape($doc_id)."' AND lot_id = '".dbEscape($lot_id)."'");
    $row = mysql_fetch_array($result);
    if (!$row['doc_id']) {
        dbQuery("INSERT INTO lc_relationships (doc_id,lot_id) VALUES ('".dbEscape($doc_id)."','".dbEscape($lot_id)."')");
    }
    return $doc_id;
}


//GENERAL CONNECTION/QUERY/ETC. FUNCTIONS
//////////////////////////////////////////

//php/mssql returns true on empty result, false on error, so i designed this:
function dbQuery($query) {
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}

//I found this on http://php.net/manual/en/function.mssql-query.php  --rjr
//there is no built-in escape function for mssql
function dbEscape($sql) { 
    return mysql_real_escape_string($sql); 
}

function dbConnect() {

    $myServer = "localhost";
    $myUser = "rjr";
    $myPass = "4CWTqGGmyrL3wCUr";
    $myDB = "genpro";
    
    //connection to the database
    global $dbhandle;
    $dbhandle = mysql_connect($myServer, $myUser, $myPass)
      or die("Couldn't connect to SQL Server on $myServer");
    
    //select a database to work with
    global $selected;
    $selected = mysql_select_db($myDB, $dbhandle)
      or die("Couldn't open database $myDB");
}

function dbClose() {
    global $dbhandle;
    mysql_close($dbhandle) or die("Couldn't close MSSQL connection!");
}

//gets time in proper string for MSSQL
function dbDate($string) {
    $time = strtotime($string);
    return date('Y-m-d H:i:s',$time);
}

