<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/17
 * Time: 9:36
 */
namespace app\admin\controller;

use think\Controller;

Class Productstatistics extends Common{
    /**
     * 继承父类自动加载
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     * 推送数产品页面渲染
     */
    public  function productPushTheNumber()
    {
        if(!empty($_GET['serverid']) && !empty($_GET['time'])){
            $serverid = $_GET['serverid'];
            $serverDataModel = new \app\common\model\Serverdata();
            $result = $serverDataModel->getServerOne($_GET['serverid']);
            $this->assign('server',$result['data'][0]);
            $time = $_GET['time'];
            $this->assign('serverid',$serverid);
            $this->assign('time',$time);
        }

        return $this->fetch('product_push_the_number');
    }

    public function getProductstatistics()
    {
        $returnArray = array();
        $where = array();
        $errorModel = new \app\common\model\Error();

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
        if(isset($_GET["serverid"])){
            $where['pss.serverid'] = $_GET["serverid"];
        }else{
            $returnArray = array(
                'code' => 40003,
                'msg' => $errorModel::ERRORCODE[40003],
                'data' => array()
            );
        }
        if(isset($_GET["time"])){
//            获取当天开始的时间
            $commonController = new \app\common\controller\Common();
            $startTime = $commonController->zeroTimestamp($_GET["time"]);
        }else{
            $returnArray = array(
                'code' => 80001,
                'msg' => $errorModel::ERRORCODE[80001],
                'data' => array()
            );
        }
        if(empty($returnArray)){

            $productStatiSticsModel = new \app\common\model\Productstatistics();
            $result = $productStatiSticsModel->getProductstatiscs($startTime,$where,$offset,$limit) ;
        }
        if($result) {
            return $result;
        }
    }

}