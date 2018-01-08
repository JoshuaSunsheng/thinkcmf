<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/9/19
 * Time: 上午1:34
 */

namespace Admin\Controller;

use Portal\Model\AppointmentModel;
use Portal\Model\CaseModel;
use Common\Controller\AdminbaseController;

define(CONTROLLER, __CONTROLLER__);

class  CaseController extends AdminbaseController
{

    function caseTable($queryStr = "", $page = 1, $pagesize = 10)
    {

        \Think\Log::write('caseTable:', "INFO");

        $db = new CaseModel();

        //使用map作为查询条件,混合模式
        $where['name'] = array('like', '%' . $queryStr . '%');
        $where['phonenumber'] = array('like', '%' . $queryStr . '%');
        $where['nationality'] = array('like', '%' . $queryStr . '%');
        $where['sex'] = array('like', '%' . $queryStr . '%');
        $where['_logic'] = 'or';
        $map['_complex'] = $where;

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')->page($page, $pagesize)
            ->join('__DICT_SEX__ as sex ON sex.id = a.sex')
            ->join('__DICT_NATIONALITY__ as nationality ON nationality.id = a.nationality')
            ->field('a.*,sex.title as sex,
                 nationality.title as nationality')
            ->where($map)
            ->select();
        $recordnum = $db->page($page, $pagesize)
            ->where($map)
            ->count();

        //计算分页
        $pagenum = $recordnum / $pagesize;
        //如果不能整除，则自动加1页
        if ($pagenum % 1 !== 0) {
            \Think\Log::write('login record pagenum: '.$pagenum, "INFO");
            $pagenum = (int)$pagenum + 1;
        }

//        var_dump(($data),true);
        \Think\Log::write('login write', 'WARN' . $data);

        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "病例列表";

        $this->display();
        \Think\Log::write('login end', "INFO");
    }


    public function caseForm(){
        \Think\Log::write('caseForm begin:'.$_GET['patientCaseId'], "INFO");

        $Study = new \Portal\Model\CaseModel(); // 实例化 Patient对象


        if (isset($_GET['patientCaseId'])) {
            $this->data = $Study->alias('a')
                ->where('a.id='.$_GET['patientCaseId'])->find();

            $this->retCode = "00";
            $this->msg = "查找成功";
            $this->caseId = $_GET['patientCaseId'];


        } else {
            $this->retCode = "01";
            $this->msg = "未找到该信息";

        }

        $this->title = "医生信息";
        $this->display();
        \Think\Log::write('login form end', "INFO");
    }
}