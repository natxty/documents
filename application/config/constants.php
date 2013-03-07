<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*Some constants that we use both within CodeIgniter and also from outside scripts called via Ajax.*/

/* requiring the alt. config file is causing some major issues
* due to the include file only able to make use of constants already defined
* see http://www.php.net/manual/en/function.define.php#102712
* here we have an experiment: 
*/

define('BASE_URL','http://www.gen-probe.com/padmin/');
define('BASE_URL_RELATIVE','/padmin/');
define('SERVER_PATH','C:/inetpub/vhosts/gen-probe.com/httpdocs/padmin');
$GLOBALS['site_loc'] = 'live';


define('UPLOADS_DIRECTORY',BASE_URL_RELATIVE.'uploads'); //no trailing slash
define('DOCUMENTS_DIRECTORY',BASE_URL_RELATIVE.'documents'); //no trailing slash
define('FULL_DOCUMENTS_DIRECTORY',BASE_URL.'documents'); //no trailing slash
define('SITETITLE','LIFECODES Lot Admin');


//BASEPATH is set in CodeIgniter's index.php.  Luckily, we only need the following constants within CI, so we'll only set them if index.php has already been called.
define('BASE_FOLDER',substr(BASEPATH,0,-7));
define('UPLOADS_FOLDER',str_replace(BASE_URL_RELATIVE,BASE_FOLDER,UPLOADS_DIRECTORY));
define('DOCUMENTS_FOLDER',str_replace(BASE_URL_RELATIVE,BASE_FOLDER,DOCUMENTS_DIRECTORY));

if ($_SERVER['REMOTE_ADDR'] == '74.111.202.3') { define('TESTING',true); } //mag office
else {
    define('TESTING',false);
}
/* End Config Experiment */

/* End of file constants.php */
/* Location: ./application/config/constants.php */