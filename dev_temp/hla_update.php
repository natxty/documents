<?php

//includes
include_once '../_shared/bot/LIB_parse.php';
include_once '../_shared/bot/cleaners.php';
include_once 'classes/lot_specifics.php';

/*
 * Set Up Regex Patterns
 */

$pstring1 = "'[^']*'";
$pstring2 = '"[^"]*"';
$pnstring = "[^'\">]";
$pintag   = "(?:$pstring1|$pstring2|$pnstring)*";
$pattrs   = "(?:\\s$pintag){0,1}";

$panchor  = "<a(?:\\s$pintag){0,1}>";
$phref    = "href\\s*=[\\s'\"]([^\"]*)([^\\s'\"\>]*)";
                
/*
 * Initial Page Load
 */
$folderpath = './datapages/';
$filename = $folderpath."lp-hla.aspx";
$file = file_get_contents($filename);

echo "<h2>$filename</h2>\n";
/*
 * Trim down to the Lot Specific
 */
$file = return_between($file, '<div class="tab" id="lotspecific">', '</div>', EXCL);

/*
 * Remove the extra style tags, if included...
 */
$match = return_between($file, '<style>', '</style>', INCL);
$file = str_replace($match, '', $file);



/*
 * Parse into chunks by table row...
 */

$rows = parse_array($file, '<tr', '</tr>');

/*
 * Break into individual TDs and do some parsing...
 */
foreach($rows as $index => $tr) {
    
    if($index == 0 ) { continue; }
    $tds = parse_array($tr, '<td', '</td>');
    
    foreach($tds as $key => $value) {
        //get rid of the tds...
        $value = preg_replace('(\<((\/)*)td(.*)\>)sUi', '', $value);
        
        if(stristr($value, 'href')) {
            /*
             * Pull out the link and anchor text...
             */
            $links = parse_array($value, '<a', '</a>');
            
            $newValue = '';
            foreach($links as $x => $result) {
               
                //strip to reveal the link text
                $anchor = strip_tags($result);
                
                //find full href (ref regex libs above):
                preg_match("/$phref/iX", $result, $link);
                $linkref = $link[1];
                $newValue .= "<a href=\"$linkref\">$anchor</a><br />";
                    
            }
            
            $value = $newValue;
            
        }
        
        $cKey = $columns[$key];
        $data[$index][$cKey] = $value;

    }
}

//$content = $data;

/*
 * DB Operations, temp
 */



$connex = connectDB($dbhost, $dbuser, $dbpass, $dbname);
if(!$connex) { die("failed DB connect"); }

/*

foreach($data as $key => $array) {
    //build our insert
    $insert['cat_no'] = $array['catalog number']; 	
	$insert['name'] = $array['LIFECODES product'] ;	
	$insert['lot_no'] = $array['Lot#'];
	$insert['cert'] = $array['Cert.'];
	$insert['tt_rs'] = $array['TT/RS']; 	
	$insert['panel'] = $array['Panel'];
	$insert['probe_hit'] = $array['Probe Hit Charts'];
	$insert['core_seq'] = $array['Core Seq'];
	$insert['exp'] = $array['Exp.'];
	
	$sql = insertRecord('temp', $insert);
	
	if($sql['status'] == 'success') {
	    $pre_content .= "Successfully inserted Lot# ".$insert['lot_no'].", insert id ".$sql['insert_id']."<br />\n";
	} else {
	    $pre_content .= 'Error: '.$sql['message']."<br />\n";
	}
}

*/




$nLots[] = array('lot_no' => '02179E', 'pdf' => 'LC711CWD-HLA-A CWD Probe Hit Table DB3_2_0 02020CR_02179E.pdf', 'type' => '3.2.0 CWD');
$nLots[] = array('lot_no' => '02179E', 'pdf' => 'LC711-HLA-A Probe Hit Table DB3_2_0 02020C_02179E.pdf', 'type' => '3.2.0');


