<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/10
 * Time: 11:15
 */
namespace app\common\model;

use app\common\controller\Common;
use think\Cache;
use think\Config;
use think\Model;

/**
 * Class Drivers
 * @package app\common\model
 * explan 驾校model
 */
Class Drivers extends Model{
    /**
     * @param $limit
     * @param $offset
     * @param int $serveruserid
     * @param $content
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 获取驾校列表
     */
    public function getList($limit,$offset,$drive_name)
    {

        $criteria = array();
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(!empty($drive_name)){
            $result = self::where($criteria)
                ->limit($offset,$limit)
                ->where('drive_name','like','%'.$drive_name.'%')
                ->order('id desc')
                ->select()
                ->toArray();
            $count = self::where($criteria)
                ->where('drive_name','like','%'.$drive_name.'%')
                ->order('id')
                ->count();
        }else{
            $result = self::where($criteria)
                ->limit($offset,$limit)
                ->order('id desc')
                ->select()
                ->toArray();

            $count = self::where($criteria)
                ->count();
        }

        if(!empty($result)){
            $returnArray = array(
                'code' => 0,
                'msg' => $errorModel::ERRORCODE[0],
                'count' =>$count,
                'data' => $result
            );
        }else{
            $returnArray = array(
                'code' => 10001,
                'msg' => $errorModel::ERRORCODE[10001],
                'data' => $result
            );
        }
        return $returnArray;
    }


    /**
     * 添加数据
     * @param data 添加数组参数
     */
    public function addDriver($data)
    {
        $errorModel = new \app\common\model\Error();
        $returnArray = array();
        if(is_array($data)){
            $checkResult = self::checkdriveName($data['drive_name']);
            if($checkResult > 0){
                $returnArray = array(
                    'code' => 40005,
                    'msg' => $errorModel::ERRORCODE[40005],
                    'data' => array()
                );
            }else{
                $result = self::insert($data);
                if($result == 1){
                    $returnArray = array(
                        'code' => 0,
                        'msg' => $errorModel::ERRORCODE[0],
                        'data' => $result
                    );
                }else{
                    $returnArray = array(
                        'code' => 40002,
                        'msg' => $errorModel::ERRORCODE[40002],
                        'data' => array()
                    );
                }
            }

        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => $errorModel::ERRORCODE[10002],
                'data' => array()
            );
        }
        return $returnArray;
    }



    /**校验重复的名称
     * @param $data
     */
    public function checkdriveName($name,$id = 0)
    {
        if($id == 0){
//        对新增数据进行名称查重 返回0/1
            $result = self::where(array('drive_name'=>$name))->count();
        }else{
//            对修改数据进行查重
            $result = self::where(array('drive_name'=>$name))->select()->toArray();

            if($result){
                if($result[0]['id'] == $id){
                    $result = 0 ;
                }else{
                    $result = 1;
                }
            }else{
                $result = 0;
            }
        }
        return $result;
    }

    /**
     * @param $data获取驾校的条件
     */
    public static function getListONe($data)
    {
        $errorModel = new \app\common\model\Error();
        $returnArray = array();
        if(is_array($data)){
            $result = self::where($data)->select()->toArray();
            if(!empty($result)){
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[0],
                    'data' => $result
                );
            }else{
                $returnArray = array(
                    'code' => 60002,
                    'msg' => $errorModel::ERRORCODE[60002],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => $errorModel::ERRORCODE[10002],
                'data' => array()
            );
        }
        return $returnArray;
    }


    /**修改黑名单记录
     * @param $data 修改记录的参数数组
     * @return array
     */
    public static function upDateRecode($where,$data)
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(is_array($data)){
            $result = self::where($where)->update($data);
            if($result == 1){
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[0],
                    'data' => array()
                );
            }else{
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[40004],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => $errorModel::ERRORCODE[10002],
                'data' => array()
            );
        }
        return $returnArray;
    }



    /**删除驾校记录
     * @param $data 修改记录的参数数组
     * @return array
     */
    public static function delRecode($where)
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(is_array($where)){
            $result = self::where($where)->delete();
            if($result > 0){
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[0],
                    'data' => array()
                );
            }else{
                $returnArray = array(
                    'code' => 40009,
                    'msg' => $errorModel::ERRORCODE[40009],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => $errorModel::ERRORCODE[10002],
                'data' => array()
            );
        }
        return $returnArray;
    }

}


