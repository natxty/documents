<?php
class Export extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('session');
    }

    public function index() {
        echo "<h1>LIFECODES</h1>";
    }

    public function product($lcp_id=false,$region=false,$date_format=false) {
        if (!$lcp_id) { echo "<p>Error! No product selected.</p>"; return false; }
        if ($region == 'us') { $region = 'us'; }
        elseif ($region == 'global') { $region = 'int'; }
        else { echo "<p>Error! Invalid region selected.</p>"; return false; }
        if ($date_format == 'YYYY-MM') { $date_format = 'Y-m'; } else { $date_format = 'Y-m-d'; }
        $GLOBALS['use_download_links'] = true;
        $preview = $this->Lifecodes_model->generateResults(array('type'=>'export','lcp_id'=>$lcp_id,'region'=>$region,'date_format'=>$date_format));
        if (!$preview) { echo "<p>Error! Could not generate product table.</p>"; return false; }
        echo $preview['lot_specific_table'];
    }

    public function document_types($dt_column=null) {
        if (!$dt_column) { return "<option value=\"\"></option>"; }
        $dt_column = str_replace('-','/',$dt_column); //because of tt/rs
        $options = $this->Lifecodes_model->getAllDocumentTypes(array('return_options'=>true,'add_new_option'=>true,'dt_column'=>$dt_column));
        foreach ($options as $value => $caption) {
            echo "<option value=\"$value\">$caption</option>";
        }
    }
}
?>