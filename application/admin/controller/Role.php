<?php
namespace app\admin\controller;

use app\common\model\Error;
use app\common\model\Role as RoleModel;
use think\Controller;
use think\Request;

/**
 * Class Warning
 * @package app\admin\controller
 * 预警
 */
Class Role extends Common{

    /**
     * @return Request
     */
    public function index()
    {
        return $this->fetch('index');
    }

    /*
     *获取角色列表
     */

    public function getRolelist()
    {
        if(isset($_GET["limit"])){
            $limit = $_GET["limit"];
        }else{
            $limit = 15;
        }
        if(isset($_GET["page"])){
            $offset = ($_GET["page"] -1) * $limit;
        }else{
            $offset = 0;
        }

        if(isset($_GET["name"])){
            $name = $_GET["name"];
        }else{
            $name = '';
        }
        $result = RoleModel::getList($limit,$offset,$name);
        if($result) {
            return $result;
        }
    }


    /**渲染添加页面
     * @return mixed
     */
    public function add()
    {
        return $this->fetch('add');
    }


    /**添加动作action
     * @return array
     * @param data 数组
     */
    public function addAction()
    {
        if(!empty($_POST['data']) && is_array($_POST['data'])){
            $_POST['data']['createtime'] = time();
            $result = RoleModel::addAction($_POST['data']);
            if($result){
                return $result;
            }
        }
    }




    public function edit()
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(!empty($_GET['id'])  ){
            $result = RoleModel::getListONe(array('id' => $_GET['id']));
            if($result['code'] == 0){
                $this->assign('role',$result['data'][0]);
                return $this->fetch('edit');
            }
        }else{
            return $errorModel::ERRORCODE[10005];
        }

    }



    /**
     * @return array修改action
     * data要修改的数组
     */
    public function editAction()
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(!empty($_POST['data']) && is_array($_POST['data']) && !empty($_POST['data']['id'])){
            $where['id'] = $_POST['data']['id'];
            $result = RoleModel::upDateRecode($where,$_POST['data']);
            if($result){
                return $result;
            }
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => $errorModel::ERRORCODE[10005],
                'data' => array()
            );
            return $returnArray;
        }
    }

    /**
     * 删除操作
     */
    public function delAction()
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(!empty($_POST['id'])){
            $result = RoleModel::delRecode(array('id'=>$_POST['id']));
            if($result){
                return $result;
            }
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => $errorModel::ERRORCODE[10005],
                'data' => array()
            );

            return $returnArray;
        }
    }
}