$nLots[] = array('lot_no' => '02020C', 'pdf' => 'LC711-HLA-A Probe Hit Table DB3_2_0 02020C_02179E.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '02020C', 'pdf' => 'LC711CWD-HLA-A CWD Probe Hit Table DB3_2_0 02020CR_02179E.pdf', 'type' => '3.2.0 CWD');



$nLots[] = array('lot_no' => '08060C', 'pdf' => 'LC711-HLA-A Probe Hit Table DB3_2_0 02020C_02179E.pdf', 'type' => '3.2.0 HLA-A');
$nLots[] = array('lot_no' => '08060C', 'pdf' => 'LC1048-HLA-A eRES Probe Hit Table DB3_2_0 03160BR.pdf', 'type' => '3.2.0 eRES');
$nLots[] = array('lot_no' => '08060C', 'pdf' => 'LC1048CWD-HLA-A eRES CWD Probe Hit Table DB3_2_0 03160BR.pdf', 'type' => '3.2.0 eRES CWD');


$nLots[] = array('lot_no' => '06080N', 'pdf' => 'LC712-HLA-B Probe Hit Table DB3_2_0 06080N_11180A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '06080N', 'pdf' => 'LC712CWD-HLA-B CWD Probe Hit Table DB3_2_0 06080N_11180A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '11180A', 'pdf' => 'LC712-HLA-B Probe Hit Table DB3_2_0 06080N_11180A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '11180A', 'pdf' => 'LC712CWD-HLA-B CWD Probe Hit Table DB3_2_0 06080N_11180A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '10140D', 'pdf' => 'LC796-HLA-C Probe Hit Table DB3_2_0 10140D_12100B.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '10140D', 'pdf' => 'LC796CWD-HLA-C CWD Probe Hit Table DB3_2_0 10140D_12100B.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '12100B', 'pdf' => 'LC796-HLA-C Probe Hit Table DB3_2_0 10140D_12100B.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '12100B', 'pdf' => 'LC796CWD-HLA-C CWD Probe Hit Table DB3_2_0 10140D_12100B.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '09130B', 'pdf' => 'LC657-HLA-DRB Generic Probe Hit Table DB3_2_0_09130BR.pdf', 'type' => 'Generic 3.2.0');
$nLots[] = array('lot_no' => '09130B', 'pdf' => 'LC657CWD-HLA-DRB (CWD) Generic Probe Hit Table DB3_2_0_09130BR.pdf', 'type' => 'Generic 3.2.0 CWD');
$nLots[] = array('lot_no' => '09130B', 'pdf' => 'LC658-HLA-DRB DR52 Probe Hit Table DB3_2_0_09130B.pdf', 'type' => 'DR52 3.2.0');
$nLots[] = array('lot_no' => '09130B', 'pdf' => 'LC658CWD.4-HLA DR52 (CWD) Probe Hit Table DB3_2_0_09130B.pdf', 'type' => 'DR52 3.2.0 CWD');

$nLots[] = array('lot_no' => '02220B', 'pdf' => 'LC830-HLA-DRB1 Probe Hit Table DB3_2_0 02220B_12130A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '02220B', 'pdf' => 'LC830CWD-HLA-DRB1 CWD Probe Hit Table DB3_2_0 02220BR_12130A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '12130A', 'pdf' => 'LC830-HLA-DRB1 Probe Hit Table DB3_2_0 02220B_12130A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '12130A', 'pdf' => 'LC830CWD-HLA-DRB1 CWD Probe Hit Table DB3_2_0 02220BR_12130A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '09280A', 'pdf' => 'LC836-HLA-DPB1 Probe Hit Table DB3_2_0 09280A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '09280A', 'pdf' => 'LC836CWD-HLA-DPB1 CWD Probe Hit Table DB3_2_0 09280A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '01130Y', 'pdf' => 'LC700-HLA-DQB1 Probe Hit Table DB3_2_0 01130Y.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '01130Y', 'pdf' => 'LC700CWD-HLA-DQB1 CWD Probe Hit Table DB3_2_0 01130Y.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '05219A', 'pdf' => 'LC959-HLA-DQA1 Probe Hit Table DB3_2_0 05219A_06290A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '05219A', 'pdf' => 'LC959CWD-HLA-DQA1 CWD Probe Hit Table DB3_2_0 05219A_06290A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '06290A', 'pdf' => 'LC959-HLA-DQA1 Probe Hit Table DB3_2_0 05219A_06290A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '06290A', 'pdf' => 'LC959CWD-HLA-DQA1 CWD Probe Hit Table DB3_2_0 05219A_06290A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '08160A', 'pdf' => 'LC1044-HLA-DRB345 Probe Hit Table DB3_2_0 08160A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '08160A', 'pdf' => 'LC1044CWD-HLA-DRB345 CWD Probe Hit Table DB3_2_0 08160A.pdf', 'type' => '3.2.0 CWD');

$nLots[] = array('lot_no' => '08160ACE', 'pdf' => 'LC1044-HLA-DRB345 Probe Hit Table DB3_2_0 08160A.pdf', 'type' => '3.2.0');
$nLots[] = array('lot_no' => '08160ACE', 'pdf' => 'LC1044CWD-HLA-DRB345 CWD Probe Hit Table DB3_2_0 08160A.pdf', 'type' => '3.2.0 CWD');


$xLots = array_reverse($nLots);

/*

foreach($xLots as $x => $lotarray) {
        
    $atext = $lotarray['type'];
    
    $path = '/pdfs/lot_specific/';
    
    $q = "select * from `temp` where lot_no = '".$lotarray['lot_no']."'";
    $r = mysql_query($q) or die( mysql_error() );
    
    while($s = mysql_fetch_object($r)) {
        $lot_no = $lotarray['lot_no'];
        
        $prev = $s->probe_hit;
        
        $newlink = '<a href="'.$path.$lotarray['pdf'].'">'.$atext.'</a>';
        
        $new = $newlink.'<br />'.$prev;
        
        //echo $lot_no.": ".$new;
        $new = strClean($new);
        $nq = "UPDATE `temp` SET probe_hit = '$new' WHERE lot_no = '$lot_no'";
        $nr = mysql_query($nq) or die (mysql_error() );
        
        //check:
        
    }
   // echo "<hr />\n";
}

*/


/* Final Load.Check */


$qa = 'select * from `temp`';
$qr = mysql_query($qa) or die( mysql_error() );
while($qs = mysql_fetch_array($qr)) {
	$return[] = array(
	'catalog number' =>  $qs['cat_no'],
	'LIFECODES product' => $qs['name'],	
	'Lot#' => $qs['lot_no'],
	'Cert.' => $qs['cert'],
	'TT/RS' => $qs['tt_rs'], 	
	'Panel' => $qs['panel'],
	'Probe Hit Charts' => $qs['probe_hit'],
	'Core Seq' => $qs['core_seq'],
	'Exp.' => $qs['exp']
	);
}

$content = $return;


/*
 * Load template for view
 */

//include 'templates/default.php';
include 'templates/hla.php';

?>