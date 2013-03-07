<?php

class Downloads extends CI_Controller {

    public function __construct() {
        parent::__construct();

		if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('ftp');
		$this->load->library('table');
		
		$this->load->helper('download');
		$this->load->helper('form');
		
        $this->data = array();
		
		/* config // FTP data */
		/* OLD DEV FTP */
		/*
		$config['hostname'] = 'lifecodes.magneticcreative.com';
		$config['username'] = 'lifecodes';
		$config['password'] = 'lot_specific';
		*/
		
		$config['hostname'] = 'gen-probe.com';
		$config['username'] = 'genpro';
		$config['password'] = 'masOxypiiw2Q';
		
		$config['debug'] = TESTING; //set in constants.php
		
		$this->ftp->connect($config);
		
		
		/* Site & Path Info */
        $this->data['sitetitle'] = $this->config->item('site_name');
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['content'] = "\n\n<!-- Error. -->\n\n"; //this gets replaced if everything works
		
		/* FTP Class Path */
		$this->data['server_base'] = 'C:/inetpub/vhosts/gen-probe.com';
        $this->data['ftpbase'] = '/httpdocs/uploads/File/product/'; //replace with dynamic value?
		$this->data['workingbase'] = '../uploads/File/product';
        $this->data['document_root'] = $_SERVER['DOCUMENT_ROOT'];
		
		$this->data['nav_menu'] = nav_menu(array('logged_in'=>$this->ion_auth->logged_in(),'is_admin'=>$this->ion_auth->is_admin()));
        $this->data['title'] = null;
		
    }

