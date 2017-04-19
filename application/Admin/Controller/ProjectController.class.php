<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/9/19
 * Time: 上午1:34
 */

namespace Admin\Controller;


use Portal\Model\AppointmentModel;
use Portal\Model\DoctorModel;
use Portal\Model\JoinMemberModel;
use Common\Controller\AdminbaseController;

define(CONTROLLER, __CONTROLLER__);

class  ProjectController extends AdminbaseController
{


    function join($queryStr = "", $page = 1, $pagesize = 10)
    {
        \Think\Log::write('join:', "INFO");

        $db = new JoinMemberModel();

        //利用page函数。来进行自动的分页
        $data = $db->select();

        \Think\Log::write('login write', 'WARN' . $data);
        $this->data = $data;
        $this->title = "参与成员";
        $this->display();
        \Think\Log::write('join end', "INFO");

    }

    function joinUpdate()
    {
        \Think\Log::write('joinUpdate:', "INFO");

        $db = new JoinMemberModel();

        if (!empty($_POST)) {
            try {
                if (!$db->create($_POST, 1)) {
                    echo $db->getError();
                } else {

                    if ($_POST['id']) {
                        if (!$db->save()) {
                            echo $db->getError();
                        } else {
                            $this->success('更新成功', 'join');
                        }
                    } else {
                        if (!$db->add()) {
                            echo $db->getError();
                        } else {
                            $this->success('新增成功', 'join');
                        }
                    }
                    $this->success('成功', 'join');
                }
            } catch (\Exception $e) { //这里的\Exception不加斜杠的话回使用think的Exception类
                $this->error('失败', 'join');
            }
        } else {
            if (isset($_GET['joinMemberId'])) {
                $this->data = $db->alias('a')
                    ->where('a.id=' . $_GET['joinMemberId'])->find();

                $this->retCode = "00";
                $this->msg = "查找成功";
            } else {
                $this->retCode = "01";
                $this->msg = "未找到该信息";
            }

            $this->display();
            \Think\Log::write('joinUpdate end', "INFO");
        }

    }


    /*
     * 医生信息
     * */
//    function form()
//    {
//        \Think\Log::write('form begin:', "INFO");
//        $db = new DoctorModel();
//
//        if (isset($_GET['doctorId'])) {
//            $this->data = $db->alias('a')->join('__DICT_TITLE__ as title ON title.id = a.title')
//                ->where('a.id='.$_GET['doctorId'])->find();
//
//            $cureTime = explode(',', $this->data["curetime"]);
//            $cureTimes = [];
//            foreach($cureTime as $ct){
//                    $cureTimes[$ct["id"]] = 'checked';
//            }
//            $this->cureTimes =$cureTimes;
//
//            $this->retCode = "00";
//            $this->msg = "查找成功";
//            $this->doctorId = $_GET['doctorId'];
//
//
//        } else {
//            $this->retCode = "01";
//            $this->msg = "未找到该信息";
//
//        }
//
//        $this->title = "医生信息";
//        $this->statuscode = $_GET['statuscode'];
//        $this->display();
//        \Think\Log::write('login form end', "INFO");
//
//    }


    /*
     * 预约信息-后台显示
     * */
//    function appointment($queryStr = '', $page = 1, $pagesize = 10)
//    {
//        \Think\Log::write('login appointment:', "INFO");
//
//        $DoctorController = new \Portal\Controller\DoctorController();
//
//        list($data, $recordnum, $pagenum) = $DoctorController->innerAppointment($queryStr, $page, $pagesize);
//
//        \Think\Log::write('login write', 'WARN' . $data);
//
//        $this->data = $data;
//        $this->pagenum = $pagenum;
//        $this->page = $page;
//        $this->pagesize = $pagesize;
//        $this->recordnum = $recordnum;
//        $this->title = "预约信息";
//
//        $this->display();
//        \Think\Log::write('login end', "INFO");
//    }

    /*
     * 预约信息-后台显示
     * */
    function regionChair($queryStr = '', $page = 1, $pagesize = 10)
    {
        \Think\Log::write('login appointment:', "INFO");

        $DoctorController = new \Portal\Controller\DoctorController();

        list($data, $recordnum, $pagenum) = $DoctorController->innerAppointment($queryStr, $page, $pagesize);

//        var_dump(($data),true);
        \Think\Log::write('login write', 'WARN' . $data);

        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "预约信息";

        $this->display();
        \Think\Log::write('login end', "INFO");
    }

    /*
      * 预约信息-后台显示
      * */
    function expert($queryStr = '', $page = 1, $pagesize = 10)
    {
        \Think\Log::write('login appointment:', "INFO");

        $DoctorController = new \Portal\Controller\DoctorController();

        list($data, $recordnum, $pagenum) = $DoctorController->innerAppointment($queryStr, $page, $pagesize);

//        var_dump(($data),true);
        \Think\Log::write('login write', 'WARN' . $data);

        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "预约信息";

        $this->display();
        \Think\Log::write('login end', "INFO");
    }


}