<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="ci-framework.kuai.ma",
 *     basePath="/api",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="API接口",
 *         description="API接口文档，开发的时候请遵守swagger接口规范，不清楚的请参照示例和文档 [CodeIgniter Rest Server](https://github.com/chriskacerguis/codeigniter-restserver) 和 [swagger-php](https://github.com/zircote/swagger-php)"
 *     )
 * )
 */
class Home extends CI_Controller {
	
	public function index()
	{
		echo 'api';
	}
}
