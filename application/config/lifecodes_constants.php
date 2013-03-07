<?php

/*Some constants that we use both within CodeIgniter and also from outside scripts called via Ajax.*/

if ($_SERVER['HTTP_HOST'] == 'localhost') {  //on local computer
    
    if(stristr($_SERVER['DOCUMENT_ROOT'], 'natxty')) {
        //we're on nsc
        define('BASE_URL','http://localhost/genprobe/');
        define('BASE_URL_RELATIVE','/genprobe/');
        $GLOBALS['site_loc'] = 'nsc';
    } else {
        //we're on rjr
        define('BASE_URL','http://localhost/genprobe/myadmin/');
        define('BASE_URL_RELATIVE','/genprobe/myadmin/');
        $GLOBALS['site_loc'] = 'rjr';
    }
    define('TESTING',true);
} else {
    
     if($_SERVER['HTTP_HOST'] ==  'lifecodes.magneticcreative.com' ) {
        define('BASE_URL','http://lifecodes.magneticcreative.com/');
        define('BASE_URL_RELATIVE','/');
        define('SERVER_PATH','/var/www/vhosts/magneticcreative.com/subdomains/lifecodes'); //no trailing slash
        $GLOBALS['site_loc'] = 'dev';
     } else {
        define('BASE_URL','http://www.gen-probe.com/padmin/');
        define('BASE_URL_RELATIVE','/padmin/');
        define('SERVER_PATH','C:/inetpub/vhosts/gen-probe.com/httpdocs/padmin');
        $GLOBALS['site_loc'] = 'live';
     }
    if ($_SERVER['REMOTE_ADDR'] == '74.111.202.3') { define('TESTING',true); } //mag office
    //else {
        define('TESTING',false);
    //}

}

define('UPLOADS_DIRECTORY',BASE_URL_RELATIVE.'uploads'); //no trailing slash
define('DOCUMENTS_DIRECTORY',BASE_URL_RELATIVE.'documents'); //no trailing slash
define('FULL_DOCUMENTS_DIRECTORY',BASE_URL.'documents'); //no trailing slash
define('SITETITLE','LIFECODES Lot Admin');


//BASEPATH is set in CodeIgniter's index.php.  Luckily, we only need the following constants within CI, so we'll only set them if index.php has already been called.
if (defined('BASEPATH')) {
    define('BASE_FOLDER',substr(BASEPATH,0,-7));
    define('UPLOADS_FOLDER',str_replace(BASE_URL_RELATIVE,BASE_FOLDER,UPLOADS_DIRECTORY));
    define('DOCUMENTS_FOLDER',str_replace(BASE_URL_RELATIVE,BASE_FOLDER,DOCUMENTS_DIRECTORY));
}

echo "Site Title: ".SITETITLE."<br />";

?>