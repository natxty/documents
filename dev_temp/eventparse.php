<?php

header("Content-type: text/html; charset=utf-8");

//includes
include_once 'classes/LIB_parse.php';
include_once 'classes/cleaners.php';
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
 * Set up some variables we'll need
 */
$clean_options['strip_line_breaks'] = TRUE;
$clean_options['convert_foreign_chars'] = TRUE;

/*
 * Initial Pages Load
 */

$files = '';
$path = './datapages/';
$filename = 'events.php';
    
    
$file = file_get_contents($path.$filename);

//echo "<h2>$filename</h2>\n";
/*
 * Trim down to the Lot Specific
 */
$file = return_between($file, '<TABLE', '</TABLE>', EXCL);



/*
 * Parse into chunks by table row...
 */

$rows = parse_array($file, '<TR', '</TR>');

/*
 * Break into individual TDs and do some parsing...
 */
foreach($rows as $index => $tr) {

    $tds = parse_array($tr, '<td', '</td>');
    
    
    
    foreach($tds as $key => $value) {
        //get rid of the tds...
        $value = preg_replace('(\<((\/)*)td(.*)\>)sUi', '', $value);
        
        if(stristr('<B>', $value)) { $value = return_between($value, '<B>', '</B>', EXCL); }
        
        //if this td is one of our doc columns, we need to break them out individually
        if($key == 0) {
            /*
             * Pull out the link and anchor text...
             */
            $anchor = strip_tags($value);
            $data[$index]['name'] = strClean($anchor); 
                
            //find full href (ref regex libs above):
            preg_match("/$phref/iX", $value, $link);
            //$link[1] = get_attribute($value, 'href');
            
            //add to our data array
            $data[$index]['link'] = $link[1];     
        
            //end link subroutine
        
        } elseif($key == 1) {
            //We're dealin with location
            $value = strClean(strip_tags($value), TRUE);
            $data[$index]['location'] = $value;
        } elseif($key == 2) {
            
            $year = '2011';
            //We're dealin with date
            $value = strClean(strip_tags($value), TRUE);
            //strip all white space:
            $value = preg_replace('([(\s+)\.])', '', $value);
            //explode based on hyphen
            $dates = explode('-', $value);
            
            //beginning
            //match month with number
            preg_match('([A-Za-z]{3,4})', $dates[0], $beg_month);
            $bmonth = $beg_month[0];
            $bday = str_replace($bmonth, '', $dates[0]);
            
            $data[$index]['date_start'] = $year."-".$month_nums[$bmonth]."-".$bday." 00:00:00.0";
            
            //ending...
            preg_match('([A-Za-z]{3,4})', $dates[1], $end_month);
            (empty($end_month)) ? $emonth = $bmonth : $emonth = $end_month[0];
            $eday = str_replace($emonth, '', $dates[1]);
            
            $data[$index]['date_end'] = $year."-".$month_nums[$emonth]."-".$eday." 00:00:00.0";
        }
        
        

    }
}

/*
 * Format the SQL statements
 */
$content = '';    
foreach($data as $ind => $idata) {
    extract($idata);
    $sql = "INSERT INTO genpro03.dbo.events(category_id, name, link, date_start, date_end, visible, deleted, location, intl, us) ";
    $sql .= "VALUES (1, N'$name', N'$link', '$date_start', '$date_end', 1, 0, N'$location', 1, 1);\n";
    
    $content .= $sql."<br />\n";
    
    unset($sql);
}



/*section removed to hla_update.php as it was that assignment-specific */

//Use viewer.php to check DB results... 
include('templates/industry.php');

?>