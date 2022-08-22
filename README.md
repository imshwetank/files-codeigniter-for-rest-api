# files-codeigniter-for-rest-api

Step 1: API Adding Language: Copy the file named rest_controller_lang.php and paste the file in your project in the following path:  application/language/english/rest_controller_lang.php 

Step 2: Copy the file named rest.php and paste it in the following path: application/config/rest.php

Step 3: Copy the file named RestController.php and Format.php and paste it in the following path: application/libraries/RestController.php & application/libraries/Format.php


Securing the API

METHOD - 1:
Now, Once your API is built. it needs securing. So the only users who have the access (Login Credentials) can get data through API.

1. To set the login credentials which is username and password & open the rest.php file in the following path -  application/config/rest.php and search for 

$config['rest_valid_logins'] = ['admin' => '1234'];
2. Change these details into 

// default
$config['rest_auth'] = FALSE;

// Change it to
$config['rest_auth'] = 'basic';
3. once we set the $config['rest_auth'] = 'basic' we have to give one more permission to access this as follows:

// default
// $config['auth_source'] = 'ldap';

// Change it to
$config['auth_source'] = '';
Now, Open POSTMAN Software and choose Authorization and select TYPE as Basic Auth and then provide your username and password as shown above.

METHOD - 2:
if you want to make API Key for each USERS to get the data from the API Call. Please watch out the Video, it is explained very clearly how it work:

https://www.youtube.com/watch?v=0N6frAVa2GE&list=PLRheCL1cXHrtV_KV9eNobLJnXMtgQd_x0



So, Let's get started to get data by interaction of Database:

Step 1: Create a table named products as given below:

CREATE TABLE IF NOT EXISTS `students` (

    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `class` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
  
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=


Step 2: Create a model named StudentModel.php in following path: application/models/StudentModel.php

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StudentModel extends CI_Model
{

    public function get_student()
    {
        $query = $this->db->get("students");
        return $query->result();
    }

    public function insert_student($data)
    {
        return $this->db->insert('students', $data);
    }

    public function edit_student($id)
    {
        $this->db->where('id',$id);
        $query = $this->db->get('students');
        return $query->row();
    }

    public function update_student($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('students', $data);
    }

    public function delete_student($id)
    {
        return $this->db->delete('students', ['id' => $id]);
    }
    
}

?>


Step 3: Create a controller named "ApiStudentController.php" in the following path: application/controllers/api/ApiStudentController.php

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';

use chriskacerguis\RestServer\RestController;

class ApiStudentController extends RestController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('StudentModel');
    }

    public function indexStudent_get()
    {
        $students = new StudentModel;
        $students = $students->get_student();
        $this->response($students, 200);
    }

    public function storeStudent_post()
    {
        $students = new StudentModel;
        $data = [
            'name' =>  $this->input->post('name'),
            'class' => $this->input->post('class'),
            'email' => $this->input->post('email')
        ];
        $result = $students->insert_student($data);
        if($result > 0)
        {
            $this->response([
                'status' => true,
                'message' => 'NEW STUDENT CREATED'
            ], RestController::HTTP_OK); 
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'FAILED TO CREATE NEW STUDENT'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function editStudent_get($id)
    {
        $students = new StudentModel;
        $students = $students->edit_student($id);
        $this->response($students, 200);
    }

    public function updateStudent_put($id)
    {
        $students = new StudentModel;
        $data = [
            'name' =>  $this->put('name'),
            'class' => $this->put('class'),
            'email' => $this->put('email')
        ];
        $result = $students->update_student($id, $data);
        if($result > 0)
        {
            $this->response([
                'status' => true,
                'message' => 'STUDENT UPDATED'
            ], RestController::HTTP_OK); 
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'FAILED TO UPDATE STUDENT'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function deleteStudent_delete($id)
    {
        $students = new StudentModel;
        $result = $students->delete_student($id);
        if($result > 0)
        {
            $this->response([
                'status' => true,
                'message' => 'STUDENT DELETED'
            ], RestController::HTTP_OK); 
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'FAILED TO DELETE STUDENT'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}

?>


Step 4: Use the routes given below to send and receive the data through api using POSTMAN: paste in following path: application/config/routes.php

$route['api/students'] = 'api/ApiStudentController/indexStudent';
$route['api/students/store'] = 'api/ApiStudentController/storeStudent';
$route['api/students/edit/(:any)'] = 'api/ApiStudentController/editStudent/$1';
$route['api/students/update/(:any)'] = 'api/ApiStudentController/updateStudent/$1';
$route['api/students/delete/(:any)'] = 'api/ApiStudentController/deleteStudent/$1';
