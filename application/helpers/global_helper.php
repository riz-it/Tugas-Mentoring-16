<?php
if (!function_exists('bulan')) {
    function ifDataExist($table, $column, $param)
    {
        $CI = &get_instance();
        $CI->load->database();
        $cek = $CI->db->get_where($table, [$column => $param])->num_rows();
        if ($cek > 0) {
            return true;
        } else {
            return false;
        }
    }
}
