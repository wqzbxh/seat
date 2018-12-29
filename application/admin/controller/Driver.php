<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/10
 * Time: 10:38
 */
namespace app\admin\controller;

use app\common\model\Drivers;
use app\common\model\Error;
use app\common\model\Ipblacklist;
use app\common\model\Ipwhitelist;
use app\common\model\Operationlog;
use app\common\model\Productdata;
use app\common\model\Serverchildruledata;
use app\common\model\Serverproductdata;
use think\Cache;
use think\Config;
use think\Controller;
use app\common\model\Serverdata;
use think\Request;

Class Driver extends Common{

    /**
     * 继承父类自动加载
     */
    public function _initialize()
    {
        parent::_initialize();
    }



    /**
     * @return mixed
     * 服务器管理首页
     */
    public function index()
    {
        return $this->fetch('index');
    }

    /**
     * 服务器管理添加页面
     */
    public function add()
    {
        $roleList = \app\common\model\Role::getList(50,0,'');
        if($roleList['code'] == 0){
            $this->assign('rolelist',$roleList['data']);
        }

        return $this->fetch('add');
    }


    /**
     *服务器添加动作
     */
    public function addAction()
    {
        $data = array();
        if(!empty($_POST['data'])){
            $data =array_merge($_POST['data'],$data) ;
            $data['create_at'] = date("Y-m-d H:i:s");
            $driversDataModel = new \app\common\model\Drivers();
            $result = $driversDataModel->addDriver($data);
            if($result['code'] == 0){
                $id = $driversDataModel->getLastInsID();
                if(is_array($_POST['price']) && !empty($_POST['price'])){
                    $driverPrice = array_filter($_POST['price']);
                    $data = array();
                    $i = 0;
                    foreach ($driverPrice as $key => $value){
                        $data[$i]['price'] = $value;
                        $data[$i]['drive_id'] = $id;
                        $data[$i]['user_type'] = $key;
                        $i++;
                    }
                    \app\common\model\Price::addListPrice($data);
                }
            }
        }else{
            $errorModel = new \app\common\model\Error();
            $result = array(
                'code' => 40001,
                'msg' => $errorModel::ERRORCODE[40001],
                'data' => array()
            );
        }
        return $result;
    }






    /**
     * 获取子规则列表
     * @param page 页码
     * @param limit 限制几个
     * @param serveruserid 用户id
     */

    public function getDriverlist()
    {
        $driverDataModel = new \app\common\model\Drivers();

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

        if(isset($_GET["drive_name"])){
            $drive_name = $_GET["drive_name"];
        }else{
            $drive_name = '';
        }

        $serveruserid = $this->userId;
        $result = $driverDataModel->getList($offset,$limit,$drive_name);
        if($result) {
            return $result;
        }
    }


    /**
     * 修改服务器页面渲染
     * @param id 产品的id int
     */
    public function edit()
    {
        $errorModel = new \app\common\model\Error();
        if(!empty($_GET['id'])){
            $result = Drivers::getListONe(array('id'=>$_GET['id']));
            $this->assign('driver',$result['data'][0]);
            $roleList = \app\common\model\Role::getListPrice($_GET['id']);
            if($roleList['code'] == 0){
                $this->assign('rolelist',$roleList['data']);
            }else{
                $roleList = \app\common\model\Role::getList(50,0,'');
                $i = 0;
                $info = [];
                foreach ($roleList['data'] as $value){
                    $info[$i] = $value;
                    $info[$i]['price'] = 0;
                    $i++;
                }
                $this->assign('rolelist',$info);
            }

            return $this->fetch('edit');
        }else{
            return $errorModel::ERRORCODE[20003];
        }
    }



    /**
     * 修改操作
     * @param data 修改的数据集合 array
     */
    public function editAction()
    {
        $errorModel = new \app\common\model\Error();
        $data = array();
        if(!empty($_POST['data'])){
            $data = $_POST['data'];
            $id = $_POST['data']['id'];
            $result = Drivers::upDateRecode(array('id'=>$id),$data);
            if($result['code'] == 0){
                if(is_array($_POST['price']) && !empty($_POST['price'])){
                    $driverPrice = array_filter($_POST['price']);
                    $data = array();
                    $i = 0;
                    foreach ($driverPrice as $key => $value){
                        $data[$i]['price'] = $value;
                        $data[$i]['drive_id'] = $id;
                        $data[$i]['user_type'] = $key;
                        $i++;
                    }
                    \app\common\model\Price::editAction($id,$data);
                }
            }
        }else{
            $result = array(
                'code' => 20005,
                'msg' => $errorModel::ERRORCODE[20005],
                'data' => array()
            );
        }
        return $result;
    }

    /**
     * 删除操作
     * @param id 产品的id int
     *
     */
    public function delAction()
    {
        $errorModel = new \app\common\model\Error();
        if(!empty($_POST['id'])){
            $result =  Drivers::delRecode(array("id"=>$_POST['id']));
        }else{
            $result = array(
                'code' => 20005,
                'msg' => $errorModel::ERRORCODE[20005],
                'data' => array()
            );
        }
        return $result;
    }


    /**
     * @return mixed
     * 推送数产品页面渲染
     */
    public  function product_push_the_number()
    {
        return $this->fetch('product_push_the_number');
    }


    /**
     * @return mixed
     * 推送数规则页面渲染
     */
    public  function rule_push_the_number()
    {
        return $this->fetch('rule_push_the_number');
    }


    /**
     * 对应生成XML文件
     */
    public function generateRuleXml()
    {
        $returnArray = array();
        if($_POST){
           $result =  Serverdata::RuleXml($_POST);
           if($result){
               return $result;
           }
        }else{
           $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => array()
            );
        }
    }



    /**
     * @explain 对服务器的操作
     * @param $serverid操作的id
     * @param $opcode操作码
     * @return array返回
     */
    public function operateServer()
    {
        $returnArray = array();
        if(!empty($_POST['serverid']) && !empty($_POST['opcode'])){//接收服务器id
            Cache::rm('code'.$_POST['serverid']);//清除缓存的该服务器提示代码
            $returnArray = Serverdata::operateServer($_POST['serverid'],$_POST['opcode']);
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => array()
            );
        }
        return $returnArray;
    }

    /**
     * @explain 对服务器的操作
     * @param $serverid操作的id
     * @param $opcode操作码
     * @return array返回
     */
    public function otherOperateServer()
    {
        $returnArray = array();
        if(!empty($_POST['serverid']) && !empty($_POST['opcode'])){
            Cache::rm('code'.$_POST['serverid']);
            $returnArray = Serverdata::operateServer($_POST['serverid'],$_POST['opcode']);
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => array()
            );
        }
        return $returnArray;
    }


    /**
     * @explain生成命令文件
     * @param $serverid操作的id
     * @param $opcode操作码
     * @return array返回
     */
    public function generateRuleCmd()
    {
        if(!empty($_POST['order']) && !empty($_POST['id'])){
            $myfile = fopen("shell/shell_".$_POST['id'].".cmd", "w") or die("Unable to open file!");
            $linkResult = $_POST['order'];
            fwrite($myfile, $linkResult);
            fflush($myfile);
            fclose($myfile);
            Operationlog::addOperation($this->userId,Request::instance()->module(),Request::instance()->controller(),Request::instance()->action(),2,'[服务器管理]生成命令〖linkfile/shell_'.$_POST['id'].'.cmd〗');

            $shellCommand = 'cd shell;./encryptionCmd '.$_POST['id']; //执行生成命令文件 DecryptFile
            system($shellCommand,$shellResult);
            if($shellResult == 0){ //已经执行生成命令成功 ，进行监听
                Operationlog::addOperation($this->userId,'api','ToServerApi','receiveState',4,'[服务器管理]执行下载SHELL命令成功！');
                Cache::rm('code'.$_POST['id']);//清除缓存的该服务器提示代码
                Cache::rm('cmddata'.$_POST['id']);//清除缓存的该服务器提示代码
                $returnArray = Serverdata::operateServer($_POST['id'],$_POST['opcode']);
                $serverIp = Config::get('server_ip');//获取服务器地址IP及其端口
                Operationlog::addOperation($this->userId,'common','Common','otherRequestGet',4,'[服务器管理]请求服务器链接：'.$serverIp.'生成命令成功，并返回结果：'.$returnArray);
            }else{
                $returnArray = [
                    'code' => 12003,
                    'msg' => Error::ERRORCODE[12003],
                    'data' => $shellResult
                ];
            }
        }else{
            $returnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => array()
            );
        }

        return $returnArray;
    }

    /** 升级upgrade
     * @return 一般监听服务器返回
     */
    public function lookStatus()
    {
        $returnArray = [];
        $result = Cache::get('code'.$_POST['id']);
        if($result){
            Cache::rm('code'.$_POST['id']);
            if($result){
                $returnArray = array(
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => array()
                );
            }
        }
        return $returnArray;
    }


    /**
     * @return 升级监听服务器返回
     */
    public function upgradeLookStatus()
    {
        $returnArray = [];
        $result = Cache::get('code'.$_POST['id']);
        if($result == $_POST['id']){
            $cmddata = Cache::get('cmddata'.$_POST['id']);
            $returnArray = array(
                'code' => 0,
                'msg' => Error::ERRORCODE[0],
                'data' => $cmddata
            );
            Cache::rm('code'.$_POST['id']);
            Cache::rm('cmddata'.$_POST['id']);
        }else if($result){
            $returnArray = array(
                'code' => 12004,
                'msg' => Error::ERRORCODE[12004],
                'data' => $result
            );
            Cache::rm('code'.$_POST['id']);
        }
        return $returnArray;
    }
