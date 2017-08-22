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
use Portal\Model\ScoreItemModel;
use Common\Controller\AdminbaseController;

define(CONTROLLER, __CONTROLLER__);

class  DoctorController extends AdminbaseController
{
//    function table(){
//        //获取系统常量, 并分组
//        //var_dump(get_defined_constants(true));
//
//        $this -> display();
//    }

    function table($queryStr = "", $page = 1, $pagesize = 10)
    {


        \Think\Log::write('login table:', "INFO");

        $db = new DoctorModel();

        //使用map作为查询条件,混合模式
        $where['realname'] = array('like', '%' . $queryStr . '%');
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
//        dump($map);

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')->page($page, $pagesize)
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
        $this->title = "医生列表";

        $this->display();
        \Think\Log::write('login end', "INFO");

    }


    /*
     * 医生信息
     * */
    function form()
    {
        \Think\Log::write('form begin:', "INFO");
        $db = new DoctorModel();

        if (isset($_GET['doctorId'])) {
            $this->data = $db->alias('a')->join('__DICT_TITLE__ as title ON title.id = a.title')
                ->where('a.id='.$_GET['doctorId'])->find();

            $cureTime = explode(',', $this->data["curetime"]);
            $cureTimes = [];
            foreach($cureTime as $ct){
                    $cureTimes[$ct["id"]] = 'checked';
            }
            $this->cureTimes =$cureTimes;

            $this->retCode = "00";
            $this->msg = "查找成功";
            $this->doctorId = $_GET['doctorId'];


        } else {
            $this->retCode = "01";
            $this->msg = "未找到该信息";

        }

        $this->title = "医生信息";
        $this->statuscode = $_GET['statuscode'];
        $this->display();
        \Think\Log::write('login form end', "INFO");

    }


    /*
     * 预约信息-后台显示
     * */
    function appointment($queryStr = '', $page = 1, $pagesize = 10)
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

    //审核通过预约
    function passAppointment($id)
    {
        \Think\Log::record('passAppointment record:'.$id);
        \Think\Log::record('passAppointment record APPOINTMENT_ADMIN_PASS:'.APPOINTMENT_ADMIN_PASS);
        $db = new AppointmentModel();
//        $db->where('id=' . $id)->delete();
        $db->where('id=' . $id)->setField('status',APPOINTMENT_ADMIN_PASS);
        \Think\Log::record('passAppointment record end');
    }

    //审核失败预约
    function cancelAppointment($id)
    {
        \Think\Log::record('cancelAppointment record:'.$id);
        $db = new AppointmentModel();
//        $db->where('id=' . $id)->delete();
        $db->where('id=' . $id)->setField('status',APPOINTMENT_FAIL);
        \Think\Log::record('cancelAppointment record end');
    }

    //删除医生
    function deleteDoctor($id){
        \Think\Log::record('deleteDoctor record:'.$id);
        $db = new DoctorModel();
        $db->where('id=' . $id)->delete();
        \Think\Log::record('deleteDoctor record end');
    }

    //审核通过医生
    function passDoctor($id, $x, $y){
        \Think\Log::record('passDoctor record:'.$id);
        \Think\Log::record('setDoctorPosition record:'.$x);
        \Think\Log::record('setDoctorPosition record:'.$y);
        $db = new DoctorModel();
        $db->where('id=' . $id)->setField('status',DOCTOR_PASS);
        $db->where('id=' . $id)->setField('x',$x);
        $db->where('id=' . $id)->setField('y',$y);

        \Think\Log::record('passDoctor record end');
    }

    //审核失败医生
    function cancelDoctor($id){
        \Think\Log::record('cancelDoctor record:'.$id);
        $db = new DoctorModel();
        $db->where('id=' . $id)->setField('status',DOCTOR_FAIL);

        $db->where('id=' . $id)->delete();
        \Think\Log::record('cancelDoctor record end');
    }


    /*
      * 专家团队-后台显示
      * */
    function score()
    {
        \Think\Log::write('score:', "INFO");

        $db = new ScoreItemModel();

        if (!empty($_POST)) {   //post 提交更新专家信息
            \Think\Log::write('post expert:', "INFO");

            foreach ($_POST as $key => $value)
            {
                $data['value'] = $value;
                $db->where('id='.'\''.$key.'\'')->save($data);
                \Think\Log::write('score:'.$key, "INFO");
            }
        } else {
            //利用page函数。来进行自动的分页
            $data = $db->select();
            $this->data = $data;
            $this->title = "积分项目列表";
            $this->display();

        }

        \Think\Log::write('expert end', "INFO");
    }

