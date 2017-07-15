<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/6/11
 * Time: 下午11:41
 */

namespace Portal\Controller;

use Common\Controller\HomebaseController;
use Portal\Model\ExpertModel;
use Portal\Model\RegionChairModel;


define(CONTROLLER, __CONTROLLER__);

class  ProjectController extends HomebaseController{




    function index(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }
    function expert(){
        //获取系统常量, 并分组
        $db = new ExpertModel();
        $data = $db->select();
        $this->data = $data;
        $this->title = "专家团队";
        $this -> display();
    }

    function join()
    {
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        \Think\Log::write('join:', "INFO");
        if(!empty($_POST)) {

            $JoinMember = new \Portal\Model\JoinMemberModel(); // 实例化 JoinMemberModel 耐药率
            //查找是否已经存在耐药率数据
            $data = $JoinMember->select();
            $data["count"] = $JoinMember->count();
            \Think\Log::write('join post end.', "INFO");
            $this->ajaxReturn($data,"json");
        }
        else{
            \Think\Log::write('join get end.', "INFO");

            $this->display();

        }
    }

    function organization(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }
    function participation(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }
    function region(){
        //获取系统常量, 并分组
        $db = new RegionChairModel();
        $data = $db->select();
        $this->data = $data;
        $this->title = "区域负责人";
        $this -> display();
    }
    function research(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }
    function rights(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }

}