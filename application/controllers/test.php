<?php
class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
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
        $this->data['title'] = 'Testing';
        if ($this->ion_auth->logged_in()) { $this->data['content'] = "<p>Logged in.</p>"; }
        else { $this->data['content'] = "<p>NOT logged in.</p>"; }
        
        $this->load->view('admin_page',$this->data);
    }


}
?>