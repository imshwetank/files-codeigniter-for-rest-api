<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries\RestController.php';
use imshwetank\RestServer\RestController;
class Apidemo extends RestController {


	public function index_put()
	{
		
		echo 'hi i am rest controler';
	}
}