    //积分列表
    function scoreTable($start_time = "", $end_time = "", $queryStr = "", $page = 1, $pagesize = 10)
    {


        \Think\Log::write('login scoreTable:', "INFO");

        if (!empty($_POST)) {
            $db = M('Doctor');
            $where = '1';
            $start_time = $start_time." 00:00:00";
            $end_time   = $end_time." 59:59:59";
            $where .= ' and s.recordTime >"'.$start_time.'"'.' and s.recordTime<'.'"'.$end_time.'"'.' and d.realname like '.'"%'.$queryStr.'%"';

            //使用map作为查询条件,混合模式
//        $where['a.realname'] = array('like', '%' . $queryStr . '%');
//        $where['_logic'] = 'or';
//        $map['_complex'] = $where;
//        dump($map);

            //利用page函数。来进行自动的分页
            $data = $db->alias('d')->page($page, $pagesize)
                ->join('__SCORE__ as s ON d.id = s.doctorId')
                ->join('__CARD__ as card ON card.doctorId = d.id')
                ->field('d.id,d.realName,card.cardAsn, card.bankName, card.subbranch, COALESCE(sum(s.value),0) as value')
                ->where($where)
                ->select();

//            \Think\Log::write('login write', 'WARN' . M('Score')->getLastSql());
            $recordnum = count($data);

//            \Think\Log::write('login write', 'WARN' . M('Score')->getLastSql());

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
        }

        $this->title = "医生积分列表";

        $this->display();

        \Think\Log::write('scoreTable end', "INFO");

    }


    /**
     *
     * 导出Excel
     */
    function expUser(){//导出Excel

        $xlsName  = "Excel";
        $xlsCell  = array(
            array('id','医生id'),
            array('realname','银行户名'),
            array('cardasn','卡号'),
            array('bankname','开户银行'),
            array('subbranch','支行'),
            array('value','当月积分'),

        );
        $xlsModel = M('Score');
        $where = '1';
        //判断导出条件，按时间导出
        if($_REQUEST['start_time'] && $_REQUEST['end_time']  ){
            $start_time = $_REQUEST['start_time']." 00:00:00";
            $end_time   = $_REQUEST['end_time']." 59:59:59";
            $where .= ' and recordTime >"'.$start_time.'"'.' and recordTime<'.'"'.$end_time.'"';
        }
        //判断导出条件，按分页导出当前页内容
        $page = false;
        if($page && empty($_REQUEST['start_time'])){
            //导出当前页的内容
//            $count = $xlsModel->where($where)->count();
//            $Page  = $this->Page($count, 15);
//            $xlsData  = $xlsModel->where($where)->Field('id,insert_time,out_trade_no,username,price,objtype')->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        }else{
            //导出所有的内容
            $xlsData  = $xlsModel->alias('s')
                ->join('__CARD__ as card ON card.doctorId = s.doctorId')
                ->join('__DOCTOR__ as a ON a.id = s.doctorId')
                ->field('a.id,a.realName,card.cardAsn, card.bankName, card.subbranch, COALESCE(sum(s.value),0) as value')
                ->where($where)
                ->select();
        }

        \Think\Log::write('login write', 'WARN' . M('Score')->getLastSql());

//        echo M('Score')->getLastSql();

        foreach ($xlsData as $k => $v)
        {
//            $xlsData[$k]['insert_time']= date('Y-m-d H:i:s',$v['insert_time']);
//            $xlsData[$k]['out_trade_no']= ' '.$v['out_trade_no'] ;
//            $xlsData[$k]['objtype']= $v['objtype'] == 1 ?'捐资助学':'不定项捐款';
        }

        $this->exportExcel($xlsName,$xlsCell,$xlsData);

    }

    /**
     * @param $expTitle 名称
     * @param $expCellName 参数
     * @param $expTableData 内容
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function exportExcel($expTitle,$expCellName,$expTableData){

        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");

        $objPHPExcel = new \PHPExcel();

        $cellName = array('A','B','C','D','E','F');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
//        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
//         $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
        }
        // Miscellaneous glyphs, UTF-8
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');//Excel5为xls格式，excel2007为xlsx格式
        $objWriter->save('php://output');
        exit;
    }

}