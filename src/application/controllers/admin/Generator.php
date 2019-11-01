<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 代码生成
 */
class Generator extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation'));
        /*$this->load->library('backend_lib');
        //检查登录
        $this->backend_lib->checkLoginOrJump();
        //检查权限管理的权限
        $this->backend_lib->checkPermissionOrJump(1);*/
        //输出调试结果
        //$this->output->enable_profiler(TRUE);
    }

    /**
     * 首页
     */
    public function index() {
        if ($this->input->method() == "post") {
            //执行表单操作
            $this->form_validation->set_rules('table', '数据表名称', 'required|trim');

            $tableName = $this->input->post('table', TRUE);
            $funName = $this->input->post('funName', TRUE);
            $is_menu = $this->input->post('is_menu', TRUE);

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //查询数据
                $dbInformation = 'information_schema';
                $infTable = 'COLUMNS';
                $dbName = $this->db->database;

                //连接数据库
               // $dbh = new PDO('mysql:host=localhost;dbname=cigenerator','root','root');
               // $dbh = new PDO($this->db->dbdriver . ':host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh = new PDO('mysql:host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh->query("SET NAMES 'UTF8'");

                $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE`,`CHARACTER_MAXIMUM_LENGTH`, `COLUMN_TYPE`, `COLUMN_COMMENT` FROM $infTable WHERE `TABLE_SCHEMA`='$dbName' AND `TABLE_NAME`='$tableName'";

                $sth = $dbh->prepare($sql);
                $sth->execute();
                $result = $sth->fetchAll();

                if ($result) {
                    $message = '提交成功';
                    $success = TRUE;
                    $this->data['result'] = $result;
                    //$this->data['tableName'] = $tableName;
                } else {
                    $message = '表格和字段结果为空.';
                }
            }

            if ($success) {
                //需要生成对应的文件数组
                $files = $this->_getFiles($tableName);

                $this->data['files'] = $files;
                $this->data['tableName'] = $tableName;
                $this->data['is_menu'] = $is_menu;
                $this->data['funName'] = $funName;
                //加载模板
                $this->template->admin_load('admin/generator/next', $this->data);
                //$this->load->view('admin/generator/next', $data);
            } else {
                $this->data['message'] = $message;
                $this->template->admin_load('admin/generator/next', $this->data);
            }
        } else {
            //显示表单
            $this->template->admin_load('admin/generator/next', $this->data);
        }
    }

    /**
     * 准备生成代码
     */
    public function next() {
        if ($this->input->method() == "post") {
            //执行表单操作
            $this->form_validation->set_rules('table', '数据表名称', 'required|trim');
            $this->form_validation->set_rules('file_paths[]', '文件列表', 'required|trim');

            $tableName = $this->input->post('table', TRUE);
            $funName = $this->input->post('funName', TRUE);//功能名称
            $isMenu = $this->input->post('is_menu', TRUE);//是否创建菜单和权限
            $column_desc = $this->input->post('column_desc[]', TRUE);//字段描述数组
            $magic = $this->input->post('magic[]', TRUE);//魔术字符串数组  get_array_index
            $file_paths = $this->input->post('file_paths[]', TRUE);//要生成的文件

            $success = FALSE;
            $message = '';

            if ($this->form_validation->run() == FALSE) {
                //检查表单是否有误
                $message = '表单填写有误';
            } else {
                //查询数据
                $dbInformation = 'information_schema';
                $infTable = 'COLUMNS';
                $dbName = $this->db->database;

                //连接数据库
                //$dbh = new PDO($this->db->dbdriver . ':host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh = new PDO('mysql:host=' . $this->db->hostname . ';dbname=' . $dbInformation, $this->db->username, $this->db->password);
                $dbh->query("SET NAMES 'UTF8'");

                $sql = "SELECT `COLUMN_NAME`, `DATA_TYPE`,`CHARACTER_MAXIMUM_LENGTH`, `COLUMN_TYPE`, `COLUMN_COMMENT` FROM $infTable WHERE `TABLE_SCHEMA`='$dbName' AND `TABLE_NAME`='$tableName'";

                $sth = $dbh->prepare($sql);
                $sth->execute();
                $result = $sth->fetchAll();

                if ($result) {
                    $message = '提交成功';
                    $success = TRUE;
                    $this->data['result'] = $result;
                    $this->data['tableName'] = $tableName;
                } else {
                    $message = '表格和字段结果为空.';
                }
            }

            if ($success) {
                //处理一下备注字段和魔法字段
                $columnDescArray = array();
                $magicArray = array();
                $temp_index = 0;
                foreach ($result as $column) {
                    if (!empty($column_desc[$temp_index])){
                        $columnDescArray[$column['COLUMN_NAME']] = $column_desc[$temp_index];
                    }
                    if (!empty($magic[$temp_index])){
                        $magicArray[$column['COLUMN_NAME']] = $magic[$temp_index];
                    }
                    $temp_index++;
                }

                //需要生成对应的文件数组
                $files = $this->_getFiles($tableName);

                //生成写入model
                if(in_array($files['model'], $file_paths)){
                    $modelStr = $this->_getModelStr($tableName, $result, $magicArray);
                    touch($files['model']);
                    file_put_contents($files['model'], $modelStr);
                }

                //生成controller
                if(in_array($files['controller'], $file_paths)) {
                    $controllerStr = $this->_getControllerStr($tableName, $result, $columnDescArray, $magicArray);
                    touch($files['controller']);
                    file_put_contents($files['controller'], $controllerStr);
                }

                //生成api_controller
                if(in_array($files['api_controller'], $file_paths)) {
                    $apiControllerStr = $this->_getApiControllerStr($tableName, $result, $columnDescArray, $magicArray, $funName);
                    touch($files['api_controller']);
                    file_put_contents($files['api_controller'], $apiControllerStr);
                }

                //生成目录
                $viewIndexDir = APP_DIR . '/views/admin/' . strtolower($tableName);
                if (!is_dir($viewIndexDir)) {
                    mkdir($viewIndexDir, 0777);
                }
                
                //生成views/index
                if(in_array($files['view_index'], $file_paths)) {
                    $viewIndexStr = $this->_getViewIndexStr($tableName, $result, $columnDescArray, $magicArray, $funName);
                    touch($files['view_index']);
                    file_put_contents($files['view_index'], $viewIndexStr);
                }
                
                //生成views/save
                if(in_array($files['view_save'], $file_paths)) {
                    $viewSaveStr = $this->_getViewSaveStr($tableName, $result, $columnDescArray, $magicArray, $funName);
                    touch($files['view_save']);
                    file_put_contents($files['view_save'], $viewSaveStr);
                }

                //生成views/view
                if(in_array($files['view_view'], $file_paths)) {
                    $viewViewStr = $this->_getViewViewStr($tableName, $result, $columnDescArray, $magicArray, $funName);
                    touch($files['view_view']);
                    file_put_contents($files['view_view'], $viewViewStr);
                }


                //生成views/modals/del
                //生成目录
                $viewDelDir = APP_DIR . '/views/admin/' . strtolower($tableName).'/modals';
                if (!is_dir($viewDelDir)) {
                    mkdir($viewDelDir, 0777);
                }
                if(in_array($files['view_del'], $file_paths)) {
                    $viewDelStr = $this->_getViewDelStr($tableName, $result);
                    touch($files['view_del']);
                    file_put_contents($files['view_del'], $viewDelStr);
                }

                //生成菜单和权限
                if($isMenu){
                    $this->_getMenuAndAuth($tableName, $funName);
                }

                //返回
                $this->data['tableName'] = $tableName;
                $this->data['files'] = $files;
                $this->template->admin_load('admin/generator/complete', $this->data);
            } else {
                $this->data['message'] = $message;
                $this->template->admin_load('admin/generator/next', $this->data);
            }
        }
    }


    /**
     * 生成菜单并插入权限
     */
    private function _getMenuAndAuth($tableName, $funName){
        $role_id = 1;//角色id
        $url = 'admin/'.$tableName;
        $this->load->model('Menu_model');
        $this->load->model('Permission_Model');
        $this->load->model('Role_permission_model');

        //先查询
        $menu = $this->Menu_model->getRow(array("url"=>$url));
        $menu_id = 0;
        if(!$menu){
            //插入
            $menu_id = $this->Menu_model->save(array(
                "name" => $funName."管理",
                "url" => $url
            ));
        }else{
            $menu_id = $menu['id'];
        }
        //处理权限
        $auth_array = array("index"=>"列表","save"=>"编辑","del"=>"删除","view"=>"详情");
        foreach ($auth_array as $key=>$value){
            $temp_auth = $this->Permission_Model->getRow(array("url"=>$url."/".$key));
            $auth_id = 0;
            if(!$temp_auth){
                //插入
                $auth_id = $this->Permission_Model->create(array(
                    "name" => $funName.$value,
                    "url" => $url."/".$key,
                    "menu_id" => $menu_id,
                ));
            }else{
                $auth_id = $temp_auth['id'];
            }
            //处理permission
            $temp_per = $this->Role_permission_model->getRow(array("role_id"=>$role_id, "permission_id" => $auth_id));
            if(!$temp_per) {
                //插入
                $this->Role_permission_model->create(array(
                    "role_id" => $role_id,
                    "permission_id" => $auth_id,
                ));
            }
        }

    }

    private function _getFiles($tableName) {
        $files = array(
            'model' => APP_DIR . '/models/' . ucfirst(strtolower($tableName)) . '_model.php',
            'controller' => APP_DIR . '/controllers/admin/' . ucfirst(strtolower($tableName)) . '.php',
            'api_controller' => APP_DIR . '/controllers/api/' . ucfirst(strtolower($tableName)) . '.php',
            'view_index' => APP_DIR . '/views/admin/' . strtolower($tableName) . '/' . 'index.php',
            'view_save' => APP_DIR . '/views/admin/' . strtolower($tableName) . '/' . 'save.php',
            'view_view' => APP_DIR . '/views/admin/' . strtolower($tableName) . '/' . 'view.php',
            'view_del' => APP_DIR . '/views/admin/' . strtolower($tableName) . '/modals/' . 'del.php',
        );
        return $files;
    }

    private function _getArrays($str) {
        $statuss = array();
        if (stripos($str, '$array$') !== FALSE) {
            $commentStr = substr($str, 7);
            if ($commentStr) {
                $arrayT = explode('|', $commentStr);
                if ($arrayT) {
                    foreach ($arrayT as $value) {
                        $arrayV = explode(':', $value);
                        if ($arrayV) {
                            $statuss[$arrayV[0]] = $arrayV[1];
                        }
                    }
                }
            }
        }

        return $statuss;
    }

    private function _getModelStr($tableName, $columns, $magicArray) {
        $tTableName = ucfirst($tableName);
        $str = <<<model
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * $tableName 模型
 */
class {$tTableName}_model extends Base_model {
    private \$_name='$tableName';

    public function __construct() {
        parent::__construct();
        \$this->tableName = \$this->_name;
    }

model;

        //是否存在定义的状态
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<model
   
    public function get{$firstColumnName}(\$key='') {
        \$data = array(
model;
                        foreach ($statuss as $sKey => $sValue) {
                            $str .= $sKey . " => '" . $sValue . "', ";
                        }

                        $str .= <<<model
);

        if (\$key !== '') {
            return \$data[\$key];
        } else {
            return \$data;
        }
    }
model;
                    }
                }
            }
        }


        //结尾
        $str .= <<<model

}

model;


        return $str;
    }

    /**
     * 生成控制器代码
     * @param type $tableName
     * @param type $columns
     * @return type
     */
    private function _getControllerStr($tableName, $columns, $columnDescArray, $magicArray) {
        //类名拼接
        $controllerClassName = ucfirst($tableName);

        $modelName = ucfirst($tableName) . '_model';

        $str = <<<sss
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * $tableName 控制器
 */
class $controllerClassName extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        \$this->load->model('$modelName');

sss;

        //是否存在关联id代码
        if ($magicArray && is_array($magicArray)) {
            foreach ($magicArray as $column) {
                if ($column && stripos($column, '$id$') !== FALSE) {
                    //获取关联id选项的的处理
                    $idStr = substr($column, 4);
                    $idStr = ucfirst($idStr);
                    //生成代码
                    $str .= <<<sss
        \$this->load->model('{$idStr}_model');

sss;
                }
            }
        }

        $str .= <<<sss
        //检查登录
        //\$this->backend_lib->checkLoginOrJump();
                
        //检查权限管理的权限
        //\$this->backend_lib->checkPermissionOrJump(1);
    }
                
    public function index() {
        //\$data = array();
        \$param = array();
        \$inParams = array();
        \$likeParam = array();


sss;

        //是否存在选项代码
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);

                    //关联id
                    $str .= <<<sss
        \$this->data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');

sss;
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$this->data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }

        //处理搜索筛选
        $str .= <<<sss

        //搜索筛选
        \$this->data['search'] = \$this->input->get('search', TRUE);
        if(\$this->data['search']) {


sss;

        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (in_array($column['DATA_TYPE'], array('char', 'varchar', 'text'))) {
                    //普通字符串
                    $str .= <<<sss
            \$this->data['{$column['COLUMN_NAME']}'] = \$this->input->get('{$column['COLUMN_NAME']}', TRUE);
            if(\$this->data['{$column['COLUMN_NAME']}']) {
                \$likeParam['{$column['COLUMN_NAME']}'] = \$this->data['{$column['COLUMN_NAME']}'];
            }


sss;
                } elseif (in_array($column['DATA_TYPE'], array('datetime', 'timestamp'))) {
                    //日期时间
                    $str .= <<<sss
            \$this->data['{$column['COLUMN_NAME']}_start'] = \$this->input->get('{$column['COLUMN_NAME']}_start', TRUE);
            \$this->data['{$column['COLUMN_NAME']}_end'] = \$this->input->get('{$column['COLUMN_NAME']}_end', TRUE);
            if (\$this->data['{$column['COLUMN_NAME']}_start'] && \$this->data['{$column['COLUMN_NAME']}_end']) {
                \$param['{$column['COLUMN_NAME']} >='] = date('Y-m-d', strtotime(\$this->data['{$column['COLUMN_NAME']}_start']));
                \$param['{$column['COLUMN_NAME']} <'] = date('Y-m-d', strtotime(\$this->data['{$column['COLUMN_NAME']}_end']));
            }


sss;
                } else if ($magicArray[$column['COLUMN_NAME']] == '$max$' && in_array($column['DATA_TYPE'], array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'double', 'decimal'))) {
                    //大小于号生成
                    $str .= <<<sss
            \$this->data['{$column['COLUMN_NAME']}_min'] = \$this->input->get('{$column['COLUMN_NAME']}_min', TRUE);
            if (\$this->data['{$column['COLUMN_NAME']}_min'] !== '') {
                \$param['{$column['COLUMN_NAME']} >='] = \$this->data['{$column['COLUMN_NAME']}_min'];
            }
            \$this->data['{$column['COLUMN_NAME']}_max'] = \$this->input->get('{$column['COLUMN_NAME']}_max', TRUE);
            if (\$this->data['{$column['COLUMN_NAME']}_max'] !== '') {
                \$param['{$column['COLUMN_NAME']} <'] = \$this->data['{$column['COLUMN_NAME']}_max'];
            }


sss;
                } else {
                    //普通的数字
                    $str .= <<<sss
            \$this->data['{$column['COLUMN_NAME']}'] = \$this->input->get('{$column['COLUMN_NAME']}', TRUE);
            if(\$this->data['{$column['COLUMN_NAME']}'] !== '') {
                \$param['{$column['COLUMN_NAME']}'] = \$this->data['{$column['COLUMN_NAME']}'];
            }


sss;
                }
            }
        }



        $str .= <<<sss
        }

        //自动获取get参数
        \$urlGet = '';
        \$gets = \$this->input->get();
        if (\$gets) {
            \$i = 0;
            foreach (\$gets as \$getKey => \$get) {
                if (\$i) {
                    \$urlGet .= "&\$getKey=\$get";
                } else {
                    \$urlGet .= "/?\$getKey=\$get";
                }
                \$i++;
            }
        }
                
        //排序
        \$orderBy = \$this->input->get('orderBy', TRUE);
        \$orderBySQL = 'id DESC';
        if (\$orderBy == 'idASC') {
            \$orderBySQL = 'id ASC';
        }
        \$this->data['orderBy'] = \$orderBy;
                
        //分页参数
        \$pageUrl = B_URL.'$tableName/index';  //分页链接
        \$suffix = \$urlGet;   //GET参数

        //\$pageUri = 4;   //URL参数位置
        //\$pagePer = 20;  //每页数量
        //计算分页起始条目
        //\$pageNum = intval(\$this->uri->segment(\$pageUri)) ? intval(\$this->uri->segment(\$pageUri)) : 1;
        //\$startRow = (\$pageNum - 1) * \$pagePer;

        //获取数据
        \$result = \$this->{$modelName}->getResult(\$param, \$this->per_page, \$this->offset, \$orderBySQL, \$inParams, \$likeParam);

        //生成分页链接
        \$total = \$this->{$modelName}->count(\$param, \$inParams, \$likeParam);

        \$this->initPage(\$pageUrl.\$suffix, \$total, \$this->per_page);
        //\$this->backend_lib->createPage(\$pageUrl, \$pageUri, \$pagePer, \$total, \$suffix);  //创建分页链接

        //获取联表结果
        //if (\$result) {
        //    foreach (\$result as \$key => \$value) {

        //    }
        //}

        \$this->data['result'] = \$result;

        //加载模板
        \$this->template->admin_load('admin/$tableName/index',\$this->data); //\$this->data
    }


sss;

        //----------------------- save 方法 ---------------------------
        $str .= <<<sss
    public function save() {
        \$data = array();

sss;
        //处理status
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);

                    //关联id
                    $str .= <<<sss
        \$data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');

sss;
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);
                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }

        $str .= <<<sss

        if (\$this->input->method() == "post") {

sss;

        //处理表单
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (!in_array($column['COLUMN_NAME'], array('update_time', 'create_time'))) {
                    
                }
                //生成代码
                $str .= <<<sss
            \$this->form_validation->set_rules('{$column['COLUMN_NAME']}', '{$column['COLUMN_NAME']}', 'trim');

sss;
            }
        }

        $str .= <<<sss

        \$param = array(

sss;

        //处理输入过滤
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                //初始时间不用输入
                if ($column['COLUMN_NAME'] == 'update_time') {
                    //对updatetime的特殊处理
                    $str .= <<<sss
            'update_time' => date('Y-m-d H:i:s'),

sss;
                } elseif (!in_array($column['DATA_TYPE'], array('timestamp'))) {
                    //生成代码
                    $str .= <<<sss
            '{$column['COLUMN_NAME']}' => \$this->input->post('{$column['COLUMN_NAME']}', TRUE),

sss;
                }
            }
        }

        //后半部分
        $str .= <<<sss

        );
            \$success = FALSE;
            \$message = '';
            \$message_type = 'fail';

            if (\$this->form_validation->run() == FALSE) {
                \$message = '表单填写有误';
                 //加载模板
                \$this->template->admin_load('admin/$tableName/save', \$data);
            } else {
                //保存记录
                \$save = \$this->{$modelName}->save(\$param);

                if (\$save) {
                    \$message = '保存成功';
                    \$success = TRUE;
                    \$message_type = 'success';
                } else {
                    \$message = '保存失败';
                }

                \$this->session->set_flashdata('message_type', \$message_type);
                \$this->session->set_flashdata('message', \$message);
                 //返回列表页面
                \$form_url = \$this->session->userdata('list_page_url');
                if(empty(\$form_url)){
                    \$form_url = "/admin/$tableName/index";
                }
                else{
                    \$this->session->unset_userdata('list_page_url');
                }
                redirect(\$form_url, 'refresh');

            }

            //if (\$success) {
            //    \$this->backend_lib->showMessage(B_URL.'$tableName', \$message);
            //} else {

            //}
        } else {
            //显示记录的表单
            //\$id = intval(\$this->input->get('id'));
            \$id = \$this->uri->segment(4);
            if (\$id) {
                \$data['data'] = \$this->{$modelName}->getRow(array('id' => \$id));
            }
            \$this->template->admin_load('admin/$tableName/save', \$data);
        }
    }


sss;

        //----------------------- manage ------------------------
        $str .= <<<sss
    public function manage() {
        \$data = array();
        \$this->form_validation->set_rules('ids[]', 'Ids', 'required');
        \$this->form_validation->set_rules('manageName', '操作名称', 'required');

        \$manageName = \$this->input->post('manageName', TRUE);
        \$ids = \$this->input->post('ids', TRUE);

        \$success = FALSE;
        \$message = '';

        if (\$this->form_validation->run() == FALSE) {
            \$message = '表单填写有误';
        } else {
            if (\$ids != null) {
                if (\$manageName == 'delete') {
                    //删除记录
                    foreach (\$ids as \$key => \$id) {
                        \$param = array(
                            'id' => \$id,
                        );
                        \$this->{$modelName}->delete(\$param);
                    }
                    \$message = '删除成功';

sss;

        //处理状态
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {

                    //关联id
                    $str .= <<<sss
                } elseif (\$manageName == 'set_{$column['COLUMN_NAME']}') {
                    \$setValue = \$this->input->post('set_{$column['COLUMN_NAME']}', TRUE);
                    if (\$setValue !== '') {
                        foreach (\$ids as \$key => \$id) {
                            \$param = array(
                                'id' => \$id,
                                '{$column['COLUMN_NAME']}' => \$setValue,
                            );
                            \$this->{$modelName}->save(\$param);
                        }
                        \$message = '操作成功';
                    } else {
                        \$message = '设置不能为空.';
                    }


sss;
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
                } elseif (\$manageName == 'set_{$column['COLUMN_NAME']}') {
                    \$setValue = \$this->input->post('set_{$column['COLUMN_NAME']}', TRUE);
                    if (\$setValue !== '') {
                        foreach (\$ids as \$key => \$id) {
                            \$param = array(
                                'id' => \$id,
                                '{$column['COLUMN_NAME']}' => \$setValue,
                            );
                            \$this->{$modelName}->save(\$param);
                        }
                        \$message = '操作成功';
                    } else {
                        \$message = '设置不能为空.';
                    }

sss;
                    }
                }
                
            }
        }

        $str .= <<<sss
                }
            }
        }

        \$this->session->set_flashdata('message_type', 'success');
        \$this->session->set_flashdata('message', \$message);

        //返回列表页面
        \$form_url = \$this->session->userdata('list_page_url');
        if (empty(\$form_url)) {
            \$form_url = "/admin/$tableName";
        } else {
            \$this->session->unset_userdata('list_page_url');
        }
        redirect(\$form_url, 'refresh');

        //\$this->backend_lib->showMessage(B_URL. '$tableName', \$message);
    }


sss;
        //----------------------- delete方法 ------------------------
        $str .= <<<sss
    public function del() {

        \$id = \$this->uri->segment(4);

        if (\$this->input->method() == "post") {
            if (\$this->{$modelName}->delete(\$id)) {
                \$this->session->set_flashdata('message_type', 'success');
                \$this->session->set_flashdata('message', "删除成功！");
            } else {
                \$this->data["message"] = "<div class=\"alert alert-danger alert-dismissable\"><button class=\"close\" data-dismiss=\"alert\">&times;</button>删除时发生错误，请稍后再试！</div>";
            }
        }
        \$this->data['id'] = \$id;
        \$this->load->view('admin/$tableName/modals/del', \$this->data);
    }


sss;

        //----------------------- view方法 ------------------------
        $str .= <<<sss
        //详情
        public function view()
        {
            \$id = \$this->uri->segment(4);

            //获取数据
            \$obj = \$this->{$modelName}->getRow(array("id" => \$id));
            if(empty(\$obj)){
                redirect('admin/$tableName/index', 'refresh');
            }
sss;
        //处理status
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);

                    //关联id
                    $str .= <<<sss
        \$this->data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');
sss;
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);
                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$this->data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }
        $str .= <<<sss

            // 传递数据
            \$this->data['data']  = \$obj;

            //当前列表页面的url
            \$form_url = empty(\$_SERVER['HTTP_REFERER']) ? '' : \$_SERVER['HTTP_REFERER'];
            if(strripos(\$form_url,"admin/$tableName") === FALSE){
                \$form_url = "/admin/$tableName/index";
            }
            \$this->data['form_url'] = \$form_url;
            //加载模板
            \$this->template->admin_load('admin/$tableName/view', \$this->data);
        }
   }

sss;

        return $str;
    }


    /**
     * 生成接口控制器代码
     * @param type $tableName
     * @param type $columns
     * @return type
     */
    private function _getApiControllerStr($tableName, $columns, $columnDescArray, $magicArray, $funName) {
        //类名拼接
        $controllerClassName = ucfirst($tableName);

        $modelName = ucfirst($tableName) . '_model';

        $tTableName = ucfirst(cg_get_hump_str($tableName));

        $str = <<<sss
<?php
defined('BASEPATH') or exit ('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

/**
 * $tableName API控制器
 */
class $controllerClassName extends REST_Controller {

    public function __construct() {
        parent::__construct();

        \$this->load->model('$modelName');
        \$this->load->library(array('form_validation'));
        
sss;

        //是否存在关联id代码
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {
                    //获取关联id选项的的处理
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);
                    $idStr = ucfirst( $idStr);
                    //生成代码
                    $str .= <<<sss
                    
        \$this->load->model('{$idStr}_model');

sss;
                }
            }
        }


        $str .= <<<sss
    }

    /**
     * @SWG\Get(path="/$tableName/list",
     *   consumes={"multipart/form-data"},
     *   tags={"$tTableName"},
     *   summary="查询{$funName}列表",
     *   description="查询{$funName}列表",
     *   operationId="{$tableName}List",
     *     @SWG\Parameter(
     *     in="query",
     *     name="token",
     *     description="用户登录token",
     *     required=false,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="cur_page",
     *     description="当前页",
     *     required=true,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="per_page",
     *     description="每页数量 [默认10条]",
     *     required=false,
     *     type="integer"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function list_get() {

sss;

        //是否存在选项代码
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE) {
                    //关联id处理
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);

                    //关联id
                    $str .= <<<sss
        \$data['{$idStr}s'] = \$this->{$idStr}_model->getResult(array(), '', '', 'id DESC');

sss;
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    $firstColumnName = ucfirst($column['COLUMN_NAME']);

                    //生成代码
                    if ($statuss) {
                        $str .= <<<sss
        \$data['{$column['COLUMN_NAME']}s'] = \$this->{$modelName}->get{$firstColumnName}();

sss;
                    }
                }
            }
        }

        $str .= <<<sss
        //if (!\$this->user_id) {
        //    return \$this->json(null, REST_Controller::NOT_LOGIN, \$this->lang->line('text_resp_unlogin'));
        //}
        \$total = \$this->{$modelName}->count(array());
        \$result = \$this->{$modelName}->getResult(array(), \$this->per_page, \$this->offset);
        if (\$result) {
            //foreach (\$result as \${$tableName}) {
            //}
            return \$this->json(array("list" => \$result, "total" => \$total), \$this::SUCCESS, \$message = \$this->lang->line('text_resp_success'));
        } else {
           return \$this->json(null, \$this::NO_DATA, \$message = \$this->lang->line('text_resp_no_data'));
        }
    }


sss;

        //----------------------- add 方法 ---------------------------
        $str .= <<<sss
    /**
     * @SWG\Post(path="/$tableName/add",
     *   consumes={"multipart/form-data"},
     *   tags={"$tTableName"},
     *   summary="添加$funName",
     *   description="添加$funName",
     *   operationId="add{$tTableName}",
sss;

        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] != 'id' && !in_array($column['COLUMN_NAME'], array('update_time', 'create_time'))) {
                    $str .= <<<sss

     *   @SWG\Parameter(
     *     in="formData",
     *     name="{$column['COLUMN_NAME']}",
     *     description="{$columnDescArray[$column['COLUMN_NAME']]}",
     *     required=false,
     *     type="string"
     *   ),
sss;
                }
            }
        }

        $str.= <<<sss
     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function add_post(){

sss;

        $str .= <<<sss
        if (!\$this->user_id) {
            return \$this->json(null, REST_Controller::NOT_LOGIN, \$this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        //\$this->form_validation->set_rules('remarks', '备注信息', 'max_length[50]');
        //\$this->form_validation->set_rules('amount', '总金额', 'numeric|greater_than_equal_to[0]');
        //\$this->form_validation->set_rules('deliver_amount', '运费', 'numeric|greater_than_equal_to[0]');
        //\$this->form_validation->set_rules('type', '商品类型', 'required|in_list[1,2]');
        //\$this->form_validation->set_rules('address_id', '地址', 'required|integer');
        
        
sss;

        //处理表单
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (!in_array($column['COLUMN_NAME'], array('update_time', 'create_time','id'))) {
                //生成代码
                $str .= <<<sss
            \$this->form_validation->set_rules('{$column['COLUMN_NAME']}', '{$column['COLUMN_NAME']}', 'trim');
            
sss;
                }
            }
        }

        $str .= <<<sss
        \$this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if (\$this->form_validation->run() == false) {
            // 传递错误信息
            \$message = (validation_errors() ? validation_errors() : \$this->session->flashdata('message'));
            return \$this->json(null, \$this::SYS_ERROR, format_message(\$message));
        }
        \$param = array(
sss;

        //处理输入过滤
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                //初始时间不用输入
                if ($column['COLUMN_NAME'] == 'update_time') {
                    //对updatetime的特殊处理
                    $str .= <<<sss
            'update_time' => date('Y-m-d H:i:s'),

sss;
                } elseif (!in_array($column['DATA_TYPE'], array('timestamp'))) {
                    //生成代码
                    $str .= <<<sss
            '{$column['COLUMN_NAME']}' => \$this->input->post('{$column['COLUMN_NAME']}', TRUE),

sss;
                }
            }
        }

        //后半部分
        $str .= <<<sss

        );
        \$id = \$this->{$modelName}->save(\$param);
        if (\$id) {
            return \$this->json(array("id" => \$id), \$this::SUCCESS, \$message = \$this->lang->line('text_resp_success'));
        } else {
            return \$this->json(null, \$this::SYS_ERROR, \$this->lang->line('text_resp_fail'));
        }
    }


sss;

        //----------------------- edit ------------------------
        $str .= <<<sss
    /**
     * @SWG\Post(path="/$tableName/edit",
     *   consumes={"multipart/form-data"},
     *   tags={"$tTableName"},
     *   summary="编辑$funName",
     *   description="编辑$funName",
     *   operationId="edit{$tTableName}",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
sss;

        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] != 'id' && !in_array($column['COLUMN_NAME'], array('update_time', 'create_time'))) {
                    $str .= <<<sss

     *   @SWG\Parameter(
     *     in="formData",
     *     name="{$column['COLUMN_NAME']}",
     *     description="{$columnDescArray[$column['COLUMN_NAME']]}",
     *     required=false,
     *     type="string"
     *   ),

sss;
                }
            }
        }

        $str.= <<<sss
     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function edit_post(){

sss;

        $str .= <<<sss
        if (!\$this->user_id) {
            return \$this->json(null, REST_Controller::NOT_LOGIN, \$this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        //\$this->form_validation->set_rules('remarks', '备注信息', 'max_length[50]');
        //\$this->form_validation->set_rules('amount', '总金额', 'numeric|greater_than_equal_to[0]');
        //\$this->form_validation->set_rules('deliver_amount', '运费', 'numeric|greater_than_equal_to[0]');
        //\$this->form_validation->set_rules('type', '商品类型', 'required|in_list[1,2]');
        //\$this->form_validation->set_rules('address_id', '地址', 'required|integer');
        \$this->form_validation->set_rules('id', 'ID', 'required|integer');
        
sss;

        //处理表单
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if (!in_array($column['COLUMN_NAME'], array('update_time', 'create_time','id'))) {
                    //生成代码
                    $str .= <<<sss
            \$this->form_validation->set_rules('{$column['COLUMN_NAME']}', '{$column['COLUMN_NAME']}', 'trim');
            
sss;
                }
            }
        }

        $str .= <<<sss
        \$this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if (\$this->form_validation->run() == false) {
            // 传递错误信息
            \$message = (validation_errors() ? validation_errors() : \$this->session->flashdata('message'));
            return \$this->json(null, \$this::SYS_ERROR, format_message(\$message));
        }

        //\$id = intval(\$this->input->post('id'));
        \$param = array(
        
sss;

        //处理输入过滤
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                //初始时间不用输入
                if ($column['COLUMN_NAME'] == 'update_time') {
                    //对updatetime的特殊处理
                    $str .= <<<sss
            'update_time' => date('Y-m-d H:i:s'),

sss;
                } elseif (!in_array($column['DATA_TYPE'], array('timestamp'))) {
                    //生成代码
                    $str .= <<<sss
            '{$column['COLUMN_NAME']}' => \$this->input->post('{$column['COLUMN_NAME']}', TRUE),

sss;
                }
            }
        }

        //后半部分
        $str .= <<<sss

        );
        \$id = intval(\$this->input->post('id'));
        \$result = \$this->{$modelName}->save(\$param);
        if (\$result) {
            return \$this->json(array("id" => \$id), \$this::SUCCESS, \$message = \$this->lang->line('text_resp_success'));
        } else {
            return \$this->json(null, \$this::SYS_ERROR, \$this->lang->line('text_resp_fail'));
        }
    }


sss;

        //----------------------- remove方法 ------------------------
        $str .= <<<sss
    /**
     * @SWG\Post(path="/$tableName/remove",
     *   consumes={"multipart/form-data"},
     *   tags={"{$tTableName}"},
     *   summary="删除{$funName}",
     *   description="删除{$funName}",
     *   operationId="remove{$tTableName}",
     *   @SWG\Parameter(
     *     in="formData",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     name="token",
     *     description="用户登录token",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="formData",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function remove_post(){
        if (!\$this->user_id) {
            return \$this->json(null, REST_Controller::NOT_LOGIN, \$this->lang->line('text_resp_unlogin'));
        }

        //参数校验
        \$this->form_validation->set_rules('id', 'ID', 'required|integer');

        \$this->form_validation->set_error_delimiters('', '');//去除p标签和换行
        if (\$this->form_validation->run() == false) {
            // 传递错误信息
            \$message = (validation_errors() ? validation_errors() : \$this->session->flashdata('message'));
            return \$this->json(null, \$this::SYS_ERROR, format_message(\$message));
        }
        \$id = intval(\$this->input->post('id'));

        if (\$this->{$modelName}->delete(\$id)) {
            return \$this->json(array("id" => \$id), \$this::SUCCESS, \$message = \$this->lang->line('text_resp_success'));
        } else {
            return \$this->json(null, \$this::SYS_ERROR, \$this->lang->line('text_resp_fail'));
        }
    }


sss;

        //----------------------- find方法 ------------------------
        $str .= <<<sss
    /**
     * @SWG\Get(path="/$tableName/find",
     *   consumes={"multipart/form-data"},
     *   tags={"$tTableName"},
     *   summary="查询{$funName}",
     *   description="查询{$funName}",
     *   operationId="find{$tTableName}",
     *     @SWG\Parameter(
     *     in="query",
     *     name="token",
     *     description="用户登录token",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="id",
     *     description="id",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     in="query",
     *     name="lang",
     *     description="语言[zh-中文，en-英文，it-意大利语，fr-法语，es-西班牙语，de-德语，为空默认英文]",
     *     required=false,
     *     type="string"
     *   ),
     *   produces={"application/json"},
     *   @SWG\Response(response="200", description="成功")
     * )
     */
    public function find_get(){
        //if (!\$this->user_id) {
        //    return \$this->json(null, REST_Controller::NOT_LOGIN, \$this->lang->line('text_resp_unlogin'));
        //}
        \$id = intval(\$this->input->get('id'));
        //获取数据
        \$data = \$this->{$modelName}->find(\$id);
        if (\$data) {
            return \$this->json(\$data, \$this::SUCCESS, \$message = \$this->lang->line('text_resp_success'));
        } else {
            return \$this->json(null, \$this::NO_DATA, \$message = \$this->lang->line('text_resp_no_data'));
        }
    }
}

sss;

        return $str;
    }


    //生成详情页面内容
    private function _getViewIndexStr($tableName, $columns, $columnDescArray, $magicArray, $funName) {
        //驼峰命名
        $tTableName = cg_get_hump_str($tableName);

                
        $str = <<<sss
<link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
<?php \$page_title = '$funName' ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo \$page_title ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li><a href="/admin/$tableName/index"><?php echo \$page_title ?></a></li>
      <li class="active">列表</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"><a href="/admin/$tableName/save" class="btn btn-block btn-primary btn-flat"><i
                  class="fa fa-plus"></i> 添加</a></h3>

            <div class="box-tools">
              <form action="/admin/$tableName/index" method="get">
                <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="keyword" class="form-control pull-right" placeholder="搜索"
                         value="<?= \$keyword ?>">
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <table class="table table-condensed table-hover">
              <thead>
              <tr>

sss;

        

        //表格头部
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == 'id') {
                    $str .= <<<sss
                            <th><b>ID</b></th>

sss;
                } else {
                    $str .= <<<sss
                            <th>{$columnDescArray[$column['COLUMN_NAME']]}</th>

sss;
                }
            }
        }
        
        $str .= <<<sss
                            <th width="250">操作</th>
              </tr>
              </thead>
              <tbody>
                        <?php if (\$result != null) : ?>
                            <?php foreach (\$result as \$value) : ?>
                                <tr>

sss;
        
        //表格中部
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {
                if ($column['COLUMN_NAME'] == 'id') {
                    $str .= <<<sss
                                    <td><input type="checkbox" name="ids[]" class="ids" value="<?php echo \$value['id']; ?>" /> <?php echo \$value['id']; ?></td>

sss;
                } elseif($magicArray[$column['COLUMN_NAME']] &&  stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //直接代入数组
                    $str .= <<<sss
                                    <td><?php echo \${$column['COLUMN_NAME']}s[\$value['{$column['COLUMN_NAME']}']]; ?></td>

sss;
                } else {
                    $str .= <<<sss
                                    <td><?php echo \$value['{$column['COLUMN_NAME']}']; ?></td>

sss;
                }
            }
        }
        
        $str .= <<<sss
                                    <td>
                                        <button data-toggle="modal" data-target="#boxModal"
                                                onclick="loadModal('/admin/$tableName/del/<?php echo \$value['id']; ?>')"
                                                class="btn btn-danger btn-sm pull-right"><i class="fa fa-remove"></i> 删除
                                        </button>
                                        <a href="/admin/$tableName/save/<?php echo \$value['id']; ?>" class="btn btn-primary btn-sm pull-right"
                                           style="margin-right: 5px;"><i class="fa fa-edit"></i> 编辑</a>
                                         <a href="/admin/$tableName/view/<?php echo \$value['id']; ?>"
                                           class="btn btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-eye"></i> 详情</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>

sss;
        
        //结尾
        $str .= <<<model
                     <?php if (empty(\$result)) { ?>
                                <tr>
                                    <td colspan="6" class="no-data">没有数据</td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">

                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                    <!-- 显示<?/*= \$administrators_show_begin */?>---><?/*= \$administrators_show_end */?>
                                    <!-- 条，-->共<?= \$total_rows ?>条
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <ul class="pagination pagination no-margin pull-right">
                                    <?php echo \$this->pagination->create_links(); ?>
                                </ul>
                            </div>
                        </div><!-- /.row -->

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

model;


        return $str;
    
    }
    
    private function _getViewSaveStr($tableName, $columns, $columnDescArray, $magicArray, $funName) {
        //驼峰命名
        $tTableName = cg_get_hump_str($tableName);
        
        $str = <<<model
<?php \$page_title = (\$data['id']?'编辑':'新增').' $funName' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo \$page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/$tableName/index">$funName 列表</a></li>
            <li class="active"><?php echo \$page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo \$page_title; ?></h3>
                        <a href="/admin/$tableName/index" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <?php if (!empty(\$message)) { ?>
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <?php echo\$message; ?>
                                    </div>
                                <?php } ?>

                                <form action="/admin/$tableName/save" class="form-horizontal" id="createForm" method="post"
                                      accept-charset="utf-8">
                                    <input type="hidden" name="id" value="<?= \$data['id'] ?>"/>

model;

        //save条件
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {

                if($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE){
                    //关联id
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);
                    $str .= <<<sss
                     <div class="form-group">
                        <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                        <div class="col-sm-3">
                            <select name="{$column['COLUMN_NAME']}" id="{$column['COLUMN_NAME']}"  class="form-control">
                                <option value="">请选择</option>
                                <?php if (\${$idStr}s != null) : ?>
                                    <?php foreach (\${$idStr}s as \$value) : ?>
                                        <option value="<?php echo \$value['id']; ?>" <?php if (\$data['{$column['COLUMN_NAME']}'] === (string)\$value['id'] || set_value('{$column['COLUMN_NAME']}') === (string)\$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo \$value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                        </div>
                     </div>

sss;
                    
                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    if ($statuss) {
                        $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <select name="{$column['COLUMN_NAME']}" id="{$column['COLUMN_NAME']}"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if (\${$column['COLUMN_NAME']}s != null) : ?>
                                        <?php foreach (\${$column['COLUMN_NAME']}s as \$key=>\$value) : ?>
                                            <option value="<?php echo \$key; ?>" <?php if (\$data['{$column['COLUMN_NAME']}'] === (string)\$key || set_value('{$column['COLUMN_NAME']}') === (string)\$key) : ?>selected="selected"<?php endif; ?>><?php echo \$value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                            </div>
                        </div>


sss;
                    }
                
                } else if (in_array($column['DATA_TYPE'], array('text'))) {
                    //文本框
                    $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <textarea type="text" name="{$column['COLUMN_NAME']}" id="{$column['COLUMN_NAME']}" class="form-control"><?php echo \$data['{$column['COLUMN_NAME']}'] ?></textarea>
                            </div>
                        </div>

sss;
                } else if (in_array($column['DATA_TYPE'], array('date','datetime','timestamp'))) {
                    //文本框
                    $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" data-msg="请填写{$column['COLUMN_NAME']}" name="{$column['COLUMN_NAME']}" class="form-control pull-right" id="{$column['COLUMN_NAME']}" value="<?php echo \$data['{$column['COLUMN_NAME']}'] ?>" />
                                </div>
                            </div>
                        </div>

sss;
                }else if(!in_array($column['COLUMN_NAME'], array('id', 'create_time', 'update_time'))) {
                    //普通
                    $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <input class="form-control" id="{$column['COLUMN_NAME']}" name="{$column['COLUMN_NAME']}" value="<?php echo \$data['{$column['COLUMN_NAME']}'] ?>"
                                       data-msg="请填写{$column['COLUMN_NAME']}"
                                       required minlength="1" data-msg-minlength="请至少输入1个以上的字符"
                                 />
                            </div>
                        </div>

sss;
                }
            }
        }
        

        //结尾
        $str .= <<<model
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-flat"
                                            style="margin-right: 5px;">提交
                                    </button>
                                    <a href="/admin/$tableName/index" class="btn btn-default btn-flat">取消</a>
                                </div>
                            </div>
                     </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="/assets/plugins/pwstrength/pwstrength.min.js"></script>
<script src="/assets/plugins/validate/jquery.validate.min.js"></script>

<script>
    $(function () {
        $("#createForm").validate();

model;
        foreach ($columns as $column) {
            if (in_array($column['DATA_TYPE'], array('date', 'datetime', 'timestamp'))) {
                //普通
                $str .= <<<sss
        $('#{$column['COLUMN_NAME']}').datepicker({
            language: 'zh-CN',//选择语言
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

sss;
            }
        }

        $str .= <<<model
        /*$(".prov").change(function () {
            id = $(".prov").val();
            $.post("/admin/cities/ajaxGetArea/" + id, {"test": null}, function (data) {
            });
        });*/
    });
</script>

model;

        return $str;
    }




    private function _getViewViewStr($tableName, $columns, $columnDescArray, $magicArray, $funName) {
        //驼峰命名
        $tTableName = cg_get_hump_str($tableName);

        $str = <<<model


<?php \$page_title = '$funName 详情' ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo \$page_title; ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin/"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="/admin/$tableName/index">$funName 列表</a></li>
            <li class="active"><?php echo \$page_title; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-info"></i> <?php echo \$page_title; ?></h3>
                        <a href="/admin/$tableName/save" class="pull-right">返回</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="form-horizontal" id="createForm" method="post">
                                    <input type="hidden" name="id" value="<?= \$data->id ?>"/>

model;

        //save条件
        if ($columns && is_array($columns)) {
            foreach ($columns as $column) {

                if($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$id$') !== FALSE){
                    //关联id
                    $idStr = substr($magicArray[$column['COLUMN_NAME']], 4);
                    $str .= <<<sss
                     <div class="form-group">
                        <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                        <div class="col-sm-3">
                            <select readonly name="{$column['COLUMN_NAME']}" id="{$column['COLUMN_NAME']}"  class="form-control">
                                <option value="">请选择</option>
                                <?php if (\${$idStr}s != null) : ?>
                                    <?php foreach (\${$idStr}s as \$value) : ?>
                                        <option  value="<?php echo \$value['id']; ?>" <?php if (\$data['{$column['COLUMN_NAME']}'] === (string)\$value['id'] || set_value('{$column['COLUMN_NAME']}') === (string)\$value['id']) : ?>selected="selected"<?php endif; ?>><?php echo \$value['id']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                        </div>
                     </div>

sss;

                } elseif ($magicArray[$column['COLUMN_NAME']] && stripos($magicArray[$column['COLUMN_NAME']], '$array$') !== FALSE) {
                    //获取状态选项的的处理
                    $statuss = $this->_getArrays($magicArray[$column['COLUMN_NAME']]);

                    if ($statuss) {
                        $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <select readonly name="{$column['COLUMN_NAME']}" id="{$column['COLUMN_NAME']}"  class="form-control">
                                    <option value="">请选择</option>
                                    <?php if (\${$column['COLUMN_NAME']}s != null) : ?>
                                        <?php foreach (\${$column['COLUMN_NAME']}s as \$key=>\$value) : ?>
                                            <option value="<?php echo \$key; ?>" <?php if (\$data['{$column['COLUMN_NAME']}'] === (string)\$key || set_value('{$column['COLUMN_NAME']}') === (string)\$key) : ?>selected="selected"<?php endif; ?>><?php echo \$value; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php echo form_error('{$column['COLUMN_NAME']}'); ?>
                            </div>
                        </div>


sss;
                    }

                } else if (in_array($column['DATA_TYPE'], array('text'))) {
                    //文本框
                    $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <?php echo \$data['{$column['COLUMN_NAME']}'] ?>
                            </div>
                        </div>

sss;
                } else if(!in_array($column['COLUMN_NAME'], array('id', 'create_time', 'update_time'))) {
                    //普通
                    $str .= <<<sss
                        <div class="form-group">
                            <label for="{$column['COLUMN_NAME']}" class="col-sm-2 control-label">{$columnDescArray[$column['COLUMN_NAME']]}</label>
                            <div class="col-sm-3">
                                <?php echo \$data['{$column['COLUMN_NAME']}'] ?>
                            </div>
                        </div>

sss;
                }
            }
        }


        //结尾
        $str .= <<<model

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(function () {

    });
</script>

model;

        return $str;
    }

    //获取删除页面的内容
    private function _getViewDelStr($tableName, $columns) {
        //驼峰命名
        //$tTableName = cg_get_hump_str($tableName);
        $str = <<<model
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title modal-red">删除数据</h4>
</div>
<form method="post" action="/admin/$tableName/del/<?=\$id?>" class="ajaxForm">
	<div class="modal-body">
		<div class="boxMessage">
		<?php
		if(isset(\$message))
			echo \$message;
		?>
		</div>
		<input type="hidden" name="id" value="<?=\$id?>">
		<label class="modal-red">确定要删除这条数据吗？</label>
	</div>

	<div class="modal-footer">
		<input type="submit" class="btn btn-danger submitButton" value="确定" onclick="return submitAjax(1)" <?=isset(\$message)?"disabled":""?>>
		<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
	</div>
</form>

model;

        return $str;
    }

}