<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/17
 * Time: 9:36
 */
namespace app\admin\controller;

use think\Controller;

Class Rulestatistics extends Common{
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
    public  function rulePushTheNumber()
    {
        if(!empty($_GET['serverid']) && !empty($_GET['time']) && !empty($_GET['id'])){
            $productid = $_GET['id'];
            $serverid = $_GET['serverid'];
            $serverDataModel = new \app\common\model\Serverdata();
            $result = $serverDataModel->getServerOne($_GET['serverid']);
            $this->assign('server',$result['data'][0]);
            $time = $_GET['time'];
            $this->assign('productid',$productid);
            $this->assign('serverid',$serverid);
            $this->assign('time',$time);
            return $this->fetch('rule_push_the_number');
        }
    }

    /**\
     * 获得推送子规则
     */
    public function  childrulepushthenumber()
    {
        if(!empty($_GET['serverid']) && !empty($_GET['productid']) && !empty($_GET['topruleid'])){
//            获取前一个月的时间
            $productid = $_GET['productid'];
            $serverid = $_GET['serverid'];
            $topruleid = $_GET['topruleid'];
            $serverDataModel = new \app\common\model\Serverdata();
            $result = $serverDataModel->getServerOne($_GET['serverid']);
            $this->assign('server',$result['data'][0]);
            $time = $_GET['time'];
            $this->assign('productid',$productid);
            $this->assign('serverid',$serverid);
            $this->assign('topruleid',$topruleid);
            $this->assign('time',$time);
            return $this->fetch('child_push_the_number');
        }
    }




    public function getRulestatistics()
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
        if(isset($_GET["productid"])){
            $where['rss.productid'] = $_GET["productid"];
        }else{
            $returnArray = array(
                'code' => 20006,
                'msg' => $errorModel::ERRORCODE[20006],
                'data' => array()
            );
        }
        if(isset($_GET["serverid"])){
            $where['rss.serverid'] = $_GET["serverid"];
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
        if(isset($_GET["seacher"])){
            $seacher = $_GET["seacher"];
        }else{
            $seacher = '';
        }

        if(empty($returnArray)){
            $rulestatisticsModel = new \app\common\model\Rulestatistics();
            $result = $rulestatisticsModel->getRulestatiscs($startTime,$where,$offset,$limit,$seacher) ;
        }

        if($result) {
            return $result;
        }
    }

    public function getRulestatisticsTime()
    {

        $returnArray = array();
        $where = array();
        $errorModel = new \app\common\model\Error();

        if(isset($_GET["limit"])){
            $limit = $_GET["limit"];
        }else{
            $limit = 30;
        }
        if(isset($_GET["page"])){
            $offset = ($_GET["page"] -1) * $limit;
        }else{
            $offset = 0;
        }
        if(isset($_GET["productid"])){
            $where['rss.productid'] = $_GET["productid"];
        }else{
            $returnArray = array(
                'code' => 20006,
                'msg' => $errorModel::ERRORCODE[20006],
                'data' => array()
            );
        }
        if(isset($_GET["serverid"])){
            $where['rss.serverid'] = $_GET["serverid"];
        }else{
            $returnArray = array(
                'code' => 40003,
                'msg' => $errorModel::ERRORCODE[40003],
                'data' => array()
            );
        }
        if(isset($_GET["topruleid"])){
            $where['rss.topruleid'] = $_GET["topruleid"];
        }else{
            $returnArray = array(
                'code' => 40003,
                'msg' => $errorModel::ERRORCODE[40003],
                'data' => array()
            );
        }

        if(isset($_GET["time"])){
//            获取当天开始的时间

            if(ctype_digit($_GET['time'])) {
                $commonController = new \app\common\controller\Common();
                $startTime = $commonController->zeroTimestamp($_GET["time"]);
                $endTime =$startTime + 86400;
                $startTime = strtotime("-1 month",$startTime);
            }else{
                $startTime  = strtotime($_GET['time']);
                $endTime = $_GET["endTime"];
                $endTime = strtotime($endTime);
            }
        }else{
            $returnArray = array(
                'code' => 80001,
                'msg' => $errorModel::ERRORCODE[80001],
                'data' => array()
            );
        }


        if(empty($returnArray)){
            $rulestatisticsModel = new \app\common\model\Rulestatistics();
            $result = $rulestatisticsModel->getRulestatiscsTime($startTime,$endTime,$where,$offset,$limit) ;
        }

        if($result) {
            return $result;
        }
    }

}