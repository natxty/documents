<?php
die("This has already been run and isn't really needed anymore.");
//includes
include_once 'classes/LIB_parse.php';
include_once 'classes/cleaners.php';
include_once 'classes/lot_specifics.php';
include_once 'classes/mssql_storage.php';


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
 * Initial Pages Load
 */

$files = '';
$path = './datapages/';

// Open the folder
$dir_handle = @opendir($path) or die("Unable to open $path");

// Loop through the files
while ($file = readdir($dir_handle)) {
	if($file == "." || $file == ".." || $file == "index.php")
		continue;
	if(is_dir($file))
		continue;
	
	
	$files[] = $file;
	
}

// Close
closedir($dir_handle);

//echo "<pre>"; print_r($files); die();

foreach($files as $xx => $filename) {
    
    
    $file = file_get_contents($path.$filename);
    
    //echo "<h2>$filename</h2>\n";
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
            
            //establish our column key
            $cKey = $tcols[$key]; //theoretically, should only match up with the non-document indexes
            
            //if this td is one of our doc columns, we need to break them out individually
            if($key >= 3 && $key <= 7) {
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
                    $linkfull = explode("/", $link[1]);
                    $_filename = $linkfull[(count($linkfull) - 1)];
                    //i removed the strClean because it was returning an empty value  --rjr
                    //$_filename = strClean($_filename);
                    $_filepath = '';
                    for($i=0;$i<(count($linkfull) - 1);$i++) {
                        $_filepath .= $linkfull[$i]."/";
                    }
                    //add to our data array
                    $data[$filename][$index]['docs'][] = array('doc_type' => $cKey, 'doc_name' => $anchor, 'doc_filepath' => $_filepath, 'doc_filename' => $_filename);     
                }
                //end link subroutine
            
            } else {
                
                $data[$filename][$index][$cKey] = $value;
            }
            
            
    
        }
    }

}

dbConnect();
echo "<p>Connected to DB...</p>";

foreach ($data as $filename => $lots) {
    $lcp_id = saveParent(array('filename'=>$filename));
    if (!$lcp_id) { echo "<p>Error! Couldn't save parent: $filename</p>"; }
    else {
        echo "<p>filename: $filename, lcp_id: $lcp_id</p>";
        foreach ($lots as $lot) {
            //echo "<pre>"; print_r($lot); echo "</pre>";
            $cat_id = saveCatalog(array('cat_no'=>$lot['cat_no'],'lcp_id'=>$lcp_id));
            $lot_id = saveLot(array('lot_name'=>$lot['name'],'lot_no'=>$lot['lot_no'],'lot_expiration'=>$lot['exp'],'cat_id'=>$cat_id,'lcp_id'=>$lcp_id));
            echo "<p>cat_no: ".$lot['cat_no'].", cat_id: $cat_id, lot_id: $lot_id</p>";
            foreach ($lot['docs'] as $doc) {
                $doc_id = saveDocument(array('dt_code'=>$doc['doc_name'],'dt_column'=>$doc['doc_type'],'doc_filepath'=>$doc['doc_filepath'],'doc_filename'=>$doc['doc_filename'],'lot_id'=>$lot_id));
                echo "<p>document code: ".$doc['doc_name'].", doc_id: $doc_id</p>";
            }
        }
        echo "<hr />";
    }
} 

dbClose();
echo "<p>Database closed.</p>";

$content = $data;

/*
$csv = '"Cat.#", "LIFECODES product", "Lot#", "Exp", "Luminex (idt)", "QT (exp) 1", "QT (exp) 2", "MATCHIT! (exp)"'."\r\n";

foreach($data as $filename => $lotfiles) {
    //$csv .= "$filename\n";
    foreach($lotfiles as $index => $values) {
        extract($values);
        $csv .= '"'.$cat_no.'", "'.$name.'", "'.$lot_no.'", "'.$exp.'", "", "", "", ""';
        
         
         foreach($docs as $ind => $docArray) {
           extract($docArray);
            $csv .= "$doc_type, $doc_filename, ";
        }
        $csv = substr($csv, 0, -2);
        
        
        $csv .= "\r\n";
    }
    $csv .= "\r\n";
}

$content = $csv;

*/


/*
 * DB Operations, temp
 */

/*
 
$connex = connectDB($dbhost, $dbuser, $dbpass, $dbname);
if(!$connex) { die("failed DB connect"); }


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

/*section removed to hla_update.php as it was that assignment-specific */

//Use viewer.php to check DB results... 
include('templates/default.php');

?>