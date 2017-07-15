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
use Portal\Model\ExpertModel;
use Portal\Model\RegionChairModel;
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
     * 区域负责人-后台显示
     * */
    function regionChair()
    {
        \Think\Log::write('regionChair:', "INFO");

        $db = new RegionChairModel();

        if (!empty($_POST)) {   //post 提交更新专家信息
            \Think\Log::write('post chair:', "INFO");
            \Think\Log::write('post chair:'.$_POST.east, "INFO");

            foreach ($_POST as $key => $value)
            {
                $data['description'] = $value;
                $db->where('region='.'\''.$key.'\'')->save($data);
                \Think\Log::write('regionChair:'.$key, "INFO");

            }

        } else {
            //利用page函数。来进行自动的分页
            $data = $db->select();
            $this->data = $data;
            $this->title = "区域负责人";
            $this->display();

        }
        \Think\Log::write('regionChair end', "INFO");
    }

    /*
      * 专家团队-后台显示
      * */
    function expert()
    {
        \Think\Log::write('login expert:', "INFO");

        $db = new ExpertModel();

        if (!empty($_POST)) {   //post 提交更新专家信息
            \Think\Log::write('post expert:', "INFO");
            \Think\Log::write('post expert:'.$_POST.teamleader, "INFO");

            foreach ($_POST as $key => $value)
            {
                $data['name'] = $value;
                $db->where('profession='.'\''.$key.'\'')->save($data);
                \Think\Log::write('login expert:'.$key, "INFO");

            }

//            $this->success('成功', 'join');

//            try {
//                if (!$db->create($_POST, 1)) {
//                    echo $db->getError();
//                } else {
//
//                        if (!$db->save()) {
//                            echo $db->getError();
//                        } else {
//                            $this->success('更新成功', 'join');
//                        }
//
//                    $this->success('成功', 'join');
//                }
//            } catch (\Exception $e) { //这里的\Exception不加斜杠的话回使用think的Exception类
//                $this->error('失败', 'join');
//            }
        } else {
            //利用page函数。来进行自动的分页
            $data = $db->select();
            $this->data = $data;
            $this->title = "专家团队";
            $this->display();

        }

        \Think\Log::write('expert end', "INFO");
    }


}