    public function index() {
		
		/*
		* To-Do: Clean up
		*/
		
		$action = $this->input->post('action');
		/*
		//debug, can remove:
		if(isset($_FILES['file_upload']['type'])) {
			print "<pre>\n";
			print_r($_FILES);
			print "</pre>\n";
		}
		*/
		
		if($action) {
			
			/*
			*
			* Combined the downloads/upload "page" function into
			* the index() ... to avoid AJAX issues with file/forms
			* ~NSC 06/11/2011
			*
			*/
			
			$path = urldecode($this->input->post('path'));
			
			//build info for our subsequent display:
			$fileroot = $path;
			$fullFileRoot = $this->_getDirFromPath($fileroot);
			
			$path = $this->_fullFilePath($path);
			//echo "Document Root: ".$this->data['document_root']."<br />";
			//echo "Path: ".$path."<br />";
			
            if ($action == 'mkDir') { //make directory
                $newdir = trim(strip_tags($this->input->post('mkDirName')));
				//echo "New Dir: ".$newdir."<br />";
                if (!$newdir) { $this->data['error'] = 'No new directory name specified.'; }
                else {
					//convert windows "\" to ftp-friendly "/"
					$converted_path = str_replace("\\", "/", $path);
					//echo "Converted: ".$converted_path."<br />";
					//find the strpos of the ftp base in the converted path
					$pos = strpos($converted_path, $this->data['ftpbase']);
					//then, remove everything before it....
					$trimmed_path = substr($converted_path, $pos, strlen($converted_path));
					//echo "Trim: ".$trimmed_path."<br />";
					//to leave us our new ftp-friendly directory:
                    $fullnewdir = $trimmed_path.$newdir;
					//echo "Full New Dir Path: ".$fullnewdir."<br />";
                    if (file_exists($fullnewdir)) { $this->data['error'] = 'Directory '.$newdir.' already exists.'; }
                    elseif ($this->ftp->mkdir($fullnewdir)) {
                        $this->data['message'] = 'Success! Your new directory was created.';
                        $this->ftp->chmod($fullnewdir,DIR_WRITE_MODE);
                    } else { $this->data['error'] = 'Something went wrong when we tried to create that directory.'; }
                }

            } else { 

				//file upload
    			$field = 'file_upload';

    			$config['upload_path'] = $path;
    			$config['allowed_types'] = 'xml|txt|dat|vda|pdf|zip|eds|idt|exp|csv';
    			$config['max_size']	= '0';
    			$config['max_width']  = '0';
    			$config['max_height']  = '0';
    			
    			$this->load->library('upload', $config);

    			if ( ! $this->upload->do_upload($field))
    			{
    				$this->data['error'] = $this->upload->display_errors('','');
                    if (TESTING) {
                        $perms = base_convert(fileperms($path), 10, 8); 
                        $perms = substr($perms, (strlen($perms) - 3)); 
                        $this->data['content'] = "<p>Attempted to upload file to $path ($perms).</p>";
                        $data = array('upload_data' => $this->upload->data());
                        $mimetype = $data['upload_data']['file_type'];
                        $this->data['content'] .= "<pre>".print_r($data,true)."</pre>";
                    }
    			}
    			else
    			{
    				$xdata = array('upload_data' => $this->upload->data());
    				$uploaded_file_name = $xdata['upload_data']['file_name'];
    				$uploaded_file_type = $xdata['upload_data']['file_type'];
    				$uploaded_file_ext = $xdata['upload_data']['file_ext'];
    				$uploaded_file_size = $xdata['upload_data']['file_size'];
    				
    				
    				
    				$this->data['content'] .= "<p class=\"status_message\"><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span><strong>Success</strong> Uploaded <b>$uploaded_file_type</b> (<i>{$uploaded_file_size}k</i>)</p>\n\n";
    			}
			} //end if file upload
			
			
			
		} else {
		
			//Initial work: determine the path from the GET
			//some of this is redundant, TO-DO: refactor
			$path = $this->input->get('path');
			(!$path) ? $fileroot = $this->data['ftpbase'] : $fileroot = $path;
			$fullFileRoot = $this->_fullFilePath($fileroot);
			$dl_filename = $this->_lastPathElement($fileroot);
			
			/*
			* Force Download of File(s), Not Dir(s)
			*/
			
			if(!is_dir($fullFileRoot)) {
				//it's not a directory, let's download (for now)
				// to-do: handler?
				$data = file_get_contents($fullFileRoot); // Read the file's contents
				$name = $dl_filename;
				
				//echo "force_download($name, $data);<br />\n";
				force_download($name, $data);
	  
				
			}
		}
		
		// Add our breadcrumb trail, created by our private function (below)
		$this->data['content'] .= "\n\n<div id=\"breadcrumbs\">".$this->breadcrumbs($fileroot)."</div>\n\n";
  
		//List the Files via CI FTP method
		$list = $this->ftp->list_files($fileroot);
		
		//Begin building the table:
		$tmpl = array ( 'table_open'  => '<table border="0" cellpadding="0" cellspacing="0" class="ftptable">' );
		$this->table->set_template($tmpl);
		
		$t_array = array(array('Type','Filename','Size','Action'));
		
		//Pack the table with the necessary elements
		foreach($list as $index => $filename) {
			
			//Some of these are redundant, TO-DO: refactor
			$newfilename = $this->_fullFilePath($filename);
			$trimPath = $this->_lastPathElement($filename);
			
			if(is_dir($newfilename)) { $trimPath = $trimPath."/"; } 
			
			//Build the link
			$fileLink = $fileroot.$trimPath;
			$filetext = "<a href=\"".site_url("downloads/?path=".urlencode($fileLink))."\">$trimPath</a>\n";
			
			//determine directory or file, so we can css appropriately
			if(is_dir($newfilename)) {
				$type = 'dir';
				$typetext = "<a class=\"dir\" href=\"\"><img src=\"".BASE_URL."images/icons/folder.png\" height=\"16\" width=\"16\" /></a>";
				$filesize = '';
				//$trimPath = $trimPath."/";
			} else {
				$type = 'file';
				$typetext = "<a class=\"file\" href=\"\"><img src=\"".BASE_URL."images/icons/page.png\" height=\"16\" width=\"16\" /></a>";
				$filesize = $this->_formatBytes(filesize($newfilename));
			}
			
			//build the actions
			//TO-DO: How will we deal with directories? 
			($type == 'dir') ? $actions = "<a href=\"".site_url("downloads/?path=".urlencode($fileLink))."\"><img src=\"".BASE_URL."images/icons/magnifier.png\" /></a> <a class=\"delete\" href=\"".urlencode($fileLink)."\"><img src=\"".BASE_URL."images/icons/cross.png\" /></a>\n" : $actions = "<a class=\"delete\" href=\"".urlencode($fileLink)."\"><img src=\"".BASE_URL."images/icons/cross.png\" /></a>\n";
			
			//Add line to the array
			$t_array[] = array($typetext, $filetext, $filesize, $actions);
		}	
  
		//Use CI Table Class to generate table:
		$table = $this->table->generate($t_array);
		$this->data['content'] .= $table;
		
		//Add our Upload Button:
		$this->data['content'] .= "<button id=\"uploadbutton\">Upload File</button>\n\n";
		$this->data['content'] .= "<button id=\"mkDirButton\">Create New Dir</button>\n\n";
		
		//Factor in some data for our mini-upload form (like full path for the file)
		$current_directory = $this->_getDirFromPath($fileroot);
		$this->data['upload_dir'] = $current_directory;
		
		/*
		* Build the Forms
		*/
		
		// Form 1. File Upload
		$this->data['content'] .= "<div id=\"uploadform\" title=\"File Uploader\" style=\"display:none;\">\n\n";
		$this->data['content'] .= $this->_buildUploadForm($current_directory);
		$this->data['content'] .= "</div>\n\n";
		
		// Form (sort-of) 2. Confirm Dialog (and receiver of our AJAX'd info for Deletes)
		$this->data['content'] .= "<div id=\"dialog-confirm\" title=\"File Delete\" style=\"display:none;\">\n";
		$this->data['content'] .= "	<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 40px 0;\"></span>This item will be permanently deleted and cannot be recovered. Are you sure?</p>\n";
		$this->data['content'] .= "</div>\n";
		
		// Form 3. Make Directory
		$this->data['content'] .= "<div id=\"mkdirform\" title=\"Create Directory\" style=\"display:none;\">\n\n";
		$this->data['content'] .= $this->_buildMkDirForm($current_directory);
		$this->data['content'] .= "</div>\n\n";
	  
	  
	  // Close FTP connection
	  $this->ftp->close();
	  
	  //Load the view
      if ($this->session->flashdata('message')) { $this->data['message'] = $this->session->flashdata('message'); }
      if ($this->session->flashdata('error')) { $this->data['error'] = $this->session->flashdata('error'); }
      
	  $this->load->view('downloads',$this->data);
		
    }
	
	
	/* Can we access this only through AJAX?*/
	public function delete() {

		$path = urldecode($this->input->post('path'));
        $fullpath = $this->data['server_base'].$path;
                
        $is_dir = is_dir($fullpath);
        $message = 'Attempting to delete '.$path;
        if (!file_exists($fullpath)) { $message .= " <strong>(doesn't exist)</strong>"; }
        elseif ($is_dir) { $message .= " (a directory)."; }
        else { $message .= " (a file)."; }
        $this->session->set_flashdata('message', $message);
        if ($is_dir) {
    		if(!$this->ftp->delete_dir($path)) {
    			$this->session->set_flashdata('error', "Couldn't delete directory!");
                echo "Error! ";
    		} else {
    			//Success!!
    			$this->session->set_flashdata('message', "Directory deleted successfully.");
    			echo "Success";
    		}
        } else { //file
    
    		if(!$this->ftp->delete_file($path)) {
    			$this->session->set_flashdata('error', "Couldn't delete file!");
    			echo "Error! "; //. $this->upload->display_errors();
    		} else {
    			//Success!!
    			$this->session->set_flashdata('message', "File deleted successfully.");
    			echo "Success";
    		}
    	}
		
		echo $this->data['content'];
		
		
	}
	
	
	
