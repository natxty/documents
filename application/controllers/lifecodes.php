<?php
class Lifecodes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Lifecodes_model');
    }

    public function helloworld() {
        $data = array();
        $data['content'] = $this->Lifecodes_model->tester();
        //$data['content'] = 'test';
        $data['title'] = 'Hello world';
        $this->load->view('admin_page',$data);
    }
}
?>