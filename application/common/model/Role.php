<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/16
 * Time: 11:57
 */

namespace app\common\model;

use think\Model;

Class Role extends Model{

    /**
     * @param $limit 限制几条
     * @param $offset 从第几条开始
     * @param int $serveruserid 服务器id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getList($limit,$offset,$name)
    {

        $criteria = array();
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(!empty($name)){
            $result = self::where($criteria)
                ->limit($offset,$limit)
                ->where('name','like','%'.$name.'%')
                ->select()
                ->toArray();
            $count = self::where($criteria)
                ->where('name','like','%'.$name.'%')
                ->count();
        }else{
            $result = self::where($criteria)
                ->limit($offset,$limit)
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

    /**添加角色记录
     * @param $data 添加记录的参数数组
     * @return array
     */
    public static function addAction($data)
    {
        $returnArray = array();
        $errorModel = new \app\common\model\Error();
        if(is_array($data)){
            $result = self::checkRole($data['name']);
                if($result == 1){
                    $result = self::insert($data);
                    if($result == 1){
                            $returnArray = array(
                                'code' => 0,
                                'msg' => $errorModel::ERRORCODE[0],
                                'data' => array()
                            );
                        }else{
                            $returnArray = array(
                                'code' => 60006,
                                'msg' => $errorModel::ERRORCODE[60006],
                                'data' => array()
                            );
                        }
                    }else{
                        $returnArray = array(
                            'code' => 60010,
                            'msg' => $errorModel::ERRORCODE[60010],
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

    public static  function checkRole($name)
    {
        if(!empty($name)){
            $result = self::where(array('name' => $name))->count();
            if($result > 0){
                return 0;
            }else{
                return 1;
            }
        }
    }

    /**
     * @param $data获取名单的条件
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
    /**
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
                    'code' => 60008,
                    'msg' => $errorModel::ERRORCODE[60008],
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


    /**删除黑名单记录
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
                    'code' => 60009,
                    'msg' => $errorModel::ERRORCODE[60009],
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

    /**
     * 获取每个角色的驾校价格
     * id
     *
     */
    public static function getListPrice($id)
    {
        if(!empty($id)){
            $result = self::alias('r')
                ->join('price p','p.user_type = r.id and p.drive_id='.$id,'LEFT')
                ->field('r.name,p.price,r.id')
                ->select()
                ->toArray();

            if(!empty($result)){
                $resurnArray = array(
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => $result,
                );
            }else{
                $resurnArray = array(
                    'code' => 10001,
                    'msg' => Error::ERRORCODE[10001],
                    'data' => []
                );
            }
        }else{
            $resurnArray = array(
                'code' => 10005,
                'msg' => Error::ERRORCODE[10005],
                'data' => []
            );
        }
        return $resurnArray;
    }
}


