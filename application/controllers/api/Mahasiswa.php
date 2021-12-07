<?php 
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Mahasiswa extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mahasiswa_model', 'mhsModel');

        $this->methods['index_get']['limit'] = 50; // 50 hit per hour
    }


    public function index_get(){
        $id = $this->get('id');

        if ($id === null){
            $mahasiswa = $this->mhsModel->getMahasiswa();
        } else {
            $mahasiswa = $this->mhsModel->getMahasiswa($id);
        }
        
        if ($mahasiswa) {
            $this->response([
                'status' => TRUE,
                'data' => $mahasiswa
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

    }



    public function index_delete() {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => FALSE,
                'message' => 'Delete operation requires an ID'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->mhsModel->deleteMahasiswa($id) > 0) {
                $this->response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'Deleted successfully'
                ], REST_Controller::HTTP_NO_CONTENT);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Failed deleting data'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }



    public function index_post() {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan')
        ];

        if ($this->mhsModel->createMahasiswa($data) > 0) {
            $this->response([
                'status' => TRUE,
                'message' => 'Data saved successfully'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed saving data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

    }



    public function index_put() {
        $id = $this->put('id');

        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan')
        ];

        if ($this->mhsModel->updateMahasiswa($data, $id) > 0) {
            $this->response([
                'status' => TRUE,
                'message' => 'Data updated successfully'
            ], REST_Controller::HTTP_NO_CONTENT);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Failed saving data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

    }
}