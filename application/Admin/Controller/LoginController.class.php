<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/9/19
 * Time: 下午11:41
 */

namespace Admin\Controller;

use Think\Controller;

define(CONTROLLER, __CONTROLLER__);

class  LoginController extends Controller{



    /**
     * @param $options
     * @param $authnum
     * @throws \Org\Com\Exception
     */
    public function login(){

        if (empty($_POST)) {
            $loginName = session('loginName'); //保存授权信息
            if($loginName){
                redirect(CONTROLLER . '/../Index/index', 2, '您已经登录, 页面跳转中...');
            }

            $this -> display();
            return;
        }

        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $loginName = $_POST['loginName'];
        $password = $_POST['password'];

        //echo $phoneNumber;
        //echo $code;
        $User = M("User");
        //echo $Msgcode;
        $data = $User->where('phoneNumber="%s"', $loginName)->order('CREATETIME desc')->limit(1)->find();
        //echo $data;
//        var_dump($data);
        if(!$data){
            $ret['rescode'] = "01";
            $ret['msg'] = "用户不存在";
        }
        else if($data["password"] != $password ){
            $ret['rescode'] = "01";
            $ret['msg'] = "用户名或密码错误".$data["password"];
        }
        else{
            session('loginName',$loginName); //保存授权信息

            $ret['rescode'] = "00";
            $ret['msg'] = "验证通过";
        }

        $this->ajaxReturn($ret);

    }



}