	/*
	* Private Functions
	*/

	private function breadcrumbs($path, $separator = "&raquo;") {
		unset($elements);
		
		$elements = explode("/", $path);
		$exclusions = array(" ", "httpdocs", "uploads", "File", "product");
		//print_r($elements);
		
		$bctext = "<li class=\"first\">\n";
		$bctext .= "<a href=\"".site_url("downloads/")."\">Manage Downloads</a>\n";
		$bctext .= $separator;
		$bctext .= "</li>\n";
			
		foreach($elements as $key => $segment) {
			if(in_array($segment, $exclusions) || $segment == '') { continue; }
			
			//but build the link:
            $link = '';
			for($i = 0; $i<=$key; $i++) {
				if(!in_array($elements[$i], $exclusions) || $elements != '') {
					$link .= $elements[$i]."/";
				}
			}
			if($key == (count($elements) - 1)) { $li_class = 'last'; } else { $li_class = "bc{$key}"; }
			$bctext .= "<li class=\"$li_class\">\n";
			$bctext .= "<a href=\"".site_url("downloads/?path=".urlencode($link))."\">".$elements[$i-1]."</a>\n";
			$bctext .= $separator;
			$bctext .= "</li>\n";
		}
		
		$bctext = "<ul>\n".$bctext."</ul>\n";
		return $bctext;
		
	}
	
