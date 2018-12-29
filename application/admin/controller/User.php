<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/11/1
 * Time: 16:01
 */
namespace app\admin\controller;

use app\common\model\Error;
use app\common\controller\Common;
use app\common\model\Users;
use app\common\model\Usermenuinfo;
use app\common\model\Menuinfo;
use think\Controller;

/**
 *
 * Class User
 * @package app\admin\controller
 * @explain 用户控制器
 */
Class User extends Common{

    /**
     * 继承父类自动加载
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->userId = session('userInfo')['id'];
    }


    /**
     *
     */
    public function add()
    {
        //查所有的菜单
        $commonCrontroller = new Common();
        $result = Menuinfo::getMenuList();
        $menulist = $commonCrontroller->arrayPidProcess($result);
        $roleList = \app\common\model\Role::getList(50,0,'');
        if($roleList['code'] == 0){
            $this->assign('roleList',$roleList['data']);
        }else{
            $this->assign('roleList',array());
        }

        $this->assign('menulist',$menulist);
        return  $this->fetch('user/add');
    }

    /**
     * @explain添加用户方法
     * @explain接收
     */
    public function addAction()
    {
        $returnArray = [];
        $data = [];
        if(!empty($_POST['data'])){
           $acceptData =  $_POST['data'];
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => array()
            );
        }
        if($acceptData['name']){
            $data['name'] = $acceptData['name'];
            $result = Users::getOne($data);
            if($result['code'] == 0){
                $returnArray = array(
                    'code' => 13008,
                    'msg' => Error::ERRORCODE[13008],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 13006,
                'msg' => Error::ERRORCODE[13006],
                'data' => array()
            );
        }

        if($acceptData['passwd']){
            $data['password'] = $acceptData['passwd'];
        }else{
            $returnArray = array(
                'code' => 13005,
                'msg' => Error::ERRORCODE[13005],
                'data' => array()
            );
        }
        $data['type'] = $acceptData['type'];
        $data['create_at'] = time();
        if(empty($returnArray)){
                $addUserResult = Users::addUserAction($data);
                if($addUserResult['code'] == 0){
                    if(!empty($_POST['sonCheck'])){
                        $userId = Users::getLastInsID();
                        foreach ($_POST['sonCheck'] as $value){
                            $menuIfo[] = [
                                'user_id' => $userId,
                                'menu_id' => $value,
                            ];
                        }
                        //添加用户菜单
                        Usermenuinfo::addAction($menuIfo);
                    }
                    $returnArray = $addUserResult;
                }
        }
        return $returnArray;
    }

    public function edit()
    {

        if(!empty($_GET['id'])){
            $result = Userdata::getOne(array('id'=>$_GET['id']));
            if($result['code'] == 0){
                //查询所有的菜单
                $commonCrontroller = new Common();
                $resultMenu = Menuinfo::getMenuList();
                $menulist = $commonCrontroller->arrayPidProcess($resultMenu);
                $this->assign('menulist',$menulist);
                //查询账号的菜单
                $userMenu = Usermenuinfo::getUsermenuinfoList($_GET['id']);
                $userMenu = \GuzzleHttp\json_encode($userMenu);
                $this->assign('userMenu',$userMenu);
                $this->assign('userInfo',$result['data']);
                return $this->view->fetch('user/edit');
            }else{
               echo " <script>window.history.back();location.reload();</script>";
            }
        }else{
            echo "<script>window.history.back();location.reload();</script>";
        }
    }

    /**
     * @explain修改用户动作
     * @explain接收
     */


    public function editAction()
    {
        $returnArray = [];
        if(!empty($_POST['data']) && is_array($_POST['data']) && !empty($_POST['data']['id'])){

            $where = array();
            $where['id'] = $_POST['data']['id'];
            $editResult = Userdata::update($_POST['data'],$where)->toArray();
            if($editResult){

                if(!empty($_POST['sonCheck'])){
                    foreach ($_POST['sonCheck'] as $value){
                        $menuIfo[] = [
                            'user_id' => $where['id'],
                            'menu_id' => $value,
                        ];
                    }
                    //添加用户菜单
                    Usermenuinfo::del(array('user_id'=>$where['id']));
                    Usermenuinfo::addAction($menuIfo);
                }
                $returnArray = $returnArray = [
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => $editResult
                ];
            }else{
                $returnArray = array(
                    'code' => 13002,
                    'msg' => Error::ERRORCODE[13002],
                    'data' => []
                );
            }
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => []
            );
        }
        return $returnArray;
    }

    /**
     * @return array
     */
    public function delAction()
    {
        $returnArray = [];
        if(!empty($_POST['id'])){
            $delResult = Userdata::destroy(['id'=> $_POST['id']]);
            Usermenuinfo::del(array('user_id'=>$_POST['id']));
            if($delResult){
                $returnArray = array(
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => $delResult
                );
            }else{
                $returnArray = array(
                    'code' => 13003,
                    'msg' => Error::ERRORCODE[13003],
                    'data' => []
                );
            }
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => []
            );
        }
        return $returnArray;
    }

    /**
     * @return array获取账号列表
     */
    public function getList()
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
        $result =  Users::getManyList($offset,$limit);
//        var_dump($result);
        return $result;
    }

    /**
     * @return int
     * 验证账号是否存在
     */
    public function verifyName()
    {
        if($_POST['name']){
            $where['name'] = $_POST['name'];
            $result = Users::getOne($where);
            if($result['code'] == 0){
                return 1;
            }
        }
    }
}

//        13001 => '查不到该用信息',
//        13002 => '修改用户信息无效',
//        13003 => '删除用户失败',
//        13004 => '用户ID不能为空',
//        13005 => '用户密码不能为空',
//        13006 => '用户账号不能为空',