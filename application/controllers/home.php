<?php
class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
        $this->data = array();
        $this->data['sitetitle'] = $this->config->item('site_name');
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['content'] = "<p class=\"alert\">Page load error!</p>" ;
        $this->data['nav_menu'] = nav_menu(array('logged_in'=>$this->ion_auth->logged_in(),'is_admin'=>$this->ion_auth->is_admin()));
        $this->data['title'] = 'Home';
    }

    public function index() {
        $this->data['content'] = "<p>Welcome to ".$this->config->item('site_name').", ".$this->ion_auth->get_user()->first_name.".</p>";

        $menu_items = array(
          site_url("edit")=>'Edit Lots'
          ,site_url("view/documents") =>'Manage Documents'
          ,site_url("downloads")=>'Manage Downloads'
        );
        $this->data['content'] .= full_menu(array('items'=>$menu_items));

        $this->load->view('admin_page',$this->data);
    }

}
?>