	private function _buildUploadForm($current_directory) {
		
		$form = "\n<div id=\"formInnerWrap\">\n";
		$attributes = array('class' => 'ftp', 'id' => 'upload');
		
		$form .= form_open_multipart('downloads/?path='.urlencode($current_directory), $attributes)."\n";	
		//$form .= form_label('Upload File', 'newfile');
		$data = array(
			'name'	=> 'file_upload',
            'id'	=> 'file_upload'
		);
		$form .= form_upload($data)."\n";
		$form .= form_hidden('path', urlencode($current_directory))."\n";
		$form .= form_hidden('action', 'upload')."\n";
		$form .= form_submit('submit', 'Upload File')."\n";
		$form .= form_close()."\n";
		
		$form .= "</div><!--// end #formInnerWrap -->\n\n";
		
		return $form;
	}
	
	private function _buildMkDirForm($current_directory) {
		$form = "\n<div id=\"mkDirInnerWrap\">\n";
		$attributes = array('class' => 'ftp', 'id' => 'mkdir');
		
		$form .= form_open('', $attributes)."\n";	
		$form .= form_label('New Dir Name', 'mkDirName');
		$data = array(
			'name'	=> 'mkDirName',
            'id'	=> 'mkDir'
		);
		$form .= form_input($data)."\n";
		$form .= form_hidden('path', urlencode($current_directory))."\n";
		$form .= form_hidden('action', 'mkDir')."\n";
		$form .= "<button id=\"mkDirSubmit\">Make</button>\n";
		$form .= form_close()."\n";
		
		$form .= "</div><!--// end #mkDirInnerWrap -->\n\n";
		
		return $form;
	}
	
	private function _fullFilePath($filepath) {
		
		$last = $this->_lastPathElement($filepath);
		$filepath = str_replace('/httpdocs', '', $filepath);
		
		$returnfile = $this->data['document_root'].$filepath;
		
        //had to do this because the symbolic link was throwing an error on all the file functions
        if (@$GLOBALS['site_loc'] == 'rjr') { $returnfile = str_replace('C:/xampp/htdocs/uploads/File/product/','C:/Users/rjr/Documents/Ink Plant/Magnetic/Genprobe/Code/myadmin/uploads/File/product/',$returnfile); }
		return $returnfile;
		
	}
	
	private function _lastPathElement($path) {
		
		$elements = explode("/", $path);
		$relements = array_reverse($elements);
		$returnfile = $relements[0];
		
		return $returnfile;
		
	}
	
	private function _getDirFromPath($path) {
		//find out if we're dealing with a file
		$fullFilePath = $this->_fullFilePath($path);
		if(!is_dir($fullFilePath)) {
			$file = $this->_lastPathElement($path);
			$dir = str_replace($file, '', $path);
		} else {
			$dir = $path;
		}
		
		
		return $dir;
		
	}
	
	private function _formatBytes($size) {
	  $units = array(' B', ' KB', ' MB', ' GB', ' TB');
	  for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	  
	  return round($size, 2).$units[$i];
	}

}
?>
