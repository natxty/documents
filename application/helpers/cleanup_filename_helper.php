<?php

//this function is used by /scripts/ajax/edit_docs.php and also within CI

function cleanup_filename($filename,$remove_period=false) {
    if (!$filename) { return false; }
    $filename = trim($filename);
    $filename = strtolower($filename);
    $filename = str_replace(' - ','-',$filename);
    $filename = str_replace(array(' ','%20','(',')'),'_',$filename);
    $filename = preg_replace('/[^a-z0-9_.-]/','',$filename);

    //replace more than one period with underscore
    if (substr_count($filename,'.') > 1) {
        $p = explode('.',$filename);
        foreach ($p as $k => $v) {
            if ($k == 0) { $filename = $v; }
            elseif ($k == (count($p)-1)) { $filename .= '.'.$v; }
            else { $filename .= '_'.$v; }
        }
    }

    //get rid of double underscores
    while (strpos($filename,'__') !== false) { $filename = str_replace('__','_',$filename); }

    while (strpos($filename,'_.') !== false) { $filename = str_replace('_.','.',$filename); }

    //get rid of the period (to make sure it doesn't cause issues when passed through the URI as a CI function variable)
    if ($remove_period) { $filename = str_replace('.','__',$filename); }

    return $filename;
}


?>