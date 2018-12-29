<?php
/**
 * Created by PhpStorm.
 * User: k
 * Date: 2018/11/8
 * Time: 14:12
 */
namespace app\common\model;

use app\common\controller\Index;
use think\Model;

Class Price extends Model{
    /** 批量添加自规则绑定
     * @param $data 绑定数据的二维数组
     */
    public static function addListPrice($data)
    {
        $errorModel = new \app\common\model\Error();
        $returnArray = array();
        if(is_array($data)){
            $result = self::insertAll($data);

            if($result > 0){
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[0],
                    'data' => $result
                );
            }else{
                $returnArray = array(
                    'code' => 50011,
                    'msg' => $errorModel::ERRORCODE[50011],
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

    public static function getListDetail($id)
    {
        if(!empty($id)){
            $result = self::alias('p')
                    ->join('role','role.id = p.user_type and  p.drive_id='.$id,'LEFT')
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

    public static function editAction($id,$data)
    {
        $errorModel = new \app\common\model\Error();
        if(!empty($id)){
            self::where(array('drive_id' => $id))->delete();
        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => $errorModel::ERRORCODE[10002],
                'data' => array()
            );
        }
        $returnArray = array();
        if(is_array($data)){
            $result = self::insertAll($data);
            if($result > 0){
                $returnArray = array(
                    'code' => 0,
                    'msg' => $errorModel::ERRORCODE[0],
                    'data' => $result
                );
            }else{
                $returnArray = array(
                    'code' => 50011,
                    'msg' => $errorModel::ERRORCODE[50011],
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