<?php
/**
 * Created by PhpStorm.
 * User: wanghaiyang
 * Date: 2018/10/12
 * Time: 9:19
 */

namespace app\common\model;

use think\Model;

Class Users extends Model{



    /**
     * @param $access 账号
     * @param $passwd 密码
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function loginSin($access,$passwd){
        $errorModel = new \app\common\model\Error();
        if(!empty($access) && !empty($access)){
            $data = array();
            $data['name'] = $access;
            $data['password'] = $passwd;
            $result = self::where($data)->select()->toArray();
            if(!empty($result)){
                    $returnArray = array(
                        'code' => 0,
                        'msg' => $errorModel::ERRORCODE[0],
                        'data' => $result
                    );
            }else{
                $returnArray = array(
                    'code' => 10003,
                    'msg' => $errorModel::ERRORCODE[10003],
                    'data' => array()
                );
            }
        }else{
//            密码账号不能为空
            $returnArray = array(
                'code' => 10004,
                'msg' => $errorModel::ERRORCODE[10004],
                'data' => array()
            );
        }

        return $returnArray;
    }


    public static function getOne($where)
    {
        $returnArray = [];
        if(!empty($where)){
            $result = self::where($where)->find();
            if(!empty($result)){
                $result = $result->toArray();
                $returnArray =[
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => $result
                ];
            }else{
                $returnArray =[
                    'code' => 13001,
                    'msg' => Error::ERRORCODE[13001],
                    'data' => $result
                ];
            }
        }else{
            $returnArray =[
                'code' => 13004,
                'msg' => Error::ERRORCODE[13004],
                'data' => []
            ];
        }

        return $returnArray;
    }

    /**添加用户记录
     * @param $data 添加记录的参数数组
     * @return array
     */
    public static function addUserAction($data)
    {
        $returnArray = array();
        if(is_array($data)){
            $result = self::insert($data);
            if($result == 1){
                $returnArray = array(
                    'code' => 0,
                    'msg' => Error::ERRORCODE[0],
                    'data' => array()
                );
            }else{
                $returnArray = array(
                    'code' => 13007,
                    'msg' => Error::ERRORCODE[13007],
                    'data' => array()
                );
            }
        }else{
            $returnArray = array(
                'code' => 10002,
                'msg' => Error::ERRORCODE[10002],
                'data' => array()
            );
        }
        return $returnArray;
    }

    /**
     * @param array $data 修改内容
     * @param array $where 修改条件
     * @param null $field 修改字段
     * @return Model
     */
    public static function update($data = [], $where = [], $field = null)
    {
        return parent::update($data, $where, $field); // TODO: Change the autogenerated stub
    }

    /**
     * @param mixed $data 删除操作
     * @return int
     */
    public static function destroy($data)
    {
        return parent::destroy($data); // TODO: Change the autogenerated stub
    }

    /**
     ** 获取子账号列表
     **/
    public static function getManyList($limit = 0,$offset = 0)
    {
        $criteria = array();
        $returnArray = array();
        $errorModel = new \app\common\model\Error();

        if($limit != 0 && $offset != 0){
            $result = self::where('id','<>',1)->limit($offset,$limit)->select()->toArray();
        }else{
            $result = self::where('id','<>',1)->select()->toArray();
        }
        $count = self::where('id','<>',1)
            ->count();

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
}
//        13001 => '查不到该用信息',
//        13002 => '修改用户信息无效',
//        13003 => '删除用户失败',
//        13004 => '用户ID不能为空',
//        13005 => '用户密码不能为空',
//        13006 => '用户账号不能为空',
//        13007 => '添加用户失败',