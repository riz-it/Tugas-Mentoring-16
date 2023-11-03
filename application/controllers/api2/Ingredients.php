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

class Ingredients extends REST_Controller
{

    /**
     * CONSTRUCTOR | LOAD MODEL
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ingredients_model');
    }

    /**
     * SHOW | GET method.
     *
     * @return Response
     */
    public function index_get($id = null)
    {
        

        if (!empty($id)) {
            $data = $this->Ingredients_model->show($id);
        } else {
            $data = $this->Ingredients_model->show();
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
        
        $input = $this->_post_args;

        foreach($input['data'] as $key => $value) {
            $input['data'][$key]['product_id'] = $input['product_id'];
        }

        $exec = $this->Ingredients_model->insertUpdate($input, $input['product_id']);
        if ($exec['status'] == false) {
            $this->response([
                'status'=> false, 
                'message' => $exec['message'],
            ], 400);
        } else {
            $this->response([
                'status'=> true, 
                'message' => $exec['message'],
            ], 200);
        }
    }

   
}
