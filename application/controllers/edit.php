<?php
class Edit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
		$this->load->library('form_validation');
		$this->load->library('session');
        $this->data = array();
        $this->data['sitetitle'] = $this->config->item('site_name');
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['content'] = "\n\n<!-- Error. -->\n\n"; //this gets replaced if everything works
        $this->data['nav_menu'] = nav_menu(array('logged_in'=>$this->ion_auth->logged_in(),'is_admin'=>$this->ion_auth->is_admin()));
        $this->data['title'] = null;
    }

    public function index() {
        $this->data['title'] = 'Edit Lots';
        $this->data['content'] = "<p>Select a product to continue:</p>";

        $menu_items = array();
        $parents = $this->Lifecodes_model->getAllParents();

        //echo "<pre>"; print_r($parents); echo "</pre>";
        foreach ($parents as $row) { $menu_items[$row['view_url']] = $row['lcp_name']; }
        $this->data['content'] .= full_menu(array('base_url'=>$this->config->item('base_url'),'items'=>$menu_items));

        $this->load->view('admin_page',$this->data);
    }

    //edit a single document
    //this can be called by ajax too (and usually will be)
    public function document($doc_id=null,$screen='full',$file_name=null,$file_id=null,$redirect_lot_id=false) {

        $this->data['title'] = 'Edit Document';

        if ($screen == 'preview') {  //this is coming from a link on the Edit Lot page
            $screen = 'full'; $status = 'p'; $edit_lot = true;
            $this->data['head'] = '<script type="text/javascript" src="'.BASE_URL_RELATIVE.'scripts/jquery-1.5.1.min.js"></script>'; //jQuery needs to be loaded
        } elseif ($screen == 'next') { $screen = 'full'; $status = 'p'; $edit_lot = false; } //this is where the popup submits to
        elseif ($screen == 'popup') { $status = 'p'; $edit_lot = false; } //this is (as the name would imply) within the popup window
        else { $status = 'a'; $edit_lot = false; } //if it's in full screen mode, then we are editing the document directl (and not in a lot preview mode)

        if ($screen == 'popup') { $view = 'admin_popup'; } else { $view = 'admin_page'; }
        
        $error = false;
        if ($doc_id == 'new') { //adding a new document
            $doc = false;
            $file_name = str_replace('__','.',$file_name); //the . is removed when passed through uri.  add it back in here.
            $file_id = intval($file_id);
        } elseif ($doc_id) {
            $doc_id = intval($doc_id);
            $doc = $this->Lifecodes_model->getDocumentInfo($doc_id,array('status'=>$status));
            if (!$doc) { $error = "Invalid Document ID."; }
            else {
                $file_name = $doc['file_name'];
                $file_id = $doc['file_id'];
            }
        } else { $error = 'No Document ID.'; }
        if ((!$error) && (!$file_name)) { $error = "No file name!"; }
        if ($error) {
            $this->data['error'] = $error;
        } else {
            $this->data['content'] = "<p>Please enter the details below for document <strong>".$file_name."</strong>:</p>";
            $this->data['errors'] = null;

            $formdata = array(
                'fields'=>array(
                    'dt_id'=>array('caption'=>'Document Type','type'=>'dropdown')
                    ,'region'=>array('caption'=>'Display Region','type'=>'checkboxes','options'=>array('us'=>'United States','int'=>'Global / International'))
                )
            );
            $formdata['fields']['dt_id']['options'] = $this->Lifecodes_model->getAllDocumentTypes(array('return_options'=>true)); //this isn't actually used now, i don't think

            //setup CodeIgniter rules
            $this->form_validation->set_rules('doc_id', 'Document ID', 'optional|xss_clean');
            $this->form_validation->set_rules('dt_column', 'Document Column', 'optional|xss_clean');
            $this->form_validation->set_rules('new_dt_code', 'Document Type', 'optional|xss_clean');
            foreach ($formdata['fields'] as $key => $f) {
                if ($f['type'] == 'checkboxes') {
                    foreach ($f['options'] as $k => $c) {
                        $this->form_validation->set_rules($key.'_'.$k, $c, 'optional|xss_clean');
                    }
                } else {
                    $this->form_validation->set_rules($key, $f['caption'], 'optional|xss_clean');
                }
            }

            //check to see if the form has been submitted
            if ($this->form_validation->run() == true) { 
                $errors = array();
                $redirect_lot_id = intval($redirect_lot_id); //this is passed through the URL
                if ($redirect_lot_id) { $lot_id = $redirect_lot_id; }
                else {
                    $lot_id = $doc['lot_id']; //if not a new one, grab the saved lot_id
                    if ($edit_lot) { $redirect_lot_id = $lot_id; } //if we reached this page from within edit_lot, we want to redirect back there
                }

                $data = array('doc_id'=>$doc_id,'file_id'=>$file_id,'file_name'=>$file_name,'lot_id'=>$lot_id,'status'=>$status);
                if ($doc_id != $this->form_validation->set_value('doc_id')) { $errors[] = "Document ID mismatch: $doc_id != ".$this->form_validation->set_value('doc_id'); }
                foreach ($formdata['fields'] as $key => $f) {
                    if ($f['type'] == 'checkboxes') { 
                        $data[$key] = array();
                        foreach ($f['options'] as $k => $c) {
                            if ($this->form_validation->set_value($key.'_'.$k) == 'y') { $data[$key][] = $k; }
                        }
                        if (count($data[$key]) == 0) { $errors[] = "You must check at least one ".$f['caption']."."; }
                    } else { 
                        $data[$key] = $this->form_validation->set_value($key);
                        if (($key == 'dt_id') && ($data[$key] == '[new]')) { //a new document type is being added
                            $dt_code = $this->form_validation->set_value('new_dt_code');
                            $dt_column = $this->form_validation->set_value('dt_column');
                            if (!$dt_code) { $errors[] = "When adding a new Document Type, you must enter a name for it."; }
                            elseif (!$dt_code) { $errors[] = "When adding a new Document Type, you must select a column for it."; } //in theory, this error can't happen
                            else {
                                //save new document type and return the ID
                                $data['dt_id'] = $this->Lifecodes_model->saveDocumentType(array('dt_code'=>$dt_code,'dt_column'=>$dt_column));
                            }
                        }
                        if (!$data[$key]) { $errors[] = $f['caption'].' is required.'; }
                    }
                }
                if (count($errors) == 0) { //if no errors, try to save it
                    $new_doc_id = $this->Lifecodes_model->saveDocument($data);
                    if (!$new_doc_id) {
                        $errors[] = "Data could not be saved.";
                        $errors = array_merge($errors,$GLOBALS['saveDocument_errors']);
                    } else {
                        //$doc_link = "<a href=\"".$this->config->item('base_url')."index.php/view/document/$new_doc_id\">$file_name</a>";
                        $doc_link = $file_name;
                        if ($redirect_lot_id) {
                            $message = "<strong>".$doc_link."</strong> was saved successfully.";
                            $this->session->set_flashdata('message',$message);
                            $this->load->helper('url');
                            redirect('/edit/lot/'.$redirect_lot_id, 'refresh');
                        }
                        $this->data['content'] = "<p>".$doc_link." was saved successfully.</p>";
                        $this->load->view('admin_page',$this->data);
                        return true;
                    }
                }
    
                if (count($errors) > 0) {
                    $this->data['errors'] = implode('<br /><br />',$errors);
                }
            }
    
            //set the values to send to the display form
            $values = array();
            foreach ($formdata['fields'] as $key => $f) {
                if (@isset($data[$key])) { $value = $data[$key]; }
                elseif (@$f['type'] == 'checkboxes') { 
                    $value = array();
                    foreach ($f['options'] as $k => $c) {
                        if ($doc[$key.'_'.$k] == 'y') { $value[] = $k; }
                    }
                } else { $value = @$doc[$key]; }
                $values[$key] = $value;
            }

            //echo "<pre>"; print_r($values); echo "</pre>";

            foreach ($formdata['fields'] as $key => $f) {
                if ($key == 'dt_id') { 
                    $options = $this->Lifecodes_model->getAllDocumentTypes(array('return_options'=>true,'return_columns'=>true,'add_new_option'=>false));
                    if ($values['dt_id']) {
                        $dt_id_info = $this->Lifecodes_model->getAllDocumentTypes(array('return_options'=>false,'dt_id'=>$values['dt_id']));
                        $dt_column = @$dt_id_info[0]['dt_column'];
                    } else { $dt_column = false; }
                    $input = form_dropdown('dt_column',$options,$dt_column,'id="dt_column" onChange="populateDocTypes();"')."<br />";
                    if ($dt_column) {
                        $options = $this->Lifecodes_model->getAllDocumentTypes(array('return_options'=>true,'add_new_option'=>true,'return_columns'=>false,'dt_column'=>$dt_column));
                    } else { $options = array(''); }
                    $input .= form_dropdown('dt_id',$options,$values['dt_id'],'id="dt_id" onChange="toggleNewDT();"').'<br />';
                    $input .= form_input(array('name'=>'new_dt_code','id'=>'new_dt_code','value'=>null,'size'=>25,'maxlength'=>100,'style'=>'display:none'));
                    $formdata['fields'][$key]['input'] = $input;
                } elseif ($f['type'] == 'checkboxes') {
                    $formdata['fields'][$key]['input'] = '';
                    foreach ($f['options'] as $k => $c) {
                        $id = $key.'_'.$k;
                        if (in_array($k,$values[$key])) { $checked = true; } else { $checked = false; }
                        $formdata['fields'][$key]['input'] .= form_checkbox(array('name'=>$id,'id'=>$id,'checked'=>$checked,'value'=>'y'))." <label for=\"$id\">$c</label>";
                    }
                }
            }

            $hidden = array('doc_id'=>$doc_id,'status'=>$status);
            $formdata['actions'] = "<p>".form_submit('submit','Save Details')."</p>";
            $uri = $this->uri->uri_string();
            $uri = str_replace('/popup','/next',$uri); //in case we get errors, display them on the full screen version
            $formdata['form_start'] = form_open($uri,null,$hidden);
            $formdata['form_end'] = form_close();

            //$this->data['content'] .= "<p>$debug_info</p>";
            $this->data['content'] .= $this->load->view('edit_document',$formdata,true);
        }
        $this->load->view($view,$this->data);
        return false;
    }

    //edit a single lot
    public function lot($lot_id=null,$lcp_id=null,$action=null) {
        if ($action == 'reset') { $this->Lifecodes_model->togglePreview('destroy',$lot_id); }
        $this->Lifecodes_model->togglePreview('start',$lot_id);
        $lcp_id = intval($lcp_id);
        $this->data['title'] = 'Edit Lot';

        $error = false;
        if (($lot_id == 'new') || (!$lot_id)) {
            $lot_id = null;
            $this->data['title'] = 'Create Lot';
            $lot = array();
        } else {
            $lot_id = intval($lot_id);
            $this->data['title'] = 'Edit Lot';
            if (!$lot_id) { $error = "No Lot ID."; }
            else {
                $lot = $this->Lifecodes_model->getLotInfo($lot_id);
                if (!$lot) {
                    if ($action == 'reset') { $lot_id = null; $lot = array(); $this->data['title'] = 'Create Lot'; } //if a new lot has been reset...
                    else { $error = 'Invalid Lot ID &ndash; could not find Lot '.$lot_id.' in the database.'; }
                } else {
                    $lcp_id = $lot['lcp_id'];
                }
            }
        }
        if ($error) {
            $this->data['error'] = $error;
            $this->load->view('admin_page',$this->data);
            return false;
        }
        if (@$lot['lot_no']) { $this->data['title'] = 'Edit Lot '.$lot['lot_no']; }
        $this->load->helper('form');
        $this->load->helper('dates');

        $formdata = array(
            'fields'=>array(
                'cat_id'=>array('caption'=>'Catalog Number')
                ,'lot_name'=>array('caption'=>'Lot Name')
                ,'lot_no'=>array('caption'=>'Lot Number')
                ,'lot_expiration'=>array('caption'=>'Expiration','type'=>'date')
                ,'region'=>array('caption'=>'Display Region','type'=>'checkboxes','options'=>array('us'=>'United States','int'=>'Global / International'))
             )
        );

        foreach ($formdata['fields'] as $key => $f) {
            if (!@$f['type']) { $formdata['fields'][$key]['type'] = 'text'; }
            if (!@$f['db_col']) { $formdata['fields'][$key]['db_col'] = $key; }
        }

        $this->form_validation->set_rules('lot_id', 'Lot ID', 'optional|xss_clean');
        $this->form_validation->set_rules('new_cat_no', 'New Catalog Number', 'optional|xss_clean');
        foreach ($formdata['fields'] as $key => $f) {
            if ($f['type'] == 'checkboxes') {
                foreach ($f['options'] as $k => $c) {
                    $this->form_validation->set_rules($key.'_'.$k, $c, 'optional|xss_clean');
                }
            } else {
                $this->form_validation->set_rules($key, $f['caption'], 'optional|xss_clean');
            }
        }

        //check to see if the form has been submitted
        $this->data['errors'] = null;
        if ($this->form_validation->run() == true) {

            if (!is_array(@$GLOBALS['errors'])) { $GLOBALS['errors'] = array(); }
            $data = array('lot_id'=>$lot_id);

            //check to see if a new catalog number was added
            if ($this->form_validation->set_value('new_cat_no')) {
                if (!$lcp_id) { $GLOBALS['errors'][] = "To add a new catalog number, a product must first be selected."; } //in theory, this shouldn't ever show up
                else {
                    $cat_no = trim($this->form_validation->set_value('new_cat_no'));
                    if (preg_match('/[^0-9-]/',$cat_no)) { $GLOBALS['errors'][] = "The new catalog number <em>".htmlspecialchars($cat_no)."</em> is invalid. Catalog numbers can only contain numbers and dashes."; }
                    else {
                        $cat_id = $this->Lifecodes_model->saveCatalog(array('cat_no'=>$cat_no,'lcp_id'=>$lcp_id));
                        if ($cat_id) {
                            $data['cat_id'] = $cat_id;
                        } else {
                            if ($GLOBALS['saveCatalog_error']) { $GLOBALS['errors'][] = $GLOBALS['saveCatalog_error']; }
                            else { $GLOBALS['errors'][] = "Couldn't save new catalog number!"; } //this shouldn't happen
                        }
                    }
                }
            }

            if ($lot_id != $this->form_validation->set_value('lot_id')) { $GLOBALS['errors'][] = "Lot ID mismatch: $lot_id != ".$this->form_validation->set_value('lot_id'); }
            foreach ($formdata['fields'] as $key => $f) {
                if (!@$data[$key]) {
                    if (@$f['type'] == 'date') { $data[$key] = getDateBoxesTime($key.'_'); }
                    elseif (@$f['type'] == 'checkboxes') { 
                        $data[$key] = array();
                        foreach ($f['options'] as $k => $c) {
                            if ($this->form_validation->set_value($key.'_'.$k) == 'y') { $data[$key][] = $k; }
                        }
                        if (count($data[$key]) == 0) { $GLOBALS['errors'][] = "You must check at least one ".$f['caption']."."; }
                    } else { $data[$key] = $this->form_validation->set_value($key); }
                }

                if (!$data[$key]) { $GLOBALS['errors'][] = $f['caption'].' is required.'; }
            }
            if (count($GLOBALS['errors']) == 0) { //if no errors, try to save it
                $GLOBALS['debug'] .= "<p>Preparing to saveLot(\$data)... ";
                $new_lot_id = $this->Lifecodes_model->saveLot($data);
                $GLOBALS['debug'] .= "\$new_lot_id generated was $new_lot_id.</p>";

                if (!$new_lot_id) {
                    if (count($GLOBALS['errors']) == 0) { $GLOBALS['errors'][] = "Data could not be saved."; }
                } else {
                    if ($_POST['finalize'] == 'y') { 
                        redirect('edit/finalize_lot/'.$new_lot_id , 'refresh');
                    } else {
                        if (!$lot_id) { $message = "Your lot was created successfully (in preview mode). To have it appear on the live site, click the Finalize Changes button."; }
                        else { $message = "Lot changes were saved successfully (in preview mode). To have your changes appear on the live site, click the Finalize Changes button."; }
                        $this->session->set_flashdata('message',$message);
                        redirect('edit/lot/'.$new_lot_id , 'refresh');
                    }

                }
            } else { $GLOBALS['debug'] .= "<p>saveLot(\$data) was not run because of errors.</p>"; }

            if (count($GLOBALS['errors']) > 0) {
                $this->data['errors'] = implode('<br /><br />',$GLOBALS['errors']);
            }
        }

        //set the values to send to the display form
        $values = array();
        foreach ($formdata['fields'] as $key => $f) {
            if (@isset($data[$key])) { $value = $data[$key]; }
            elseif (@$f['type'] == 'date') { $value = strtotime(@$lot[$f['db_col']]); }
            elseif (@$f['type'] == 'checkboxes') { 
                $value = array();
                foreach ($f['options'] as $k => $c) {
                    if (@$lot[$key.'_'.$k] == 'y') { $value[] = $k; }
                }
            } else { $value = @$lot[$f['db_col']]; }
            $values[$key] = $value;
        }

        foreach ($formdata['fields'] as $key => $f) {
            $value = @$values[$key];
            if ($key == 'cat_id') {
                $options = $this->Lifecodes_model->getCatalog(array('include_blank'=>true,'lcp_id'=>$lcp_id));
                $input = form_dropdown($key,$options,$value);
            } elseif ($key == 'lot_expiration') {
                $input = dateBoxes($stime=$value,$blankdate=true,$beginyear=2000,$endyear=(date('Y')+20),$prefix=$key.'_');
            } elseif (@$f['type'] == 'checkboxes') {
                $input = '';
                foreach ($f['options'] as $k => $c) {
                    $id = $name = $key.'_'.$k;
                    $id .= '_lot'; //so that document region checkbox labels still work
                    if (in_array($k,$values[$key])) { $checked = true; } else { $checked = false; }
                    $input .= form_checkbox(array('name'=>$name,'id'=>$id,'checked'=>$checked,'value'=>'y'))." <label for=\"$id\">$c</label>";
                }
            } else { $input = form_input(array('name'=>$key,'value'=>$value)); }


            if (($key == 'cat_id') && ($lcp_id)) { //to add a new catalog number, the $lcp_id is required
                $input = '<span id="cat_holder">'.$input.' <a href="#" id="launch_add_cat" class="ui-state-default ui-corner-all lifecodes-button"><span class="ui-icon ui-icon-newwin"></span>Add Catalog Number</a></span>'; //this is used by add cat number popup
            }
            $formdata['fields'][$key]['input'] = $input;
        }

        if ($lot_id) {
            $documents = $this->Lifecodes_model->getDocuments(array('lot_id'=>$lot_id));
            if (count($documents) > 0) {
                $table = $this->Lifecodes_model->renderDocumentsTable($documents,array('version'=>'edit_lot','lot_id'=>$lot_id));
                $formdata['documents'] = $table;
            } else {
                $formdata['documents'] = '<p><em>No documents yet.</em></p>';
            }
            $formdata['documents'] .= '<p><a href="#" id="dialog_link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Add New Document</a></p>';
        } else {
            $formdata['documents'] = '<p><em>After you click the Save Changes button to save the lot, you will be able to add documents.</em></p>';
        }

        $hidden = array('lot_id'=>$lot_id,'lcp_id'=>$lcp_id);

        if ($lot_id) { $caption = 'Update Preview'; } else { $caption = 'Continue'; }
        $formdata['main_action'] = "<p><a href=\"#\" id=\"submit_form\" class=\"ui-state-default ui-corner-all lifecodes-button\"><span class=\"ui-icon ui-icon-newwin\"></span>$caption</a></p>";

        $actions = array();
        if ($lot_id) { $actions[] = "<a href=\"#\" id=\"launch_preview\" class=\"ui-state-default ui-corner-all lifecodes-button\"><span class=\"ui-icon ui-icon-newwin\"></span>Preview</a>"; }
        if ($lot_id) { $actions[] = "<a href=\"#\" id=\"reset_button\" class=\"ui-state-default ui-corner-all lifecodes-button\"><span class=\"ui-icon ui-icon-newwin\"></span>Reset</a>"; }
        if ($lot_id) { $actions[] = "<a href=\"#\" id=\"finalize_changes\" class=\"ui-state-default ui-corner-all lifecodes-button\"><span class=\"ui-icon ui-icon-newwin\"></span>Finalize Changes</a>"; }
        if (count($actions) > 0) { $formdata['actions'] = "<p>".implode(' ',$actions)."</p>"; }
        else { $formdata['actions'] = false; }

        if ($lot_id) { $formdata['reset_url'] = $this->config->item('base_url').'index.php/edit/lot/'.$lot_id.'/'.$lcp_id.'/reset'; }
        else { $formdata['reset_url'] = $this->config->item('base_url').'index.php/edit/lot/new/'.$lcp_id; }

        $formdata['form_start'] = form_open(null,array('name'=>'edit_lot_form'),$hidden);
        $formdata['form_end'] = form_close();
        $formdata['lot_id'] = $lot_id;
        $formdata['lcp_id'] = $lcp_id;

        //for popup
        $this->data['head'] = '<link href="'.BASE_URL_RELATIVE.'scripts/uploadify/uploadify.css" type="text/css" rel="stylesheet" />'."\n";
        $this->data['head'] .= '<link type="text/css" href="'.BASE_URL_RELATIVE.'css/gp/jquery-ui-1.8.12.custom.css" rel="stylesheet" />'."\n";
        $this->data['head'] .= '<link type="text/css" href="'.BASE_URL_RELATIVE.'css/edit_lot.css" rel="stylesheet" />';

        //for sortables
        /*$this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.js\"></script>\n";
        $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.core.js\"></script>\n";
        $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.widget.js\"></script>\n";
        $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.mouse.js\"></script>\n";
        $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery.ui.sortable.js\"></script>\n";
        $this->data['head'] .= "<script type=\"text/javascript\" src=\"".BASE_URL_RELATIVE."scripts/jquery_sortables.js\"></script>\n";*/

        if (@$this->session->flashdata('message')) { $this->data['message'] = $this->session->flashdata('message'); }

        $this->data['content'] = '';
        if (@$lot['lcp_id']) { $this->data['content'] .= "<p><a href=\"".site_url("view/lots/lcp/".$lot['lcp_id'])."\">Back to ".$lot['lcp_name']."</a></p>" ; }
        else { $this->data['content'] .= "<p><a href=\"".site_url("view/lots/lcp/".$lcp_id)."\">Back</a></p>" ; }
        $this->data['content'] .= $this->load->view('edit_lot',$formdata,true);
        $this->load->view('admin_page',$this->data);
    }

    //make preview changes live
    public function finalize_lot($lot_id=null) {
        $this->data['title'] = 'Finalize Lot';
        $error = false;
        $lot_id = intval($lot_id);
        if (!$lot_id) { $error = 'No Lot ID.'; }
        else {
            $lot = $this->Lifecodes_model->getLotInfo($lot_id,array('status'=>'p'));
            if (!$lot) { $error = 'Invalid Lot ID.'; }
        }
        if ($error) {
            $this->data['error'] = $error;
            $this->load->view('admin_page',$this->data);
        } else {
            $this->Lifecodes_model->togglePreview('finalize',$lot_id);
            $this->session->set_flashdata('message','Lot changes were successfully finalized.');
            redirect ('view/lot/'.$lot_id , 'refresh');
        }
    }

    //"delete" a lot
    public function delete_lot($lot_id=null,$confirm=null) {
        $this->data['title'] = 'Delete Lot';
        $error = false;
        $lot_id = intval($lot_id);
        if (!$lot_id) { $error = "No Lot ID."; }
        else {
            $lot = $this->Lifecodes_model->getLotInfo($lot_id);
            if (!$lot) { $error = "Invalid Lot ID."; }
        }
        if ($error) {
            $this->data['error'] = $error;
        } else {
            if (intval($confirm) > (time() - 120)) {
                $success = $this->Lifecodes_model->deleteLot($lot_id);
                if ($success) { $this->data['message'] = "Lot was deleted successfully."; }
                else { $this->data['error'] = "Lot could not be deleted!"; }
                $content = false;
            } else {
                $content = "<p>Are you sure you want to delete Lot ".$lot['lot_name']."?</p>" ;
                $content .= "<p><a href=\"".site_url('edit/delete_lot/'.$lot_id.'/'.time())."\">Yes, Delete</a></p>" ;
            }
            $this->data['content'] = $content;
        }
        $this->load->view('admin_page',$this->data);
    }

    //"delete" a file
    public function delete_file($file_id=null,$start=null,$undelete=false) {
        $this->data['title'] = 'Delete File';
        $error = false;
        $file_id = intval($file_id);
        $start = intval($start);
        if (!$file_id) { $error = "No File ID."; }
        else {
            $args = array('file_id'=>$file_id);
            if ($undelete) { $args['include_deleted'] = true; }
            $files = $this->Lifecodes_model->getFiles($args);
            if ((!is_array($files)) || (count($files) < 1)) { $error = "Invalid File ID."; }
        }
        if ($error) {
            $this->data['error'] = $error;
            $this->data['content'] = false;
        } elseif ($undelete) {
            $success = $this->Lifecodes_model->undeleteFile($file_id);
            if ($success) { $this->data['message'] = "File was undeleted successfully."; }
            else { $this->data['error'] = "File could not be undeleted!"; }
            $this->data['content'] = "<p><a href=\"".site_url("view/files/full/".$start)."\">Back to Files</a></p>";
        } else {
            $success = $this->Lifecodes_model->deleteFile($file_id);
            if ($success) { $this->data['message'] = "File was deleted successfully."; }
            else { $this->data['error'] = "File could not be deleted!"; }
            $this->data['content'] = "<p><a href=\"".site_url("view/files/full/".$start)."\">Back to Files</a> &middot; <a href=\"".BASE_URL."edit/delete_file/$file_id/$start/undelete\">Undelete</a></p>";
        }
        $this->load->view('admin_page',$this->data);
    }

    //delete a document type
    public function delete_dt($dt_id=null,$confirm=null) {
        $this->data['title'] = 'Delete Document Type';
        $error = false;
        $dt_id = intval($dt_id);
        if (!$dt_id) {
            $error = "No Document Type ID.";
        } else {
            $types = $this->Lifecodes_model->getAllDocumentTypes(array('dt_id'=>$dt_id));
            if ((!is_array($types)) || (count($types) < 1)) { $error = "Invalid Document Type ID."; }
        }
        if (!$error) {
            reset($types);
            $type = current($types);
            $name = $type['dt_code'];
            if (!$name) { $name = "This document type"; }
            $documents = $this->Lifecodes_model->getDocuments(array('dt_id'=>$dt_id));
            if (count($documents) > 0) { $error = $name." cannot be deleted because there are active documents associated with it. You must delete them before deleting the document type."; }
        }
        if ($error) {
            $this->data['error'] = $error;
            $this->data['content'] = "<p><a href=\"".$this->config->item('base_url')."view/document_types\">Go Back</a></p>" ;
        } else {
            if (intval($confirm) > (time() - 120)) {
                $success = $this->Lifecodes_model->deleteDocumentType($dt_id);
                if ($success) { $this->data['message'] = $name." was deleted successfully."; }
                else { $this->data['error'] = $name." could not be deleted!"; }
                $content = "<p><a href=\"".$this->config->item('base_url')."view/document_types\">Go Back</a></p>" ;
            } else {
                $content = "<p>Are you sure you want to delete ".$name."?  This action cannot be undone!</p>" ;
                $content .= "<p><a href=\"".site_url('edit/delete_dt/'.$dt_id.'/'.time())."\">Yes, Delete</a> &middot; <a href=\"".$this->config->item('base_url')."view/document_types\">No, Go Back</a></p>" ;
            }
            $this->data['content'] = $content;
        }
        $this->load->view('admin_page',$this->data);
    }


    //remove a document from a lot (only in preview mode)
    public function remove_document($doc_id,$lot_id) {
        $this->data['title'] = 'Remove Document';
        $lot_id = intval($lot_id);
        $error = false;
        if (!$lot_id) { $error = "No Lot ID."; }
        elseif (!$doc_id) { $error = "No Document ID."; }
        if ($error) {
            $this->data['error'] = $error;
        } else {
            $success = $this->Lifecodes_model->remove_document($doc_id);
            if ($success) {
                $this->session->set_flashdata('message',"Document was removed successfully.");
                redirect('edit/lot/'.$lot_id , 'refresh');
            } else { $error = "Database error!"; }
        }
        $this->load->view('admin_page',$this->data);
    }


}
?>