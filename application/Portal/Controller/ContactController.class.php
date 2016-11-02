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
        //var_dump(get_defined_constants(true));

        $this -> display();
    }

    function pressCenter(){
        //获取系统常量, 并分组
//        $this->display();
//        $term_id=I('get.id',0,'intval');


        $term_id=4;
        $term=sp_get_term($term_id);

        $data[0]["term"] = $term;
        $data[0]["cat_id"] = $term_id;


        $term_id=5;
        $term=sp_get_term($term_id);

        $data[1]["term"] = $term;
        $data[1]["cat_id"] = $term_id;


        $term_id=6;
        $term=sp_get_term($term_id);

        $data[2]["term"] = $term;
        $data[2]["cat_id"] = $term_id;

//        var_dump($data, true);



        $tplname=$term["list_tpl"];
        $tplname=sp_get_apphome_tpl($tplname, "list");
//        $this->assign($data);
        $this->assign('data', $data);

        $this->display();
    }

    function chart(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }

    function academic(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }

    function question(){
        //获取系统常量, 并分组
        //var_dump(get_defined_constants(true));

        $this -> display();
    }



}