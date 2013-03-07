<?php
class Superadmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) { redirect('auth/login'); }
        $this->load->model('Superadmin_model');
        echo "<h1>Superadmin</h1>";
    }

    public function index() {
        echo "<p>Welcome.</p>";
    }

    public function delete_product($lcp_id=null) {
        if ($lcp_id) {
            die('Not now.'); //comment this out when you actually want to run this
            $this->Superadmin_model->deleteProduct($lcp_id);
        } else { echo "<p>No product selected.</p>"; }
    }

    public function delete_user($user_id=null) {
        $user_id = intval($user_id);
        if ($user_id) {
            die('Not now.'); //comment this out when you actually want to run this
            $tables = $this->config->item('tables','ion_auth');
            $this->db->query("DELETE FROM `".$tables['users']."` WHERE `id` = '$user_id'");
            echo "<p>".$this->db->last_query()."</p>";
            $this->db->query("DELETE FROM `".$tables['meta']."` WHERE `user_id` = '$user_id'");
            echo "<p>".$this->db->last_query()."</p>";
            echo "<p>User $user_id deleted.</p>";
        } else { echo "<p>No user selected.</p>"; }
    }

    public function edit_doc_types() {
        echo "<p>The following MySQL queries can be used to edit link text for document types. You need to update the new name for each, of course.</p>\n";
        $query = $this->db->query("SELECT dt_code FROM lc_doctypes GROUP BY dt_code ORDER BY dt_code");
        foreach ($query->result() as $row) {
            echo "<p>UPDATE `lc_doctypes` SET `dt_code` = '".$row->dt_code."' WHERE `dt_code` = ".$this->db->escape($row->dt_code).";</p>\n";
        }
        echo "\n<hr />\n\n" ;
        echo "<p>The following MySQL queries can be used to add a new document type. One is included for each column.  You need to add the dt_code (link caption).</p>\n";
        $this->add_doc_type();
    }

    public function add_doc_type($insert_after=false) {
        if ($insert_after) {
            $insert_after = strip_tags(urldecode($insert_after));
            $query = $this->db->query("SELECT * FROM lc_doctypes WHERE dt_code = ".$this->db->escape($insert_after));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $where = " WHERE dt_column = ".$this->db->escape($row->dt_column);
                $next_order = ($row->order + 1);
            } else { die("Couldn't find ".htmlspecialchars($insert_after)."!"); }
        } else {
            $next_order = '(MAX(`order`) + 1)';
            $where = '';
        }

        //update existing orders
        if ($insert_after) {
            echo "<p>UPDATE `lc_doctypes` SET `order` = (`order` + 1) ".$where." AND `order` >= ".$next_order.";</p>\n";
        }

        //generate insert query/ies
        $query_string = "SELECT dt_name,dt_column,".$next_order." AS `next_order` FROM lc_doctypes".$where." GROUP BY dt_column ORDER BY dt_column";
        $query = $this->db->query($query_string);
        foreach ($query->result() as $row) {
            echo "<p>INSERT INTO `lc_doctypes` (`dt_code`,`dt_name`,`dt_column`,`order`) VALUES ('',".$this->db->escape($row->dt_name).",".$this->db->escape($row->dt_column).",".$this->db->escape($row->next_order).");</p>\n";
        }

    }
}
?>