///////////////////////////////////////////////////////////////////////////////////////////
//    更新服务器状态
    public function updateServerStatus()
    {
        $serverDataModel = new Serverdata();
        $result = $serverDataModel->checkServerStatus();
        if($result){
            return $result;
        }
    }

    /**
     * 复制服务器信息
     * 复制绑定记录
     */
    public function copy()
    {
        if($_POST['serverid']){
            $serverDataModel = new Serverdata();
            $serverInfo = $serverDataModel->getServerOne($_POST['serverid']);
            if($serverInfo['code'] == 0){
                $serverdetails = $serverInfo['data'][0];
                $serverdetails['servername'] = $serverdetails['servername'].time().rand(0000, 9999);
                foreach ($serverdetails as $key=>$value)
                {
                    if ($key === 'id')
                        unset($serverdetails[$key]);
                }
                $serverResult = $serverDataModel->addServer($serverdetails);
                $newServerid = $serverDataModel->getLastInsID();
                try {
                    //复制产品绑定记录
                    Serverproductdata::copy($_POST['serverid'],$newServerid);
                    //复制规则绑定记录
                    \app\common\model\Serverruledata::copy($_POST['serverid'],$newServerid);
                    //复制子规则绑定记录
                    Serverchildruledata::copy($_POST['serverid'],$newServerid);
                    //复制黑白名单
                    Ipblacklist::copy($_POST['serverid'],$newServerid);
                    Ipwhitelist::copy($_POST['serverid'],$newServerid);

                    $returnArray = array(
                        'code' => 0,
                        'msg' => Error::ERRORCODE[0],
                        'data' => []
                    );
                    return $returnArray;
                } catch (Exception $e){
                    $error = $e->getMessage();
                    $returnArray = array(
                        'code' => 40013,
                        'msg' => Error::ERRORCODE[40013],
                        'data' => $error
                    );

                    return $returnArray;
                }
            }else{
                $returnArray = array(
                    'code' => 40014,
                    'msg' => Error::ERRORCODE[40014],
                    'data' => []
                );
                return $returnArray;
            }
        }else{
            $returnArray = array(
                'code' => 40003,
                'msg' => Error::ERRORCODE[40003],
                'data' => []
            );
            return $returnArray;
        }
    }


}


