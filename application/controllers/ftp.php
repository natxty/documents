<?php
class Ftp extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('ftp');
    }

    public function index() {
        echo "<h1>LIFECODES</h1>";
		
		$config['hostname'] = 'gen-probe.com';
		$config['username'] = 'genpro';
		$config['password'] = 'rockin9e!';
		$config['debug'] = TRUE;
			
		$this->ftp->connect($config);

		$list = $this->ftp->list_files('/webroot/uploads/File/product/');

		print_r($list);

		$this->ftp->close();
		
    }

    
}
?>