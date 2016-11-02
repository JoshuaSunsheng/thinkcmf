<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/9/19
 * Time: 上午1:34
 */

namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Portal\Model\PatientModel;

//require '../../Common/Common/common.php';

define(CONTROLLER, __CONTROLLER__);

class  PatientController extends AdminbaseController
{


    function table($queryStr = "", $page = 1, $pagesize = 10)
    {

        \Think\Log::write('login table:', "INFO");

        $db = new PatientModel();

        //使用map作为查询条件,混合模式
        $where['realname'] = array('like', '%' . $queryStr . '%');
        $where['_logic'] = 'or';
        $map['_complex'] = $where;

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')
            ->page($page, $pagesize)
            ->join('__DICT_STATUS__ as status ON status.id = a.status')
            ->field('a.*,a.status as statusCode,
                 status.title as status')
            ->where($map)
            ->select();
        $recordnum = $db->page($page, $pagesize)
            ->where($map)
            ->count();

        //计算分页
        $pagenum = $recordnum / $pagesize;
        //如果不能整除，则自动加1页
        if ($pagenum % 1 !== 0) {
            \Think\Log::write('login record pagenum: ' . $pagenum, "INFO");
            $pagenum = (int)$pagenum + 1;
        }

//        var_dump(($data),true);
        \Think\Log::write('login write', 'WARN' . $data);

//        $this->assign('data',$data);
        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "患者列表";

        $this->display();
    }


    function form()
    {

        \Think\Log::write('login form:', "INFO");
        $db = new PatientModel();

        //利用page函数。来进行自动的分页
        if (isset($_GET['patientId'])) {
            $map['a.id'] = $_GET['patientId'];

            $data = $db->alias('a')
                ->join('__DICT_NATIONALITY__ as nationality ON nationality.id = a.nationality')
                ->join('__DICT_MARITAL_STATUS__ as marital_status ON marital_status.id = a.maritalStatus')
                ->join('__DICT_OCCUPATION__ as occupation ON occupation.id = a.occupation')
                ->join('__DICT_OCCUPATION__ as avocation ON avocation.id = a.avocation')
                ->join('__DICT_GASTRO_SCOPE__ as gastroScope ON gastroScope.id = a.gastroScope')
                ->join('__DICT_PATHOLOGY__ as pathology ON pathology.id = a.pathology')
                ->join('__DICT_STOMACH_HISTORY__ as stomachHistory ON stomachHistory.id = a.stomachHistory')
                ->join('__DICT_CANCER__ as familyCancer ON familyCancer.id = a.familyCancer')
                ->join('__DICT_RELATIONSHIP__ as relationship ON relationship.id = a.relationship')
                ->join('__DICT_STOMACH_SYMTOM__ as similarHistory ON similarHistory.id = a.similarHistory')
                ->join('__DICT_DRUG__ as drug ON drug.id = a.drug')
                ->field('a.*,
                 nationality.title as nationality,
                 marital_status.title as maritalStatus,
                 occupation.title as occupation,
                 avocation.title as avocation,
                 gastroScope.title as gastroScope,
                 pathology.title as pathology,
                 stomachHistory.title as stomachHistory,
                 familyCancer.title as familyCancer,
                 relationship.title as relationship,
                 similarHistory.title as similarHistory,
                 drug.title as drug')
                ->where($map)
                ->find();

            //前端radio显示封装数据, 第一项表示value为1时, 第二项表示value为0时
            $this->sex = [$data['sex'] == '1' ? 'checked' : '', $data['sex'] == '1' ? '' : 'checked'];
            $this->isbrithintown = [$data['isbrithintown'] == '1' ? 'checked' : '', $data['isbrithintown'] == '1' ? '' : 'checked'];
            $this->isintown = [$data['isintown'] == '1' ? 'checked' : '', $data['isintown'] == '1' ? '' : 'checked'];
            $this->iscancerfamily = [$data['iscancerfamily'] == '1' ? 'checked' : '', $data['iscancerfamily'] == '1' ? '' : 'checked'];
            $this->isothercancer = [$data['isothercancer'] == '1' ? 'checked' : '', $data['isothercancer'] == '1' ? '' : 'checked'];
            $this->ischeck = [$data['ischeck'] == '1' ? 'checked' : '', $data['ischeck'] == '1' ? '' : 'checked'];
            $this->checkstatus = [$data['checkstatus'] == '1' ? 'checked' : '', $data['checkstatus'] == '1' ? '' : 'checked'];
            $this->iscure = [$data['iscure'] == '1' ? 'checked' : '', $data['iscure'] == '1' ? '' : 'checked'];
            $this->usedanalgesic = [$data['usedanalgesic'] == '1' ? 'checked' : '', $data['usedanalgesic'] == '1' ? '' : 'checked'];
            $this->usedantiacid = [$data['usedantiacid'] == '1' ? 'checked' : '', $data['usedantiacid'] == '1' ? '' : 'checked'];
            $this->knowrelation = [$data['knowrelation'] == '1' ? 'checked' : '', $data['knowrelation'] == '1' ? '' : 'checked'];
            $this->inclination = [$data['inclination'] == '1' ? 'checked' : '', $data['inclination'] == '1' ? '' : 'checked'];

            $this->data = $data;
//            $this->data = $db->relation(true)->find($_GET['patientId']);
//        var_dump($this->data, true);

            $this->retCode = "00";
            $this->msg = "查找成功";
        } else {
            $this->retCode = "01";
            $this->msg = "未找到该信息";

        }
        \Think\Log::write('login write', 'WARN');
//        var_dump($this, true);

        $this->display();
    }


    //删除患者
    function deletePatient($id)
    {
        \Think\Log::record('deletePatient record:' . $id);
        $db = new PatientModel();
        $db->where('id=' . $id)->delete();
        \Think\Log::record('deletePatient record end');
    }

    //审核通过患者
    function passPatient($id)
    {
        \Think\Log::record('passPatient record:' . $id);
        $db = new PatientModel();
//        $db->where('id=' . $id)->delete();
        $db->where('id=' . $id)->setField('status', PATIENT_PASS);
        \Think\Log::record('passPatient record end');
    }


    //审核失败患者
    function cancelPatient($id)
    {
        \Think\Log::record('cancelPatient record:' . $id);
        $db = new PatientModel();
        $db->where('id=' . $id)->setField('status',PATIENT_FAIL);

//        $db->where('id=' . $id)->delete();
        \Think\Log::record('cancelPatient record end');
    }

}