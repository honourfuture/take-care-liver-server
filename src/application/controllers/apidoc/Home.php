<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH."../docs/php/vendor/autoload.php");

class Home extends CI_Controller {
	
	public function index()
	{
		$api_dir = APPPATH."controllers/api";

		$swagger = \Swagger\scan($api_dir);
		header('Content-Type: application/json');
		echo $swagger;
	}
}
