<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ingredients_model extends CI_Model
{

    /**
     * CONSTRUCTOR | LOAD DB
     */
    protected $table = 'product_ingredients';
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
            $cek = $this->db->get_where($this->table, ['product_id' => $id])->num_rows();
            if ($cek > 0) {
                $data = $this->db->get_where($this->table, ['product_id' => $id])->result_array();
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
    public function insertUpdate($data, $product_id)
    {
        $ifExist = ifDataExist($this->table, 'product_id', $product_id);
        if ($ifExist) {
            $this->db->delete($this->table, array('product_id' => $product_id));
            $exec = $this->db->insert_batch($this->table, $data['data']);
            if ($exec) {
                $id = $this->db->insert_id();
                $return = [
                    'status' => true,
                    'message' => 'Data berhasil diperbarui.',
                    'id' => $id
                ];
            } else {
                $return = [
                    'status' => false,
                    'message' => 'Data gagal diperbarui.'
                ];
            }
        } else {
            $exec = $this->db->insert_batch($this->table, $data['data']);
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
        $ifExist = ifDataExist($this->table, 'id', $id);
        if ($ifExist) {
            $return = $this->db->update($this->table, $data, array('id' => $id));
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
                'message' => 'ID tidak valid.'
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
        $ifExist = ifDataExist($this->table, 'product_id', $id);
        if ($ifExist) {
            $exec = $this->db->delete($this->table, array('product_id' => $id));
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
