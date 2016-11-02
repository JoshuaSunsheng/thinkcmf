<?php
namespace Api\Controller;

use Common\Controller\AdminbaseController;

class GuestbookadminController extends AdminbaseController{
	
	protected $guestbook_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->guestbook_model=D("Common/Guestbook");
	}
	
	public function index(){
//		$this->error("保存失败！");
		$count=$this->guestbook_model->where(array("status"=>1))->count();
		$page = $this->page($count, 20);
		$guestmsgs=$this->guestbook_model->where(array("status"=>1))->order(array("createtime"=>"DESC"))->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign("page", $page->show('Admin'));
		$this->assign("guestmsgs",$guestmsgs);
		$this->display();
	}

	public function delete(){
		$id=I("get.id",0,'intval');
		$result=$this->guestbook_model->where(array("id"=>$id))->delete();
		if($result!==false){
			$this->success("删除成功！", U("Guestbookadmin/index"));
		}else{
			$this->error('删除失败！');
		}
	}


	/**
	 *  添加
	 */
	public function add() {
		$this->display();
	}

	/**
	 *  添加保存
	 */
	public function add_post() {
		if (IS_POST) {

			if ($this->guestbook_model->create()!==false) {
				if ($this->guestbook_model->add()!==false) {
					$this->success("添加成功！", U("Guestbookadmin/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->guestbook_model->getError());
			}
		}
	}



	/**
	 * 编辑
	 */
	public function edit(){
		$id= I("get.id",0,'intval');
		$navcat=$this->guestbook_model->where(array('id'=>$id))->find();
		$this->assign($navcat);
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function edit_post(){
		if (IS_POST) {
			if ($this->guestbook_model->create() !== false) {
				if ($this->guestbook_model->save() !== false) {
					$this->success("保存成功！", U("Guestbookadmin/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->guestbook_model->getError());
			}
		}
	}
}