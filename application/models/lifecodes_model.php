<?php
class Lifecodes_model extends CI_Model {

    var $active_status;
    var $lots_table_cols;
    var $documents_table_cols;
    var $file_count;

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
        $this->active_status = 'a'; //this determines which rows to pull from the database: active or preview
        $GLOBALS['debug'] = false;
    }


    //get a group of lots from the database
    public function getLots($args=array()) {

        //I put the @s here so we don't get warnings if options aren't set
        @$filter = $args['filter'];
        @$sort = $args['sort'];
        @$sort_desc = $args['sort_desc'];
        @$start = intval($args['start']);
        @$limit = intval($args['limit']);
        @$get_lot_info = $args['get_lot_info'];
        $region = @$args['region'];

        $query = "SELECT `lot_id`";
        if ($sort == 'cat_no') { $query .= ", (SELECT cat_no FROM lc_catalog WHERE lc_catalog.cat_id = lc_lots.cat_id) AS cat_no"; }
        $query .= " FROM `lc_lots` ";

        //filter
        $where = array();
        if (($this->active_status == 'p') && (@$args['preview_lot'])) {
            $where[] = '((`status`=\'a\' AND `lot_id` != '.$this->db->escape($args['preview_lot']).') OR (`status`=\'p\' AND `lot_id` = '.$this->db->escape($args['preview_lot']).'))';
        } else {
            $where[] = '`status`='.$this->db->escape($this->active_status);
        }
        if (is_array($filter)) {
            foreach ($filter as $f) {
                $column = null;
                if ($f['key'] == 'lcp') { //to get the lots from a specific parent, we must find the catalogs tied to that parent
                    $catalog = $this->getCatalog(array('lcp_id'=>$f['value']));
                    $cats = array();
                    foreach ($catalog as $cat_id => $cat_no) { $cats[] = $cat_id; }
                    if (count($cats) == 0) { return array(); } //no results
                    $where[] = '`cat_id` IN ('.implode(',',$cats).')';
                } elseif ($column) {
                    $where[] = '`'.$column.'`='.$this->db->escape(trim(strip_tags($f['value']))); 
                }
            }
        }

        if ($region == 'int') { $where[] = "`region_int` = 'y'"; }
        elseif ($region == 'us') { $where[] = "`region_us` = 'y'"; }

        if (count($where) > 0) { $query .= "WHERE ".implode(' AND ',$where); }

        //sort can be passed as either a single column, or as an array of columns
        if (!is_array($sort)) {
            if ($sort) {
                if ($sort_desc == true) { $sort = array('`'.$sort.'` DESC'); }
                else { $sort = array('`'.$sort.'`'); }
            }
            else { $sort = array(); }
        }
        if (count($sort) == 0) { $sort[] = "`order` ASC"; $sort[] = "`lot_expiration` ASC"; }
        $query .= ' ORDER BY '.implode(', ',$sort);
        
        //start, limit
        if ($start < 0) { $start = 0; }
        if ($limit > 0) { $query .= ' LIMIT '.$start.', '.$limit; }

        $GLOBALS['debug_query'] = $query;
        $query = $this->db->query($query);
        if ($query->num_rows() < 1) { return array(); }

        $lots = array();
        foreach ($query->result() as $row) {
            if ($get_lot_info) {  //return all the lot data
                $lot_args = array();
                if ((@$args['preview_lot']) && ($row->lot_id == $args['preview_lot'])) { $lot_args['status'] = false; }
                $lots[$row->lot_id] = $this->getLotInfo($row->lot_id,$lot_args);
            } else { $lots[$row->lot_id] = $row->lot_id; } //only return the lot ID
        }

        return $lots;
    }


    //this function returns an array of data about a single lot
    //can be used from within getLots() to generate a big array of multiple lots
    public function getLotInfo($lot_id,$args=array()) {
        if (!$lot_id) { return false; }
        if (array_key_exists('status',$args)) {
            if ($args['status'] === false) { $status = false; }
            elseif (in_array($args['status'],array('a','p'))) { $status = $args['status']; }
            else { $status = $this->active_status; }
        } else { $status = $this->active_status; }
        $query_string = "SELECT *, (SELECT lcp_name FROM lc_parents WHERE c.lcp_id = lc_parents.lcp_id) AS lcp_name FROM `lc_lots` AS l LEFT JOIN lc_catalog AS c ON l.cat_id = c.cat_id WHERE l.`lot_id` = ".$this->db->escape($lot_id);
        if ($status) { $query_string .= " AND l.`status` = ".$this->db->escape($status); }
        else { $query_string .= ' ORDER BY `status`'; }
        $query_string .= " LIMIT 1";
        $query = $this->db->query($query_string);
        if ($query->num_rows() < 1) { $GLOBALS['debug'] .= "<p>Query returned no results: $query_string</p>"; return false; }
        $row = $query->row_array();
        $doc_cols = $this->getDocumentColumns();
        if (!@$args['skip_docs']) {
            $docs = array();
            foreach ($doc_cols as $key => $dt_column) { $docs[$dt_column] = array(); }
            $query2 = $this->db->query("SELECT d.doc_id, f.file_name, f.file_path, dt.dt_code, dt.dt_name, dt.dt_column FROM lc_documents AS d LEFT JOIN lc_files AS f ON d.file_id = f.file_id LEFT JOIN lc_doctypes AS dt ON d.dt_id = dt.dt_id WHERE d.lot_id = ".$this->db->escape($lot_id)."");
            if ((array_key_exists('region',$args)) && ($args['region'])) { $region = $args['region']; } else { $region = null; }
            $documents = $this->getDocuments(array('lot_id'=>$lot_id,'region'=>$region));
            foreach ($documents as $doc) {
                if (@$args['docs_real_links']) { $url = $doc['url']; }
                else { $url = $this->config->item('base_url').'index.php/view/document/'.$doc['doc_id']; }
                $docs[$doc['dt_column']][] = "<a href=\"$url\">".stripslashes($doc['dt_code'])."</a>";
            }
            foreach ($doc_cols as $key => $dt_column) {
                $row['docs_'.$key] = implode('<br />',$docs[$dt_column]);
            }
        }
        if (@$args['date_format']) { $date_format = $args['date_format']; } else { $date_format = 'Y-m-d'; }
        $row['lot_expiration'] = date($date_format,strtotime($row['lot_expiration']));
        $row['url'] = site_url('view/lot/'.$lot_id);

        $regions = array();
        if ($row['region_us'] == 'y') { $regions[] = 'US'; }
        if ($row['region_int'] == 'y') { $regions[] = 'INT'; }
        $row['region'] = implode(' / ',$regions);

        return $row;
    }

    //info about a document from lc_documents and lc_files
    public function getDocumentInfo($doc_id,$args=array()) {
        if (!$doc_id) { return false; }

        if (@$args['status']) { $status = $args['status']; }
        else { $status = $this->active_status; }

        $query = $this->db->query("SELECT * FROM `lc_documents` WHERE `doc_id` = ".$this->db->escape($doc_id)." AND `status` = ".$this->db->escape($status)." LIMIT 1");
        if ($query->num_rows() < 1) { return false; }
        $row = $query->row_array();

        $regions = array();
        if ($row['region_us'] == 'y') { $regions[] = 'US'; }
        if ($row['region_int'] == 'y') { $regions[] = 'INT'; }
        $row['region'] = implode(' / ',$regions);

        $query = $this->db->query("SELECT * FROM `lc_files` WHERE `file_id` = ".$this->db->escape($row['file_id'])." LIMIT 1");
        $rowf = $query->row_array();
        $row = array_merge($rowf,$row);
        $query = $this->db->query("SELECT * FROM `lc_doctypes` WHERE `dt_id` = ".$this->db->escape($row['dt_id'])." LIMIT 1");
        $rowdt = $query->row_array();
        $row = array_merge($rowdt,$row);
        if (@$GLOBALS['use_download_links']) {
            if (substr($row['file_name'],-4) == '.pdf') { $row['url'] = FULL_DOCUMENTS_DIRECTORY.$row['file_path'].$row['file_name']; }
            else { $row['url'] = 'download.php?filename='.urlencode($row['file_name']); } //use downloader if not PDF
        } else { 
            $row['url'] = FULL_DOCUMENTS_DIRECTORY.$row['file_path'].$row['file_name'];
        }
        $row['file_link'] = "<a href=\"".$row['url']."\">".$row['file_name']."</a>";

        $lot = $this->getLotInfo($row['lot_id'],array('skip_docs'=>true));
        $row['lot'] = "<a href=\"".$lot['url']."\">".$lot['lot_no']."</a>";
        return $row;
    }

    //find all documents associated with a particular lot and return them as an array
    public function getDocuments($args=array()) {
        $lot_id = @$args['lot_id'];
        $region = @$args['region'];
        $dt_id = @$args['dt_id'];
        $query = "SELECT d.doc_id, f.file_name, f.file_path, dt.dt_code, dt.dt_name, dt.dt_column, dt.order, (SELECT lot_no FROM lc_lots WHERE lc_lots.lot_id = d.lot_id AND status = ".$this->db->escape($this->active_status).") AS lot_no FROM lc_documents AS d LEFT JOIN lc_files AS f ON f.file_id = d.file_id LEFT JOIN lc_doctypes AS dt ON d.dt_id = dt.dt_id WHERE `status` = ".$this->db->escape($this->active_status);
        if ($dt_id) { $query .= " AND d.dt_id = ".$this->db->escape($dt_id); }
        if ($lot_id) { $query .= " AND d.lot_id = ".$this->db->escape($lot_id); }
        if ($region == 'us') { $query .= " AND d.region_us = 'y'"; }
        elseif ($region == 'int') { $query .= " AND d.region_int = 'y'"; }
        if ((@$args['sort']) && (isset($this->Lifecodes_model->documents_table_cols[$args['sort']])) && ($this->Lifecodes_model->documents_table_cols[$args['sort']]['sortable'] == true)) {
            if ($args['sort'] == 'file_link') { $sort = 'file_name'; }
            elseif ($args['sort'] == 'lot') { $sort = 'lot_no'; }
            else { $sort = $args['sort']; }
            $order = '`'.$sort.'`';
            if (@$args['sort_desc']) { $order .= " DESC"; }
        } else { $order = '`order`, `file_path`, `file_name`'; } //default order
        $query .= " ORDER BY ".$order;
        if (isset($args['start']) && isset($args['limit'])) { $query .= " LIMIT ".intval($args['start']).",".intval($args['limit']); }
        $query = $this->db->query($query);
        $documents = array();
        foreach($query->result_array() as $row) {
            $documents[$row['doc_id']] = $this->getDocumentInfo($row['doc_id']);
        }
        return $documents;
    }

    //find all lots that have a particular file attached
    public function getAttachements($file_id,$args=array()) {
        $query = $this->db->query("SELECT d.lot_id FROM lc_documents AS d WHERE d.file_id = ".$this->db->escape($file_id)." AND `status` = ".$this->db->escape($this->active_status));
        if ($args['get_lot_info']) { 
            $lots = array();
            foreach ($query->result() as $row) { $lots[$row->lot_id] = $this->getLotInfo($row->lot_id); }
            return $lots;
        } else { return $query->result_array(); }
    }

    public function renderLotsTable($lots,$args=array()) {
        $this->lots_table_cols = array(
          'cat_no'=>array('caption'=>'Cat. #','sortable'=>true,'nowrap'=>true,'width'=>75)
          ,'lot_name'=>array('caption'=>'LIFECODES<br />Product','sortable'=>true,'nowrap'=>false,'width'=>150)
          ,'lot_no'=>array('caption'=>'Lot #','sortable'=>true,'nowrap'=>false,'width'=>60)
          ,'lot_expiration'=>array('caption'=>'Expiration Date','sortable'=>true,'nowrap'=>true,'width'=>100)
          ,'docs_cert'=>array('caption'=>'Cert.','sortable'=>false,'nowrap'=>false,'width'=>50)
          ,'docs_tt/rs'=>array('caption'=>'TT/RS','sortable'=>false,'nowrap'=>false,'width'=>50)
          ,'docs_panel'=>array('caption'=>'Panel','sortable'=>false,'nowrap'=>false,'width'=>50)
          ,'docs_probe_hit'=>array('caption'=>'Probe Hit<br />Charts','sortable'=>false,'nowrap'=>false,'width'=>100)
          ,'docs_core'=>array('caption'=>'Core<br />Seq.','sortable'=>false,'nowrap'=>false,'width'=>100)
          ,'docs_template'=>array('caption' => 'Templates', 'sortable'=>false, 'nowrap'=>true,'width'=>90)
          ,'region'=>array('caption'=>'US/INT','sortable'=>false,'nowrap'=>false,'width'=>35)
          ,'last_col'=>array('caption'=>'Actions','sortable'=>false,'nowrap'=>false,'width'=>35)
          //,'move_col'=>array('caption'=>'Move','sortable'=>false,'nowrap'=>false,'width'=>35)
        );
        /*$width = 0;
        foreach ($this->lots_table_cols as $col) { $width = $width + $col['width']; }
        die('total width: '.$width);*/
        if (@$args['only_set_cols']) { return true; } //columns need to be set in order to check sort
        else { return $this->renderSortableTable('lots',$lots,$args); }
    }

    public function renderDocumentsTable($documents,$args=array()) {
        $version = @$args['version'];
        if ($version == 'edit_lot') {
            $this->documents_table_cols = array(
              'dt_name'=>array('caption'=>'Column','sortable'=>true,'nowrap'=>true)
              ,'dt_code'=>array('caption'=>'Document','sortable'=>true,'nowrap'=>true)
              ,'file_link'=>array('caption'=>'Filename','sortable'=>true,'nowrap'=>true)
              ,'region'=>array('caption'=>'US / INT','sortable'=>false,'nowrap'=>true)
              ,'last_col'=>array('caption'=>'Actions','sortable'=>false,'nowrap'=>true)
            );
        } else {
            $this->documents_table_cols = array(
              'doc_id'=>array('caption'=>'ID','sortable'=>true,'nowrap'=>false)
              ,'dt_name'=>array('caption'=>'Column','sortable'=>true,'nowrap'=>true)
              ,'dt_code'=>array('caption'=>'Type','sortable'=>true,'nowrap'=>false)
              ,'file_link'=>array('caption'=>'Filename','sortable'=>true,'nowrap'=>false)
              ,'lot'=>array('caption'=>'Lot','sortable'=>true,'nowrap'=>true)
              ,'last_col'=>array('caption'=>'Actions','sortable'=>false,'nowrap'=>false)
            );
        }
        if (@$args['only_set_cols']) { return true; } //columns need to be set in order to check sort
        else { return $this->renderSortableTable('documents',$documents,$args); }
    }

    //note: These tables are generated "by hand" instead of with CI's table class because of the customizations requried for Sortables
    private function renderSortableTable($type,$data,$args=array()) {
        if ($type == 'lots') {
            $cols = $this->lots_table_cols;
        } elseif ($type = 'documents') {
            $cols = $this->documents_table_cols;
        } else {
            return false;
        }

        if (!is_array($data)) { return false; }
        if (count($data) < 1) { return false; }

        $blank = '&nbsp;'; //what to fill blank cells with

        $table = "<table>";

        //headers
        $headers = array();
        foreach ($cols as $key => $col) {
            if (array_key_exists('sort',$GLOBALS) && ($col['sortable'])) {
                $qs = '?sort='.$key;
                if (($GLOBALS['sort'] == $key) && ($GLOBALS['sort_desc'] == false)) { $qs .= '&desc=y'; $arrow = '&darr;'; } else { $arrow = '&uarr;'; }
                $caption = "<a href=\"".site_url($this->uri->uri_string().$qs)."\"> $arrow ".$col['caption']."</a>";
            } else { $caption = $col['caption']; }
            $headers[] = array('value'=>$caption,'width'=>@$col['width']);
        }
        $table .= "<thead><tr>";
        foreach ($headers as $h) {
            if ($h['value']) { $value = $h['value']; } else { $value = $blank; }
            $table .= "<th";
            if ($h['width']) { $table .= " style=\"width:".$h['width']."px;\""; }
            if ($type == 'documents') { $table .= " class=\"nowrap\""; }
            $table .= ">$value</th>";
        }
        $table .= "</tr></thead>";

        //body
        $table .= "<tbody";
        if (@$args['sortable'] == true) { $table .= " id=\"sortable\" style=\"cursor:pointer;\""; }
        $table .= ">";
        $i = 0;
        foreach ($data as $id => $data_row) {
            $i++;
            $row = array();
            foreach ($cols as $key => $col) {
                if ($key == 'last_col') {
                    if ($type == 'lots') {
                        $value = "<a href=\"".site_url("edit/lot/".$id)."\">Edit</a><br /><a href=\"".site_url("edit/delete_lot/".$id)."\">Delete</a>";
                        if (@$args['sortable']) { $value .= '<br /><a href="#">Move</a>'; }
                    } elseif ($type == 'documents') {
                        $v = array();
                        $lot_id = @$args['lot_id'];
                        if (!$lot_id) { 
                            $url = site_url('view/document/'.$id);
                            $v[] = "<a href=\"$url\">View</a>";
                        }
                        $url = site_url('edit/document/'.$id);
                        if ($lot_id) { $url .= '/preview'; }
                        $v[] =  "<a href=\"$url\">Edit</a>";
                        if ($lot_id) { 
                            $url = site_url('edit/remove_document/'.$id.'/'.$lot_id);
                            $v[] = "<a href=\"$url\">Remove</a>";
                        }
                        $value = implode('<br />',$v);
                    } else { return false; } //this shouldn't happen
                } else {
                    $value = $data_row[$key];
                }
                if ($col['nowrap']) { $class = 'nowrap'; } else { $class = null; }
                $row[] = array('class'=>$class,'data'=>$value,'width'=>@$col['width']);
            }
            if ($type == 'lots') { $row_id = 'lot'.$id.'_'.$i; }
            elseif ($type == 'documents') { $row_id = 'doc'.$id.'_'.$i; }
            else { return false; } //this shouldn't happen
            $table .= $this->tableRow($row,array('blank'=>$blank,'row_id'=>$row_id));
        }
        $table .= "</tbody>";
        $table .= "</table>";

        return $table;
    }

    //this is used by the renderLotsTable($lots) function
    private function tableRow($row,$args=array()) {
        $blank = @$args['blank'];
        $row_id = @$args['row_id'];
        $r = "<tr"; 
        if ($row_id) { $r .= " id=\"$row_id\""; }
        $r .= ">";
        foreach ($row as $cell) {
            $r .= "<td";
            if ($cell['class']) { $r .= " class=\"".$cell['class']."\""; }
            if ($cell['width']) { $r .= " style=\"width:".$cell['width']."px;\""; }
            $r .= ">";
            if ($cell['data']) { $r .= $cell['data']; }
            else { $r .= $blank; }
            $r .= "</td>";
        }
        $r .= "</tr>";
        return $r;
    }

    public function getFiles($args=array()) {
        $q = "SELECT * FROM `lc_files` WHERE `file_id` > '0'";
        if (!@$args['include_deleted']) { $q .= " AND `deleted` != 'y'"; }
        if (@$args['search_query']) {
            $this->load->helper('cleanup_filename');
            $search_query = '%'.cleanup_filename($args['search_query']).'%';
            $q .= " AND `file_name` LIKE ".$this->db->escape($search_query);
        } elseif (@$args['file_id']) {
            $q .= " AND `file_id` = '".intval($args['file_id'])."'";
        }
        $q .= " ORDER BY `last_updated` DESC, `file_name`";
        $query = $this->db->query($q);
        $this->file_count = $query->num_rows();
        if (isset($args['start']) && isset($args['limit'])) { //apply limit
            $q .= " LIMIT ".intval($args['start']).",".intval($args['limit']);
            $query = $this->db->query($q);
        }
        $files = array();
        foreach($query->result_array() as $row) {
            if ($row['last_updated'] == '0000-00-00 00:00:00') { $row['date_updated'] = 'Before<br />2011-05-01'; }
            else {
                $last_updated = strtotime($row['last_updated']);
                if ($last_updated) { $row['date_updated'] = date('Y-m-d',$last_updated); } else { $row['date_updated'] = 'Before<br />2011-05-01'; }
            }
            $files[$row['file_id']] = $row;
        }
        return $files;
    }


    public function renderFilesTable($files,$args=array()) {
        if (count($files) < 1) { return "<p><em>No data.</em></p>"; }
        $this->load->library('table');
        $cols = array(
            'date_updated'=>array('caption'=>'Date Uploaded','sortable'=>true,'nowrap'=>true)
            ,'file_name'=>array('caption'=>'Filename','sortable'=>true,'nowrap'=>false)
            ,'action'=>array('caption'=>'','sortable'=>false,'nowrap'=>true)
        );
        $headers = array();
        foreach ($cols as $key => $col) { $headers[] = $col['caption']; }
        $this->table->set_heading($headers);

        foreach ($files as $file_id => $file) {
            //echo "<pre>"; print_r($file); die();
            $row = array();
            foreach ($cols as $key => $col) {
                if (($key == 'file_name') && (@$args['view'] == 'admin_page')) { $row[] = "<a href=\"".FULL_DOCUMENTS_DIRECTORY.'/'.$file[$key]."\">".$file[$key]."</a>"; }
                elseif ($key == 'action') {
                    if (@$args['view'] == 'admin_page') { 
                        $start = intval(@$args['start']);
                        $row[] = "<a href=\"".BASE_URL."edit/delete_file/".$file_id."/".$start."\">Delete File</a>";
                    } else { $row[] = "<a onclick=\"selectFile('".$file_id."','".$file['file_name']."');\" href=\"#\">Select Document</a>"; }
                } else { $row[] = $file[$key]; }
            }
            $this->table->add_row($row);
        }
        return $this->table->generate();
    }


    public function getCatalog($args=array()) {
        $cat = array();
        if (@$args['include_blank']) { $cat[] = ''; }
        if (@$args['lcp_id']) { $where = "WHERE `lcp_id` = '".intval($args['lcp_id'])."'"; } else { $where = ''; }
        $query = "SELECT * FROM `lc_catalog` ".$where." ORDER BY `cat_no`";
        $query = $this->db->query($query);
        foreach ($query->result() as $row) {
            $cat[$row->cat_id] = $row->cat_no;
        }
        return $cat;
    }

    public function table_title($args=array()) {
        @$filter = $args['filter'];
        @$type = $args['type'];
        if ($type != 'view_lots') { return false; }
        if (!is_array($filter)) { return false; }
        elseif (count($filter) < 1) { return false; }
        if ($filter[0]['key'] == 'lcp') {
            $query = $this->db->query("SELECT * FROM `lc_parents` WHERE `lcp_id` = ".$this->db->escape($filter[0]['value']));
            $row = $query->row();
            $title = $row->lcp_name;
        }
        return "<h4>$title</h4>";
    }

    //as you might guess, this next one saves a lot
    public function saveLot($data) {
        $lot_id = $data['lot_id'];
        if (!$data['lot_name']) { return false; }
        if (!$data['lot_no']) { return false; }
        if (!$data['lot_expiration']) { return false; }
        if (!$data['cat_id']) { return false; }

        if (!is_array($data['region'])) { $GLOBALS['errors'][] = "No region array! <!-- in saveLot() -->"; return false; } //shouldn't happen
        elseif (count($data['region']) == 0) { $GLOBALS['errors'][] = "You must select at least one region!"; return false; }
        else {
            if (in_array('us',$data['region'])) { $data['region_us'] = 'y'; } else { $data['region_us'] = 'n'; }
            if (in_array('int',$data['region'])) { $data['region_int'] = 'y'; } else { $data['region_int'] = 'n'; }
            unset($data['region']);
        }

        $status = 'p'; //p for preview (it will get activated later)

        //cleanup data
        unset($data['lot_id']);
        $data['lot_expiration'] = date('Y-m-d h:m:s',$data['lot_expiration']);
        $data['lot_no'] = strtoupper($data['lot_no']);
        foreach ($data as $key => $value) { $data[$key] = trim($value); }

        $query = "SELECT * FROM lc_catalog WHERE cat_id = ".$this->db->escape($data['cat_id']);
        $query = $this->db->query($query);
        $row = $query->row();

        //echo "<pre>"; print_r($data); die();

        if ($lot_id) {
            $this->db->where('lot_id', $lot_id);
            $this->db->where('status', $status);
            $this->db->update('lc_lots', $data);
            //die($this->db->last_query());
            if (!$this->db->_error_message()) { return $lot_id; } else { return false; }
        } else {
            //look for duplicates
            $data['status'] = $status;
            $query = $this->db->query("SELECT * FROM lc_catalog WHERE cat_id = ".$this->db->escape($data['cat_id']));
            $row = $query->row();
            $lcp_id = $row->lcp_id;
            $query = $this->db->query("SELECT * FROM lc_catalog WHERE lcp_id = ".$this->db->escape($lcp_id));
            $catalogs = array();
            foreach ($query->result() as $row) {
                $catalogs[] = $this->db->escape($row->cat_id);
            }
            $query = "SELECT * FROM lc_lots WHERE lot_no = ".$this->db->escape($data['lot_no'])." AND `cat_id` NOT IN (".implode(',',$catalogs).")";
            $query = $this->db->query($query);
            $row = $query->row();
            if (@$row->lot_id > 0) { $GLOBALS['errors'][] = "Another lot with this same Lot Number already exists."; return false; }
            else {
                //this can't be auto-increment because of the status column (and previews)
                $query = $this->db->query("SELECT * FROM lc_lots ORDER BY lot_id DESC LIMIT 1");
                $row = $query->row();
                $lot_id = $data['lot_id'] = $row->lot_id + 1;
                $this->db->insert('lc_lots', $data);
                if (!$this->db->_error_message()) { return $lot_id; } else { return false; }
            }
        }
    }
    
    //this marks a lot as deleted in the database.  it doesn't actually delete it
    public function deleteLot($lot_id) {
        $lot_id = intval($lot_id);
        if (!$lot_id) { return false; }
        $this->db->query("UPDATE lc_lots SET status = 'd' WHERE lot_id = '$lot_id' LIMIT 1");
        if (!$this->db->_error_message()) { return true; } else { return false; }
    }

    //delete a document type (actually deletes it from DB -- a check to see there are no associated active documents has already been run in delete_dt() function)
    public function deleteDocumentType($dt_id) {
        $dt_id = intval($dt_id);
        if (!$dt_id) { return false; }
        $this->db->query("DELETE FROM `lc_doctypes` WHERE dt_id = '$dt_id' LIMIT 1");
        if (!$this->db->_error_message()) { return true; } else { return false; }
    }

    //this marks a file as deleted in the database.  it doesn't actually delete it
    public function deleteFile($file_id) {
        $file_id = intval($file_id);
        if (!$file_id) { return false; }
        $this->db->query("UPDATE lc_files SET deleted = 'y' WHERE file_id = '$file_id' LIMIT 1");
        if (!$this->db->_error_message()) { return true; } else { return false; }
    }

    //it's like magic. oh wow!
    public function undeleteFile($file_id) {
        $file_id = intval($file_id);
        if (!$file_id) { return false; }
        $this->db->query("UPDATE lc_files SET deleted = '' WHERE file_id = '$file_id' LIMIT 1");
        if (!$this->db->_error_message()) { return true; } else { return false; }
    }

    //save a document (relation)
    public function saveDocument($args) {
        $GLOBALS['saveDocument_errors'] = array();
        //echo "<pre>"; print_r($args); echo "</pre>"; die();
        //echo "<pre>"; print_r(get_defined_constants()); echo "</pre>"; die();
        $lot_id = intval($args['lot_id']);
        if (!$lot_id) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: No Lot ID!"; return false; }
        $dt_id = intval($args['dt_id']);
        if (!$dt_id) { $GLOBALS['saveDocument_errors'][] = "Error: You must select a document type!"; return false; }
        if (!is_array($args['region'])) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: No region array!"; return false; }
        elseif (count($args['region']) == 0) { $GLOBALS['saveDocument_errors'][] = "Error: You must select at least one region!"; return false; }
        else {
            if (in_array('us',$args['region'])) { $region_us = 'y'; } else { $region_us = 'n'; }
            if (in_array('int',$args['region'])) { $region_int = 'y'; } else { $region_int = 'n'; }
        }
        $file_id = intval($args['file_id']);
        if (!$file_id) { //this should only happen if a new file is being uploaded
            $file_id = $this->saveUploadedFile(@$args['file_name']);
            if (!$file_id) { $GLOBALS['saveDocument_errors'][] = "Error: Uploaded file could not be saved correctly!"; return false; }
        }

        if (@$args['status']) { $status = $args['status']; }
        else { $status = 'p'; } //p for preview

        if ($args['doc_id'] == 'new') {
            //check for a duplicate
            $dup_fields = array('file_id','lot_id','dt_id','status');
            $query = "SELECT * FROM `lc_documents` WHERE ";
            $and = ''; foreach ($dup_fields as $key) { $query .= $and."`$key`=".$this->db->escape($$key); $and = ' AND '; }
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $doc_id = $row->doc_id;
                //note: as of now, this warning doesn't actually show up to the user.  should it?
                $GLOBALS['warnings'][] = "There is already a document uploaded with the same document type for the same lot. This action updated that data.";
            } else { $doc_id = false; }
        } else {
            $doc_id = intval($args['doc_id']);
            if (!$doc_id) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: No document ID!"; return false; }
        }

        $data = compact('lot_id','file_id','dt_id','status','region_us','region_int');
        if (!$doc_id) { //insert new document relation
            //this can't be auto-increment because of the status column (and previews)
            $query = $this->db->query("SELECT * FROM lc_documents ORDER BY doc_id DESC LIMIT 1");
            $row = $query->row();
            $doc_id = $data['doc_id'] = $row->doc_id + 1;

            $this->db->insert('lc_documents', $data);
            if ($this->db->_error_message()) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: Couldn't insert document data into database!"; return false; }
        } else {
            $this->db->where('doc_id', $doc_id);
            $this->db->where('status', $status);
            $this->db->update('lc_documents', $data); 
            if ($this->db->_error_message()) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: Couldn't update document data in database!"; return false; }
        }
        return $doc_id;
    }

    //called from saveDocument()
    //this handles the moving of the file from uploads folder to real folder, adding row to lc_files table
    private function saveUploadedFile($file_name) {
        if (!$file_name) { $GLOBALS['saveDocument_errors'][] = 'No filename!'; return false; }
        $file_id = false;
        $file_path = '/'; //as of now, this is constant
        $uploaded_file = UPLOADS_FOLDER.'/'.$file_name;
        if (!file_exists($uploaded_file)) { $GLOBALS['saveDocument_errors'][] = "Uploaded file does not exist!\n\n<!-- ".$uploaded_file." -->\n\n"; return false; }
        $file = DOCUMENTS_FOLDER.'/'.$file_name;
        if (file_exists($file)) { //if the file already exists, let's archive it and also lookup the existing file_id
            //note: as of now, this warning doesn't actually show up to the user.  should it?
            $GLOBALS['warnings'][] = "There is already a file uploaded with the same filename. This latest upload has replaced it in all instances on the site. The old file is still available as an archive.";

            list($base,$extension) = explode('.',$file_name);
            $archive_file_exists = true; $i = 0;
            while ($archive_file_exists) {
                $i++;
                $archive_file_name = $base.'_archive_'.$i.'.'.$extension;
                $archive_file = DOCUMENTS_FOLDER.'/'.$archive_file_name;
                if (!file_exists($archive_file)) { $archive_file_exists = false; }
            }
            $moved = rename($file,$archive_file);
            if (!$moved) { $GLOBALS['saveDocument_errors'][] = "Could not archive existing file!\n\n<!-- ".$file." to ".$archive_file." -->\n\n"; return false; }
            $query = $this->db->query("SELECT `file_id` FROM `lc_files` WHERE `file_name` = ".$this->db->escape($file_name)." AND `file_path` = ".$this->db->escape($file_path));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $file_id = $row->file_id;
            }
        }
        $moved = rename($uploaded_file,$file);
        if (!$moved) { $GLOBALS['saveDocument_errors'][] = "Could not move file to documents directory!\n\n<!-- ".$uploaded_file." to ".$file." -->\n\n"; return false; }
        $data = array('file_name'=>$file_name,'file_path'=>$file_path,'deleted'=>'n');
        if ($file_id) { //update existing row
            $this->db->where('file_id',$file_id);
            $this->db->update('lc_files', $data);
            if ($this->db->_error_message()) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: Couldn't insert file data into database!"; return false; }
        } else { //create new row
            $this->db->insert('lc_files', $data);
            if ($this->db->_error_message()) { $GLOBALS['saveDocument_errors'][] = "saveDocument() Error: Couldn't insert file data into database!"; return false; }
            $file_id = $this->db->insert_id();
        }
        return $file_id;
    }
    
    //returns an array of all products (a.k.a. parents)
    public function getAllParents($args=array()) {
        $query = $this->db->query("SELECT * FROM `lc_parents` ORDER BY `lcp_name`");
        $rows = array();
        foreach ($query->result_array() as $row) {
            $row['view_url'] = site_url('view/lots/lcp/'.$row['lcp_id']);
            $rows[] = $row;
        }
        return $rows;
    }

    //returns an array of all document types
    public function getAllDocumentTypes($args=array()) {
        if (@$args['return_options']) { $return_options = true; } else { $return_options = false; }
        $q = "SELECT * FROM `lc_doctypes`";
        if (@$args['dt_column']) { $q .= " WHERE `dt_column` = ".$this->db->escape($args['dt_column']); }
        elseif (@$args['dt_id']) { $q .= " WHERE `dt_id` = ".$this->db->escape($args['dt_id']); } //only return one document type
        if (@$args['return_columns']) { $q .= " GROUP BY `dt_column`"; }
        $q .= " ORDER BY `dt_name`,`dt_code`";
        $query = $this->db->query($q);
        $rows = array();
        $options = array(' ');
        if (@$args['add_new_option']) { $options['[new]'] = '-- New Document Type'; }
        foreach ($query->result_array() as $row) {
            if (@$args['return_columns']) {
                $value = str_replace('/','-',$row['dt_column']); //because of tt/rs
                $options[$value] = $row['dt_name'];
            } elseif (@$args['include_columns']) { $options[$row['dt_id']] = $row['dt_name'].', '.$row['dt_code']; }
            else { $options[$row['dt_id']] = $row['dt_code']; }
            $rows[] = $row;
        }
        if ($return_options) { return $options; } //for a dropdown
        else { return $rows; } //all data
    }

    public function saveParent($args) {
        extract($args);
        $table_name = 'lc_parents';
        $query = $this->db->query("SELECT * FROM `$table_name` WHERE `lcp_filename` = ".$this->db->escape($filename)."");
        $row = $query->row();
        if ($row->lcp_id > 0) {
            return $row->lcp_id;
        } else {
            $this->db->query("INSERT INTO `$table_name` (lcp_filename) VALUES (".$this->db->escape($filename).")");
            return $this->db->insert_id();
        }
    }

    //get the database ready for a preview ($action=start),
    //finalize the preview data ($action=finalize),
    //or destroy the preview data ($action=destroy)
    public function togglePreview($action,$lot_id) {
        if (!in_array($action,array('start','finalize','destroy'))) { die('Error: Unknown togglePreview() action!'); }
        $lot_id = intval($lot_id);
        if ($action == 'start') { //start a new preview "session"
            $this->active_status = 'p'; //p for preview
            if (!$lot_id) { return true; } //we are creating a new lot
            if ($this->session->userdata('preview_lot_id') == $lot_id) { return true; } //a preview has already been started for this lot by this user

            //set session data so that we know a preview is in progress
            $this->session->set_userdata(array('preview_lot_id'=>$lot_id));

            //create new preview rows (a copy of the live rows)
            $query = $this->db->query("SELECT * FROM `lc_lots` WHERE `lot_id` = '$lot_id' AND `status` = 'a' LIMIT 1");
            if ($query->num_rows() > 0) {

                //delete existing preview rows for this lot (only if an active lot exists to copy data from)
                $this->togglePreview('destroy',$lot_id);
                //set session data again because it was destroyed
                $this->session->set_userdata(array('preview_lot_id'=>$lot_id));

                $row = $query->row();
                $cols = $this->db->list_fields('lc_lots');
                $data = array('status'=>'p');
                foreach ($cols as $col) {
                    if ($col != 'status') { $data[$col] = $row->$col; }
                }
                $this->db->insert('lc_lots',$data);
                if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            }

            $cols = $this->db->list_fields('lc_documents');
            $query = $this->db->query("SELECT * FROM `lc_documents` WHERE `lot_id` = '$lot_id' AND `status` = 'a'");
            foreach ($query->result() as $row) {
                $data = array('status'=>'p');
                foreach ($cols as $col) {
                    if ($col != 'status') { $data[$col] = $row->$col; }
                }
                $this->db->insert('lc_documents',$data);
                if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            }
            return true;
        } elseif ($action == 'finalize') {
            if (!$lot_id) { die('Error: No togglePreview() Lot ID!'); }
            
            //delete existing active rows
            $this->db->query("DELETE FROM `lc_lots` WHERE `lot_id` = '$lot_id' AND `status` = 'a'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            $this->db->query("DELETE FROM `lc_documents` WHERE `lot_id` = '$lot_id' AND `status` = 'a'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }

            //change preview rows to active
            $this->db->query("UPDATE `lc_lots` SET `status` = 'a' WHERE `lot_id` = '$lot_id' AND `status` = 'p'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            $this->db->query("UPDATE `lc_documents` SET `status` = 'a' WHERE `lot_id` = '$lot_id' AND `status` = 'p'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }

            $this->active_status = 'a'; //a for active
            $this->togglePreview('destroy',$lot_id);
            return true;
        } elseif ($action == 'destroy') {
            //delete existing preview rows for this lot
            if (!$lot_id) { die('Error: No togglePreview() Lot ID!'); }
            $this->db->query("DELETE FROM `lc_lots` WHERE `lot_id` = '$lot_id' AND `status` = 'p'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            $this->db->query("DELETE FROM `lc_documents` WHERE `lot_id` = '$lot_id' AND `status` = 'p'");
            if ($this->db->_error_message()) { die('togglePreview() database error!'); }
            //clear session data
            $this->session->set_userdata(array('preview_lot_id'=>false));
            return true;
        } else {
            die('Error: Unknown togglePreview() action!');
        }
    }

    //remove a document from a lot (only in preview mode)
    public function remove_document($doc_id) {
        $doc_id = intval($doc_id);
        if (!$doc_id) { return false; }
        $this->db->query("DELETE FROM lc_documents WHERE doc_id = '$doc_id' AND status = 'p'");
        if ($this->db->_error_message()) { return false; }
        else { return true; }
    }

    //add a new catalog number and return the new cat_id
    function saveCatalog($args) {
        $GLOBALS['saveCatalog_error'] = false;
        $cat_no = $args['cat_no'];
        $lcp_id = $args['lcp_id'];
        if (!$cat_no) { return false; }
        if (!$lcp_id) { return false; }
        $query = $this->db->query('SELECT * FROM lc_catalog WHERE cat_no = '.$this->db->escape($cat_no));
        while ($query->num_rows() > 0) {
            $row = $query->row();
            if ($row->lcp_id != $lcp_id) {
                $query2 = $this->db->query('SELECT * FROM `lc_lots` WHERE `cat_id` = '.$this->db->escape($row->cat_id));
                if ($query2->num_rows() < 1) { //if there are no lots using the duplicate category, just delete it to avoid the error
                    $this->db->query('DELETE FROM `lc_catalog` WHERE `cat_id` = '.$this->db->escape($row->cat_id));
                    if ($this->db->_error_message()) { $GLOBALS['saveCatalog_error'] = 'The catalog number <em>'.htmlspecialchars($cat_no).'</em> already exists under another product and could not be deleted.'; return false; }
                } else { $GLOBALS['saveCatalog_error'] = 'The catalog number <em>'.htmlspecialchars($cat_no).'</em> already exists under another product.'; return false; }
            } else { return $row->cat_id; }  //just return the one that's already there
            $query = $this->db->query('SELECT * FROM lc_catalog WHERE cat_no = '.$this->db->escape($cat_no));
        }
        $this->db->query("INSERT INTO lc_catalog (cat_no,lcp_id) VALUES (".$this->db->escape($cat_no).",".$this->db->escape($lcp_id).")");
        if ($this->db->_error_message()) { $GLOBALS['saveCatalog_error'] = "Could not save catalog number to database!"; return false; }
        return $this->db->insert_id(); //return the new cat_id
    }

    //generate results for a preview or for the live site
    public function generateResults($args) {
        $lcp_id = intval($args['lcp_id']);
        if (!$lcp_id) { return false; }
        $query = $this->db->query("SELECT * FROM lc_parents p, lc_ptypes t WHERE p.lcp_id = '$lcp_id' AND p.lcp_type = t.lcpt_type  LIMIT 1");
        if ($query->num_rows() < 1) { return false; }
        $row = $query->row_array();
        $filter = array();
        $filter[] = array('key'=>'lcp','value'=>$lcp_id);
        if ((array_key_exists('region',$args)) && ($args['region'])) { $region = $args['region']; } else { $region = null; }
        $lots = $this->getLots(array('filter'=>$filter,'start'=>0,'limit'=>10000,'region'=>$region,'preview_lot'=>@$args['lot_id']));
        if ((!$lots) || (count($lots) < 1)) { return false; }
        $row['preview_view'] = 'previews/'.substr($row['lcp_filename'],0,-5).'.php';
		$tclass = $row['lcpt_name'];
        $t = '<table border="0" cellspacing="0" cellpadding="0" class="gridtable '.$tclass.'">';
        $t .= '<tr class="mgmtcreds small">';
        $cols = array();
        $cols[] = 'cat_no';
        $cols[] = 'lot_name';
        $cols[] = 'lot_no';
        $cols[] = 'docs_cert';
        $cols[] = 'docs_tt/rs';
        if ($row['lcp_type'] != 1) { $cols[] = 'docs_panel'; }
        if ($row['lcp_type'] != 2) { $cols[] = 'docs_probe_hit'; }
        if ($row['lcp_type'] != 2) { $cols[] = 'docs_core'; }
        $cols[] = 'lot_expiration';
        $cols[] = 'docs_template';
        foreach ($cols as $key => $col) {
            if ($col == 'cat_no') { unset($cols[$key]); $cols[$col] = array('class'=>'catno','caption'=>'Cat. #'); }
            elseif ($col == 'lot_name') { unset($cols[$key]); $cols[$col] = array('class'=>'lotname','caption'=>'LIFECODES Product'); }
            elseif ($col == 'lot_no') { unset($cols[$key]); $cols[$col] = array('class'=>'lotno','caption'=>'Lot #'); }
            elseif ($col == 'lot_expiration') { unset($cols[$key]); $cols[$col] = array('class'=>'exp','caption'=>'Expiration'); }
            else {
                $query2 = $this->db->query("SELECT * FROM lc_doctypes WHERE dt_column = ".$this->db->escape(substr($col,5))." LIMIT 1");
                $row2 = $query2->row();
				$doctypeclass = str_replace("/","",$row2->dt_column);
                unset($cols[$key]); $cols[$col] = array('class'=>'docs '.$doctypeclass,'caption'=>$row2->dt_name);
            }
        }
        foreach ($cols as $key => $col) { $t .= '<td class="'.$col['class'].'" align="left" valign="top"><p><b>'.$col['caption'].'</b></p></td>'; }
        $t .= '</tr>';

        foreach ($lots as $lot_id) {
            if (!array_key_exists('date_format',$args)) { $args['date_format'] = false; }
            $lot = $this->getLotInfo($lot_id,array('docs_real_links'=>true,'region'=>$region,'date_format'=>$args['date_format']));
            $t .= '<tr class="mgmtcreds small">';
            foreach ($cols as $key => $col) {
                $t .= '<td class="'.$col['class'].'" align="left" valign="top">';
                if ($lot[$key]) { 
					$t .= str_replace("<br />","",$lot[$key]); 
				} else {
					$t .= '&nbsp;'; 
				}
                $t .= '</td>';
            }
            $t .= '</tr>';
        }
        $t .= '</table>';

        $row['lot_specific_table'] = $t;
        return $row;
    }
    
    private function getDocumentColumns($args=array()) {
        $cols = array();
        $query = $this->db->query("SELECT * FROM lc_doctypes GROUP BY dt_column");
        foreach ($query->result() as $row) {
            $cols[$row->dt_column] = $row->dt_column;
        }
        return $cols;
    }

    public function saveDocumentType($args) {
        $dt_code = trim(@$args['dt_code']);
        $dt_column = trim(@$args['dt_column']);
        $cols = array(
          'cert'=>'Cert.'
          ,'core'=>'Core Seq.'
          ,'panel'=>'Panel'
          ,'probe_hit'=>'Probe Hit Charts'
          ,'template'=>'Template'
          ,'tt/rs'=>'TT/RS'
        );
        if (!$dt_code) { return false; }
        if (!$dt_column) { return false; }
        elseif (!@$cols[$dt_column]) { return false; } //invalid column
        $query = $this->db->query("SELECT * FROM lc_doctypes WHERE dt_code = ".$this->db->escape($dt_code)." AND dt_column = ".$this->db->escape($dt_column)."");
        if ($query->num_rows() > 0) { //it already exists
            $row = $query->row();
            return $row->dt_id;
        } else {
            $dt_name = $cols[$dt_column];
            $query = $this->db->query("SELECT `order` FROM lc_doctypes WHERE dt_column = ".$this->db->escape($dt_column)." ORDER BY `order` DESC LIMIT 1");
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $order = $row->order + 1;
            } else { $order = 1; }

            $this->db->query("INSERT INTO lc_doctypes (dt_column,dt_name,dt_code,`order`) VALUES (".$this->db->escape($dt_column).",".$this->db->escape($dt_name).",".$this->db->escape($dt_code).",".$this->db->escape($order).")");
            if ($this->db->_error_message()) { return false; }
            return $this->db->insert_id(); //return the new dt_id
        }
    }

}
?>