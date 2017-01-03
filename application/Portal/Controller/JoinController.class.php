<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/6/11
 * Time: 下午11:41
 */

namespace Portal\Controller;

//use Think\Controller;
use Common\Controller\HomebaseController;

define(CONTROLLER, __CONTROLLER__);

class  JoinController extends HomebaseController{




    function index(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        \Think\Log::write('index','info');
//        session('token', null);

        $token = session('token');
        $token['openid']="oFT0muO-LKGtUPx-4ZhvD3eKNoy0";
        session('token', $token);
        $token = session('token');


        if($token && $token['openid']){
            //已经获取了tokenid 用户 可以直接登录界面
            \Think\Log::write('index: '.$token,'info');

            \Think\Log::record('$token already has '.$token);
            $this->assign('openid', $token['openid']);
            $this->check_wechat_user();
            $this->display();

        }
        else{
            $code = $_GET['code'];
            \Think\Log::write('index: '.$code,'info');

            $get_code_url = sp_get_access_token_url($code);

            $token_data = file_get_contents($get_code_url);
            \Think\Log::write('login $token_data '.$token_data,'info');

            $token = json_decode($token_data, TRUE);

            if($token && $token['openid']){
                $token['appid'] = appId;
                session('token',$token); //保存授权信息
                $this->assign('openid', $token['openid']);
                $this->check_wechat_user();
            }
            else{
                //未能正确获取openid, 重新定向该页面
                \Think\Log::write('login write: error to get openid','info');
                $return_url = U("portal/join/index",'',true,true);
                \Think\Log::write('login write $return_url:'.$return_url,'info');
                $url = sp_get_wechat_code_url($return_url);
                \Think\Log::write('login write $url:'.$url,'info');
                redirect($url, 2, 'please wait...');
            }


        }
    }

    /**
     * @param $return_url
     */
    public function check_wechat_user()
    {
        \Think\Log::write('check_wechat_user:','info');
        $user = session('user');
        if(!$user){
            $user = $this->get_wechat_user();
            session('user', $user);
        }

        \Think\Log::write('check_wechat_user $user:'.$user,'info');

        if ($user) {
            if($user['user_type'] == DOCTOR){
                $doctorId = $this->get_doctor_id();
                session('doctor', $doctorId);
                \Think\Log::write('check_wechat_user $doctorId: '.$doctorId,'info');

                $return_url = U("portal/doctor/myPatient",'',true,true);
            }
            else{
                $patientId = $this->get_patient_id();
                session('patient', $patientId);
                $return_url = U("portal/patient/appointment",'',true,true);
            }
            redirect($return_url, 2, 'please wait...');
        } else {
            $this->display();
        }
    }

}