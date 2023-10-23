<?php

/* Table structure for table `products` */
// CREATE TABLE `products` (
//   `id` int(10) UNSIGNED NOT NULL,
//   `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
//   `price` double NOT NULL,
//   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//   `updated_at` datetime DEFAULT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
// ALTER TABLE `products` ADD PRIMARY KEY (`id`);
// ALTER TABLE `products` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; COMMIT;

/**
 * Product class.
 * 
 * @extends REST_Controller
 */
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Product_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($id = null)
    {
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

        if (!$decodedToken['status']) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        if (!empty($id)) {
            $data = $this->Product_model->show($id);
        } else {
            $data = $this->Product_model->show();
        }

        if ($data['status'] == false) {
            $this->response(['status'=> false, 'message' => 'Data tidak ditemukan.'], 404);
        } else {
            $this->response(['status'=> true, 'message' => 'Menampilkan data produk', 'data' => $data['data']], 200);
        }
    }


    /**
     * INSERT | POST method.
     *
     * @return Response
     */
    public function index_post()
    {
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

        if (!$decodedToken['status']) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $input = $this->_post_args;
        $this->form_validation->set_data($input);
        $validate = $this->validationFormAdd();


        if (!$validate['status']) {
            $this->response([
                'status'=> false, 
                'message' => $validate['message'],
                'errors' => $validate['errors']
            ], 400);
        }

        $exec = $this->Product_model->insert($input);
        if ($exec['status'] == false) {
            $this->response([
                'status'=> false, 
                'message' => $exec['message'],
            ], 400);
        } else {
            $data = $this->Product_model->show($exec['id']);

            if ($data['status'] == false) {
                $this->response(['status'=> false, 'message' => 'Data tidak ditemukan.'], 404);
            } else {
                $this->response(['status'=> true, 'message' => 'Data berhasil ditambahkan.', 'data' => $data['data']], 201);
            }
        }
    }

    /**
     * UPDATE | PUT method.
     *
     * @return Response
     */
    public function index_put($id)
    {
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

        if (!$decodedToken['status']) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $input = $this->_put_args;
        if(empty($input)){
            $this->response(['status'=> false, 'message' => 'Parameter tidak boleh kosong'], 400);
        }

        $exec = $this->Product_model->update($input, $id);
        if ($exec['status'] == false) {
            $this->response([
                'status'=> false, 
                'message' => $exec['message'],
            ], 400);
        } else {
            $data = $this->Product_model->show($id);
            if ($data['status'] == false) {
                $this->response(['status'=> false, 'message' => 'Data tidak ditemukan.'], 404);
            } else {
                $this->response(['status'=> true, 'message' => 'Data berhasil diperbarui.', 'data' => $data['data']], 200);
            }
        }
    }

    /**
     * DELETE method.
     *
     * @return Response
     */
    public function index_delete($id)
    {
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

        if (!$decodedToken['status']) {
            $this->response(['status'=> false, 'message' => 'Autentikasi gagal.'], 400);
            return;
        }

        $data = $this->Product_model->delete($id);

        if ($data['status'] == false) {
            $this->response(['status'=> false, 'message' => 'Data tidak ditemukan.'], 404);
        } else {
            $this->response(['status'=> true, 'message' => 'Data berhasil dihapus.'], 200);
        }
    }

    private function validationFormAdd($arrayError = true)
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('product_name', 'Nama Produk', 'required');
        $this->form_validation->set_rules('category_id', 'ID Kategori', 'required');
        if ($this->form_validation->run() === FALSE) {
            $error = $this->form_validation->error_string();
            if ($arrayError) {
                $error = $this->form_validation->error_array();
            }
            return [
                'status'    => false,
                'message'   => 'Form tidak valid',
                "errors"    => $error,
            ];
        }
        return [
            'status'    => true,
            'message'   => 'Form valid',
            'errors'    => null,
        ];
    }
   
}
