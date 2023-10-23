<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category_model extends CI_Model
{

    /**
     * CONSTRUCTOR | LOAD DB
     */
    protected $table = 'categories';
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function show($id = 0)
    {
        if (!empty($id)) {
            $cek = $this->db->get_where($this->table, ['category_id' => $id])->num_rows();
            if ($cek > 0) {
                $data = $this->db->get_where($this->table, ['category_id' => $id])->row_array();
                $query = [
                    'status' => true,
                    'data' => $data
                ];
            } else {
                $query = [
                    'status' => false,
                ];
            }
        } else {
            $data1 = $this->db->get($this->table)->result();
            $query = [
                'status' => true,
                'data' => $data1
            ];
        }
        return $query;
    }

    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function insert($data)
    {
        $exec = $this->db->insert($this->table, $data);
        if ($exec) {
            $id = $this->db->insert_id();
            $return = [
                'status' => true,
                'message' => 'Data berhasil ditambahkan.',
                'id' => $id
            ];
        } else {
            $return = [
                'status' => false,
                'message' => 'Data gagal ditambahkan.'
            ];
        }
        return $return;
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function update($data, $id)
    {
        $ifExist = ifDataExist($this->table, 'category_id', $id);
        if ($ifExist) {
            $return = $this->db->update($this->table, $data, array('category_id' => $id));
            $isUpdated = $this->db->affected_rows();
            if ($isUpdated > 0) {
                $return = [
                    'status' => true,
                    'message' => 'Berhasil memperbarui data.'
                ];
            } else {
                $return = [
                    'status' => false,
                    'message' => 'Gagal memperbarui data.'
                ];
            }
        } else {
            $return = [
                'status' => false,
                'message' => 'ID Kategori valid.'
            ];
        }
        return $return;
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function delete($id)
    {
        $ifExist = ifDataExist($this->table, 'category_id', $id);
        if ($ifExist) {
            $exec = $this->db->delete($this->table, array('category_id' => $id));
            if ($exec) {
                $query = [
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ];
            } else {
                $query = [
                    'status' => false,
                    'message' => 'Data gagal dihapus'
                ];
            }
        } else {
            $query = [
                'status' => false,
                'message' => 'ID Kategori tidak valid.'
            ];
        }
        return $query;
    }
}
