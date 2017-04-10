<?php
namespace Api\Controller;

use Common\Controller\AppframeController;
use Common\Controller\HomebaseController;


class GuestbookController  extends HomebaseController{

	protected $guestbook_model;

	public function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Common/Guestbook");
	}
	
	public function addmsg(){
//		if(!sp_check_verify_code()){
//			$this->error("验证码错误！");
//		}


		if (IS_POST) {

			$session_user=session('user');
			if(!empty($session_user)){//用户已登陆,且是本站会员
				$uid=session('user.id');
				$_POST['uid']=$uid;
				$users_model=M('Users');
				$user=$users_model->field("user_login,user_email,user_nicename")->where("id=$uid")->find();
				$username=$user['user_login'];
				$user_nicename=$user['user_nicename'];
				$email=$user['user_email'];
				$_POST['full_name']=empty($user_nicename)?$username:$user_nicename;
				$_POST['email']=$email;
			} else {
				$_POST['full_name']="匿名";
				$_POST['email']="www@www.com";

			}

			if ($this->guestbook_model->create()!==false) {
				$result=$this->guestbook_model->add();
				if ($result!==false) {
					$this->ajaxReturn(sp_ajax_return(array("id"=>$result),"留言成功！",1));

//					$this->success("留言成功！");
				} else {
					$this->error("留言失败！");
				}
			} else {
				$this->error($this->guestbook_model->getError());
			}
		}
		
	}
}