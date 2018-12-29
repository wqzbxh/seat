<?php
namespace app\admin\controller;

use app\common\model\Menuinfo;
use app\common\model\Warningdata;
use think\Controller;

class Index extends  Common{

    /**
     * 继承父类自动加载
     */
    public function _initialize()
    {
        parent::_initialize();
    }


    public function testSon()
    {
        $result = Menuinfo::sonGetList(3);
        if($result['code'] == 0){
            $data = self::arrayPidProcess($result['data']);
            $resultArray = [
                'code' => 0,
                'msg' => Error::ERRORCODE[0],
                'data' => $data
            ];
        }else{
            $resultArray = $result;
        }
        var_dump($resultArray);
    }


    public function index()
    {

        $commonCrontroller = new \app\common\controller\Common();
        if($this->userId == 1){//返回超级用户左侧菜单主题
            $result = Menuinfo::getMenuList();
            $menulist = $commonCrontroller->arrayPidProcess($result);
            $this->assign('menulist',$menulist);
        }else{
            $result = Menuinfo::sonGetList($this->userId);
            if($result['code'] == 0){
                $menulist = $commonCrontroller->arrayPidProcess($result['data']);
                $this->assign('menulist',$menulist);
            }else{
                $this->assign('msg',$result['msg']);
            }
        }

        $this->assign('username',session('userInfo')['nick_name']);
        $this->assign('userflag',session('userInfo')['type']);
        return $this->fetch('index');
    }


    /**
     * 白名单
     * @return Request
     */
    public function whiteIndex()
    {
        $serverDataModel = new \app\common\model\Serverdata();
//        获取服务器列表
        $result = $serverDataModel->getServerList('',0,1000,$this->userId);
        if($result['code'] == 0){
            $this->assign('serverList',$result['data']);
            $this->assign('serverDefault',$result['data'][0]['id']);
            return $this->fetch('whitelist/index');
        }

    }

    /**
     * 黑名单
     * @return Request
     */
    public function roleindex()
    {
        return $this->fetch('role/index');

    }



    /**
     * @return mixed
     * 服务器管理首页
     */
    public function driverIndex()
    {
        return $this->fetch('driver/index');

    }




    /**
     * @return mixed|string
     * 渲染采集信息首页模块
     * 返回数据库表名 为服务器名
     * 返回默认数据
     */

    public function httpdatacollectServeridIndex()
    {
        $httpDataCollectServerModel = new \app\common\model\HttpdatacollectServerid();
        $result = $httpDataCollectServerModel->getTables();
        if($result['code'] == 0){
            $this->assign('tables',$result['data']);
            $this->assign('defaultTables',key($result['data']));
            return $this->fetch('httpdatacollect_serverid/index');
        }else{
            return '分库中暂无数据表';
        }

    }

    /**
     * @return mixed
     * 渲染策略模式主题下产品管理首页
     */
    public function productIndex()
    {
        return $this->fetch('product/index');
    }


    /**
     * @return mixed
     * 加载推送策略资源
     */

    public function pushpolicyIndex()
    {
        return $this->fetch('pushpolicy/index');
    }



    /**
     * 产品邦定页面
     **/
    public function binding()
    {

        $serverDataModel = new \app\common\model\Serverdata();
        $productDataModel = new \app\common\model\Productdata();
//        获取服务器列表
        $result = $serverDataModel->getServerList('',0,1000,$this->userId);
//        获取产品列表（分清与服务器绑定状态）getProductBindingList
        if($result['code'] == 0){
            $this->assign('serverList',$result['data']);
            $this->assign('serverDefault',$result['data'][0]['id']);
            return $this->fetch('product/binding');
        }
    }


    /**
     * 加载欢迎主页
     */

    public function main()
    {
        return $this-> view ->fetch('main');
    }

    /**
     * @return string 用户首页页面
     * @throws \think\Exception
     */

    public function  userIndex()
    {
        return $this-> view ->fetch('user/index');
    }

    public function test()
    {
    var_dump(APP_PATH);
    }

    /**
     * 加载链接链接管理页面资源
     */
    public function shortlinkset()
    {
        $serverDataModel = new \app\common\model\Serverdata();
        $productDataModel = new \app\common\model\Productdata();
//        获取服务器列表
        $result = $serverDataModel->getServerList('',0,1000,$this->userId);
        $linkResult = \app\common\model\Shortlinkset::find();
        if(!empty($linkResult)){
            $arrayResult = $linkResult->toArray();
            if(!empty($arrayResult['serverid'])){//说明是已经有更新过短链 反显上次的更新服务器
                $servcerids = explode(',',$arrayResult['serverid']);
                $this->assign('servcerids',$servcerids);
            }else{//说明已经添加过链接，但是没有更新过
                $this->assign('servcerids','');
            }
        }else{//证明数据库没有记录,服务器复选框全部选中
            $this->assign('servcerids','');
        }
        if($result['code'] == 0){
            $this->assign('serverList',$result['data']);
            return $this-> view ->fetch('shortlinkset/index');
        }

    }

    /**
     * 加载链接结果页面资源
     */
    public function warning()
    {
        return $this-> view ->fetch('warning/link_result_index');
    }
    /**
     * 加载链接结果页面资源
     */
    public function operation()
    {
        return $this-> view ->fetch('operation/operation');
    }


    public function prohibit_to_push()
    {
        $serverDataModel = new \app\common\model\Serverdata();
//        获取服务器列表
        $result = $serverDataModel->getServerList('',0,1000,$this->userId);
        if($result['code'] == 0){
            $this->assign('serverList',$result['data']);
            $this->assign('serverDefault',$result['data'][0]['id']);
            return $this->view->fetch('prohibit_to_push/index');
        }
    }
}
