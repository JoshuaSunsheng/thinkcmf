<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/6/11
 * Time: 下午11:41
 */

namespace Portal\Controller;

use Portal\Model\PatientModel;
use Common\Controller\HomebaseController;

define("CONTROLLER", __CONTROLLER__);

class PatientController extends HomebaseController{

    /*
     * 病人第一次注册登录
     * */

    function login(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
//        var_dump(get_defined_constants(true));
        \Think\Log::record('login record');
        \Think\Log::write('login write','WARN');

        $token = session('token');

        if(!$token){
            $code = $_GET['code'];
            $get_code_url = OAURL_ACCESS_TOKEN.'?appid='.appId.'&redirect_uri='.CONTROLLER.'/login'.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
            //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
            \Think\Log::write('login write '.$get_code_url,'WARN');
            redirect($get_code_url, 2, '页面跳转中...');

            $token_data = file_get_contents($get_code_url);

            $token = json_decode($token_data, TRUE);
            if($token){
                $token['appid'] = appId;
                //$param = file_get_contents(OAURL, false, stream_context_create($opts));
                session('token',$token); //保存授权信息
                //var_dump(session('token'), true);
                $this -> display();

            }
            else{
                echo "获取异常";
            }

            echo "<hr>";

        }
        else{
            \Think\Log::record('$token already has '.$token);
            $this -> display();

        }

        //微信网页授权登录获取code


    }

    /*
     * 病人第一次注册登录
     * 知情同意书
     * */
    function agreement(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        $this -> display();
    }

    /*
     * 病人第一次注册登录
     * 填写表格
     * */
    function application()
    {
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        $token = session('token');

        if (empty($token)) {
            //echo CONTROLLER.'/login';
            redirect(CONTROLLER.'/login', 2, '页面跳转中...');
        }


        if (!empty($_POST)) {
            $Patient = new \Portal\Model\PatientModel(); // 实例化 Patient对象
            //$Patient->getByPhoneNumber();

            $Patient->create($_POST, 1);
            $z = $Patient->add();

            if ($z) {
                $this->success('提交成功', 'appointment');
            } else {
                echo '提交失败, 请重新提交!';
            }
        } else {
            $this->display();
        }
    }

    /*
     * 病人预约
     * 预约医生
     * */
    function appointment(){
        //获取系统常量, 并分组

        //$Appointment = new \Home\Model\AppointmentModel(); // 实例化 Appointment对象

        $token = session('token');

        if (empty($token)) {
            redirect(CONTROLLER . '/login', 2, '页面跳转中...');
        }


        if (!empty($_POST)) {
            $Appointment = new \Portal\Model\AppointmentModel(); // 实例化 Patient对象
            //$Patient->getByPhoneNumber();
            $Appointment->create($_POST, 1);

            \Think\Log::write("==========================================================");
            $string=implode(' ',$_POST);
            \Think\Log::write($string);
            $z = $Appointment->add();
            if ($z) {
                $this->success('提交成功', 'appointment');
            } else {
                echo '提交失败, 请重新提交!';
            }
        } else {
            $this->display();
        }
    }

    public function fanye(){
        //获取系统常量, 并分组
        $token = session('token');

        if (empty($token)) {
            redirect(CONTROLLER . '/login', 2, '页面跳转中...');
        }

        $province = I('param.province');
        $city = I('param.city');
        $district = I('param.district');
        $isNearBy = I('param.isNearBy');

        if($province){
            $map['province'] = $province;
        }

        if($city){
            $map['city'] = $city;
        }

        if($district){
            $map['district'] = $district;
        }

        //dump(I('param.'));
        $start = I('param.pageNo') * 3 - 3;
        $end = 3;
        $Doctor = new \Portal\Model\DoctorModel(); // 实例化 Patient对象
        //$Patient->getByPhoneNumber();


        $ret['list'] = $Doctor->where($map)->order('createTime')->limit($start,$end)->select();
        $ret['maxResult'] = $Doctor->where($map)->count();

        $this->ajaxReturn($ret, 'JSON');

    }

    /**
     * @param $options
     * @param $authnum
     * @throws \Org\Com\Exception
     */
    public function send(){

        \Think\Log::write('send:', "INFO");

        //初始化必填
        $options['accountsid']='6c53057d22d325f222eb5d0188d38e89'; //填写自己的
        $options['token']='4f57071121447bf5af8182dc7a7c3db5'; //填写自己的
        //初始化 $options必填
        $ucpass = new \Org\Com\Ucpaas($options);

        //随机生成6位验证码
        srand((double)microtime()*1000000);//create a random number feed.
//        $ychar="0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        $ychar="0,1,2,3,4,5,6,7,8,9";
        $list=explode(",",$ychar);
        $authnum = "";
        for($i=0;$i<6;$i++){
            $randnum=rand(0,9); // 0-9;
            //$randnum=rand(0,35); // 10+26;
            $authnum.=$list[$randnum];
        }


        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $appId = "dad0c81fddc140cb8566ea8f5e9b8252";  //填写自己的
        $to = $_POST['to'];
        $templateId = "25567";
        $param=$authnum;

        $MSGCODE = new \Portal\Model\MsgcodeModel();

        //记录短信验证码至数据库
        $data['CODE'] = $authnum;
        $data['phoneNumber'] = $to;
        $data['SENDFLAG'] = 0;
        //        $data['VALIDTIME'] = Date('Y-m-d H:i:s') ;
        $data['VALIDTIME'] = date ( "Y-m-d H:i:s", mktime ( date ( "H" ), date ( "i" ) + 5, date ( "s" ), date ( "m" ), date ( "d" ), date ( "Y" ) ) );

        $arr=$ucpass->templateSMS($appId,$to,$templateId,$param);
        \Think\Log::write('send:'.$to, "INFO");
        \Think\Log::write('send:'.$param, "INFO");
        \Think\Log::write('send:'.$arr, "INFO");

        if (substr($arr,21,6) == 000000) {
//        if (true) {
            //如果成功就，这里只是测试样式，可根据自己的需求进行调节
            echo "短信验证码已发送成功，请注意查收短信";
            \Think\Log::write('send '.$to.'验证码:'.$authnum, "INFO");

        }else{
            //如果不成功
            echo "短信验证码发送失败，请联系客服";
            $data['SENDFLAG'] = 1;

        }

        $MSGCODE->data($data)->add();

        \Think\Log::write('send end.', "INFO");


    }


    /**
     * @param $options
     * @param $authnum
     * @throws \Org\Com\Exception
     */
    public function verifyCode(){


        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $phoneNumber = $_POST['to'];
        $code = $_POST['msgCode'];

        //echo $phoneNumber;
        //echo $code;

        $Msgcode = M("Msgcode");

        //echo $Msgcode;
        $data = $Msgcode->where('status=0 AND phoneNumber=%s', $phoneNumber)->order('CREATETIME desc')->limit(1)->find();

        //echo $data;
//        var_dump($data);
        $currentTime = date ( "Y-m-d H:i:s");

        $ret['rescode'] = "01";

        //验证码失效
        if(strtotime($currentTime)>strtotime($data['validtime'])){
            $ret['msg'] = "验证码失效";
        }
        else{
            if(trim($data[code]) == trim($code)){
                $ret['rescode'] = "00";
                $ret['msg'] = "验证通过";
            }
            else{
                $ret['msg'] = "验证码错误";
            }
        }

        $token = session('token'); //保存授权信息
        $token['phoneNumber'] = $phoneNumber;

        session('token', $token); //保存授权信息

        //print_r($token);
        //var_dump($token, true);

        $this->ajaxReturn($ret);

    }


}