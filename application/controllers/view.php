<?php
class View extends CI_Controller {

    public $data;

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
        $this->data = array();
        $this->data['sitetitle'] = $this->config->item('site_name');
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['content'] = "\n\n<!-- Error. -->\n\n"; //this gets replace if everything works
        $this->data['nav_menu'] = nav_menu(array('logged_in'=>$this->ion_auth->logged_in(),'is_admin'=>$this->ion_auth->is_admin()));
        $this->data['title'] = null;
    }


    //view lots table
    public function index($filter_key=null,$filter_value=null) { //note: if you change these variables, change the function lots() too

        if (($filter_key == 'lcp') && $filter_value) { 
            $url = site_url('edit/lot/new');
            $url .= '/'.intval($filter_value);
            $this->data['content'] = "<p><a href=\"$url\">Create New Lot</a></p>" ;
        }

        $this->Lifecodes_model->renderLotsTable(false,array('only_set_cols'=>true)); //set the column names so we can check if sort is appropriate
        if ((isset($_GET['sort'])) && (isset($this->Lifecodes_model->lots_table_cols[$_GET['sort']])) && ($this->Lifecodes_model->lots_table_cols[$_GET['sort']]['sortable'] == true)) {
            $sort = $GLOBALS['sort'] = $_GET['sort'];
            if (((isset($_GET['desc'])) && ($_GET['desc'] == 'y'))) { $desc = $GLOBALS['sort_desc'] = true; }
            else { $desc = $GLOBALS['sort_desc'] = false; }
        } else {
            $sort = $GLOBALS['sort'] = null;
            $desc = $GLOBALS['sort_desc'] = null;
        }

        $filter = array();
        if ($filter_key && $filter_value) { $filter[] = array('key'=>$filter_key,'value'=>$filter_value); }
        $lots = $this->Lifecodes_model->getLots(array('get_lot_info'=>true,'sort'=>$sort,'sort_desc'=>$desc,'filter'=>$filter));
        $options = array();
        if (($filter_key == 'lcp') && $filter_value) { $options['sortable'] = true; } else { $options['sortable'] = false; }
        $table = $this->Lifecodes_model->renderLotsTable($lots,$options);

        if (($filter_key == 'lcp') && $filter_value) { //sortables
            $this->data['head'] = "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.js\"></script>\n";
            $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.core.js\"></script>\n";
            $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.widget.js\"></script>\n";
            $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.mouse.js\"></script>\n";
            $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.sortable.js\"></script>\n";
            $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery_sortables.js\"></script>\n";
        }
        $this->data['title'] = 'View Lots';
        $this->data['content'] .= $this->Lifecodes_model->table_title(array('type'=>'view_lots','filter'=>$filter));
        $this->data['content'] .= $table;
        $this->load->view('admin_page',$this->data);
    }

    //alternate name for view lots index function
    public function lots($filter_key=null,$filter_value=null) {
        return $this->index($filter_key,$filter_value);
    }

    //view a single lot row
    public function lot($lot_id) {
        $this->data['title'] = 'View Lot';
        $this->data['content'] = '';
        $lot_id = intval($lot_id);
        $lot = $this->Lifecodes_model->getLotInfo($lot_id);
        if (!is_array($lot)) {
            $this->data['error'] = "Invalid Lot ID Entered!";
        } else {
            $this->data['content'] .= "<p><a href=\"".site_url("view/lots/lcp/".$lot['lcp_id'])."\">Back to ".$lot['lcp_name']."</a></p>" ;
            $lots = array($lot_id=>$lot);
            $table = $this->Lifecodes_model->renderLotsTable($lots);
            $this->data['content'] .= $table;
        }
        if (@$this->session->flashdata('message')) { $this->data['message'] = $this->session->flashdata('message'); }
        $this->load->view('admin_page',$this->data);
    }

    //preview mode for a product
    public function preview($lcp_id=null,$lot_id=null) {
        $this->Lifecodes_model->active_status = 'p'; //p for preview
        $this->data['title'] = 'Preview';
        $lcp_id = intval($lcp_id);
        $lot_id = intval($lot_id);
        if (!$lcp_id) { $this->data['error'] = 'No preview page selected!'; }
        else {
            $preview = $this->Lifecodes_model->generateResults(array('type'=>'preview','lcp_id'=>$lcp_id,'lot_id'=>$lot_id));
            if (!$preview) { $this->data['error'] = "Could not generate preview page."; }
            else {
                $this->data['content'] = $this->load->view($preview['preview_view'],$preview,true);
            }
        }
        if (@$this->data['error']) { $view = 'admin_page'; }
        else { $view = 'preview'; }
        $this->load->view($view,$this->data);
    }

    //display all files
    public function files($screen='popup',$start=0) {
        $this->load->helper('form');
		$this->load->library('form_validation');
        if ($screen == 'popup') { $view = 'admin_popup'; } else { $view = 'admin_page'; }
        $this->data['title'] = 'Files';

        if (@$_REQUEST['search_query']) { 
            $search_query = trim(strip_tags($_REQUEST['search_query']));
            $start = 0;
            $limit = 100;
        } else {
            $search_query = false;
            $start = intval($start); if ($start < 0) { $start = 0; }
            $limit = 10;
        }

        $files = $this->Lifecodes_model->getFiles(array('search_query'=>$search_query,'start'=>$start,'limit'=>$limit));
        $table = $this->Lifecodes_model->renderFilesTable($files,array('view'=>$view,'start'=>$start));

        $this->data['content'] = "<p class=\"search_form\">".form_open();
        $this->data['content'] .= form_input(array('name'=>'search_query','id'=>'search_query','value'=>$search_query)).' ';
        if ($view == 'admin_popup') { $this->data['content'] .= "<input type=\"button\" value=\"Search\" onClick=\"start_search()\" />"; }
        else { $this->data['content'] .= "<input type=\"submit\" value=\"Search\" />"; }
        
        $this->data['content'] .= form_close().'</p>';
        
        $this->data['content'] .= $table;
        $page = floor($start / $limit) + 1;
        $nav = array();
        if ($start > 0) {
            //$next = $start - $limit;
            //if ($next < 0) { $next = 0; }
            //$nav[] = "<a href=\"#\" onClick=\"navigate_library($next)\">&laquo; Previous</a>";
            if ($view == 'admin_popup') { $nav[] = "<a href=\"#dialog_top\" onClick=\"navigate_library(0)\">&laquo; First</a>"; }
            else { $nav[] = "<a href=\"".BASE_URL."view/files/full\">&laquo; First</a>"; }
            
        }

        if (!$search_query) {
            $qty = 14; //number of page links
            $i = $page - (round($qty/2));
            $j = 0;
            if ($i < 1) { $i = 1; }
            while (($j < $qty) && ((($i-1) * $limit) < $this->Lifecodes_model->file_count)) {
                $j++;
                if ($i == $page) { $nav[] = "<strong>$i</strong>"; }
                else { 
                    $k = ($i-1) * $limit;
                    if ($view == 'admin_popup') { $nav[] = "<a href=\"#dialog_top\" onClick=\"navigate_library($k)\">$i</a>"; }
                    else { $nav[] = "<a href=\"".BASE_URL."view/files/full/$k\">$i</a>"; }
                }
                $i++;
            }
            if (($start + $limit) < $this->Lifecodes_model->file_count) {
                //$next = $start + $limit;
                //$nav[] = "<a href=\"#\" onClick=\"navigate_library($next)\">Next &raquo;</a>";
                $last = floor($this->Lifecodes_model->file_count / $limit) * $limit;
                if ($view == 'admin_popup') { $nav[] = "<a href=\"#dialog_top\" onClick=\"navigate_library($last)\">Last &raquo;</a>"; }
                else { $nav[] = "<a href=\"".BASE_URL."view/files/full/$last\">Last &raquo;</a>"; }
                
            }
            $this->data['content'] .= "<p>".implode(' | ',$nav)."</p>";
        }
        $this->load->view($view,$this->data);
    }

    //display all document types
    //display all documents
    public function document_types() {
        $this->data['title'] = 'Document Types';

        $types = $this->Lifecodes_model->getAllDocumentTypes();
        $this->load->library('table');
        $this->table->set_heading('Column', 'Caption', '&nbsp;');
        foreach ($types as $type) {
            $this->table->add_row($type['dt_name'], $type['dt_code'], "<a href=\"".BASE_URL."edit/delete_dt/".$type['dt_id']."\">Delete</a>");
        }
        $table = $this->table->generate();
        //echo "<pre>"; print_r($types); die();

        $this->data['content'] .= $table;
        $this->load->view('admin_page',$this->data);
    }


    //display all documents
    public function documents() {
        $this->data['title'] = 'Documents';

        $nav = array();
        $nav[] = "<a href=\"".BASE_URL."view/files/full\">Delete Files</a>";
        $nav[] = "<a href=\"".BASE_URL."view/document_types\">Delete Document Types</a>";
        $this->data['content'] = "<p>".implode(' &middot; ',$nav)."</p>";

        $this->Lifecodes_model->renderDocumentsTable(false,array('only_set_cols'=>true)); //set the column names so we can check if sort is appropriate
        if ((isset($_GET['sort'])) && (isset($this->Lifecodes_model->documents_table_cols[$_GET['sort']])) && ($this->Lifecodes_model->documents_table_cols[$_GET['sort']]['sortable'] == true)) {
            $sort = $GLOBALS['sort'] = $_GET['sort'];
            if (((isset($_GET['desc'])) && ($_GET['desc'] == 'y'))) { $desc = $GLOBALS['sort_desc'] = true; }
            else { $desc = $GLOBALS['sort_desc'] = false; }
        } else {
            $sort = $GLOBALS['sort'] = null;
            $desc = $GLOBALS['sort_desc'] = null;
        }
        $documents = $this->Lifecodes_model->getDocuments(array('sort'=>$sort,'sort_desc'=>$desc));
        $table = $this->Lifecodes_model->renderDocumentsTable($documents);
        $this->data['content'] .= $table;
        $this->load->view('admin_page',$this->data);
    }

    public function document($doc_id) {
        $this->data['title'] = 'View Document';
        $doc_id = intval($doc_id);
        $doc = $this->Lifecodes_model->getDocumentInfo($doc_id);
        $c = null;
        if (!is_array($doc)) { $this->data['error'] = "Invalid Document ID Entered!" ; }
        else {
            $c = "<p><a href=\"".site_url("edit/document/$doc_id/full")."\">Edit Document</a></p>";
            $c .= "<table>";
            $c .= "<tr><th>Filename:</th><td><a href=\"".$doc['url']."\">".$doc['file_name']."</a></td></tr>";
            $c .= "<tr><th>Lot:</th><td>".$doc['lot']."</td></tr>";
            $c .= "</table>";
        }
        $this->data['content'] = $c;
        $this->load->view('admin_page',$this->data);
    }

    public function preview_test($lcp_id=null,$lot_id=null) {
        $this->Lifecodes_model->active_status = 'p'; //p for preview
        $this->data['title'] = 'Preview';
        $lcp_id = intval($lcp_id);
        $lot_id = intval($lot_id);
        if (!$lcp_id) { $this->data['error'] = 'No preview page selected!'; }
        else {
            $preview = $this->Lifecodes_model->generateResults(array('type'=>'preview','lcp_id'=>$lcp_id,'lot_id'=>$lot_id));
            if (!$preview) { $this->data['error'] = "Could not generate preview page."; }
            else {
                $this->data['content'] = $GLOBALS['debug_query'];
            }
        }
        $view = 'admin_page';
        $this->load->view($view,$this->data);
    }

}
?>