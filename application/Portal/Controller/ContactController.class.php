<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/6/11
 * Time: 下午11:41
 */

namespace Portal\Controller;

use Common\Controller\HomebaseController;

define(CONTROLLER, __CONTROLLER__);

class  ContactController extends HomebaseController{

    function index(){
        //获取系统常量, 并分组
        $this -> display();
    }

    function pressCenter(){
        //获取系统常量, 并分组

        $term_id=6; //通知
        $term=sp_get_term($term_id);

        $data[0]["term"] = $term;
        $data[0]["cat_id"] = $term_id;

        $term_id=4; //HP临床诊治及学术研究最新成果
        $term=sp_get_term($term_id);
        $data[1]["term"] = $term;
        $data[1]["cat_id"] = $term_id;

        $term_id=5; //阶段性研究成果
        $term=sp_get_term($term_id);

        $data[2]["term"] = $term;
        $data[2]["cat_id"] = $term_id;

        $tplname=$term["list_tpl"];
        $tplname=sp_get_apphome_tpl($tplname, "list");
        $this->assign('data', $data);

        $this->display();
    }


    function chart(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        \Think\Log::write('chart begin:', "INFO");

        if(!empty($_POST)){
            $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率

            $drug = I('post.drug');
            $province = I('post.province');
            $year = I('post.year');

            \Think\Log::write('chart $drug:'.$drug, "INFO");

            $map = null;
            $map['province'] = array('like',"%".$province."%");
            $map['drug'] = array('like',"%".$drug."%");
            $map['year'] = array('like',"%".$year."%");

            \Think\Log::write('chart $map:'.$map, "INFO");

            //查找是否已经存在耐药率数据
            $data = $DrugResistanceRate->field('region, solerate')->where($map)->select();
            $data["count"] = $DrugResistanceRate->where($map)->count();

            \Think\Log::write('chart $data:'.$data, "INFO");
            $this->ajaxReturn($data,"json");
        }
        else{
            $this -> display();
        }
    }

    function map(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));
        \Think\Log::write('chart begin:', "INFO");

        if(!empty($_POST)){
            $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率

            $drug = I('post.drug');
            $province = I('post.province');
            $year = I('post.year');

            \Think\Log::write('chart $drug:'.$drug, "INFO");

            $map = null;
            $map['province'] = array('like',"%".$province."%");
            $map['drug'] = array('like',"%".$drug."%");
            $map['year'] = array('like',"%".$year."%");

            \Think\Log::write('chart $map:'.$map, "INFO");

            //查找是否已经存在耐药率数据
            $data = $DrugResistanceRate->field('region, solerate')->where($map)->select();
            $data["count"] = $DrugResistanceRate->where($map)->count();

            \Think\Log::write('chart $data:'.$data, "INFO");
            $this->ajaxReturn($data,"json");
        }
        else{
            $this -> display();
        }


    }

    function academic(){
        //获取系统常量, 并分组
        $this -> display();
    }

    function question(){
        //获取系统常量, 并分组

        $this -> display();
    }
}