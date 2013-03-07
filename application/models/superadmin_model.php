<?php
class Superadmin_model extends CI_model {

    //this is to completely remove a product (lc_parents table) and all of its associated lots
    //WARNING: THIS DELETES STUFF! DON'T RUN IT UNLESS YOU MEAN IT.
    public function deleteProduct($lcp_id) {
        die("This function hasn't been updated to reflect the removal of the lcp_id column from the lc_lots table. Fix before running.");
        $lcp_id = intval($lcp_id);
        if (!$lcp_id) { die('no $lcp_id'); }
        $q = array();
        $q[] = "DELETE FROM `lc_parents` WHERE `lcp_id` = '$lcp_id'";
        $query = $this->db->query("SELECT `lot_id` FROM `lc_lots` WHERE `lcp_id` = '$lcp_id' ORDER BY `lot_id`");
        foreach ($query->result() as $row) {
            $q[] = "DELETE FROM `lc_documents` WHERE `lot_id` = '".$row->lot_id."'";
        }
        $q[] = "DELETE FROM `lc_lots` WHERE `lcp_id` = '$lcp_id'";
        foreach ($q as $query) {
            echo "<p>$query";
            $query = $this->db->query($query);
            echo " - ".$this->db->affected_rows()." row(s) affected.";
            echo "</p>";
        }
    }

}
?>