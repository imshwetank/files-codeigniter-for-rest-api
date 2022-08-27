<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries\RestController.php';
use chriskacerguis\RestServer\RestController;
class Apidemo extends RestController {


	public function index_post()
	{
		
		echo 'hi i am rest controler';
	}
}
