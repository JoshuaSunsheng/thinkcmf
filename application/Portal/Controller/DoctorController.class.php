<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 16/6/11
 * Time: 下午11:41
 */

namespace Portal\Controller;

use Common\Controller\HomebaseController;
use Portal\Model\AppointmentModel;
use Portal\Model\DoctorModel;


define(CONTROLLER, __CONTROLLER__);

class  DoctorController extends HomebaseController{

    function myStudy(){
        \Think\Log::write('myStudy begin:', "INFO");
        $doctorId = $this->get_doctor_id();
        $doctor = M('Doctor')->find($doctorId);

        if(!empty($_POST)){
            \Think\Log::write('myStudy POST.', "INFO");

            $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率


//            $city = I('post.city')."地区";
            $city = $doctor["city"]."地区";

            \Think\Log::write('chart $drug:'.$city, "INFO");
            $map = null;
            $map['drug'] = array('like',array('%klms%','%zyfsx%'),'OR');
            $map['region'] = array('like',"%".$city."%");

            \Think\Log::write('chart $map:'.$map, "INFO");

            //查找是否已经存在耐药率数据, 同一地区的药品同一时间同时存在
            $data = $DrugResistanceRate->field('year, drug, solerate, mutilrate')->where($map)->order('year')->select();
            $data["count"] = $DrugResistanceRate->where($map)->count();

            \Think\Log::write('chart $data:'.$data, "INFO");

            $this->ajaxReturn($data,"json");
        }
        else{
            $this->assign('doctor',$doctor);
            $this -> display();
        }
    }

    /*
     * 用于分析耐药excel中的单耐药率和多耐药率
     * DrugResistance 分析结果 存储 DrugResistanceRate表中
     * 增量分析数据, 若该耐药率已经存在则跳过计算
     * */
    function generateData(){
        $this -> display();

        \Think\Log::write('upload myStudy:', "INFO");
        $initYear = 2009;
        $thisYear = 2016;
        $drug=array('klms','zyfsx');
//        $drug=array('mdl','klms','amx','qdms','ltl','ysshs','zyfsx');
        $region=array("台州地区","嘉兴地区","杭州地区","温州地区","湖州地区","舟山地区","金华地区","绍兴地区","丽水地区","宁波地区");
//        $region=array("台州地区");


        $doctor = M('Doctor')->where(array("userId"=>session('doctor'))); ;

        $DrugResistance = new \Portal\Model\DrugResistanceModel(); // 实例化 DrugResistance 耐药率数据

        foreach($drug as $d) {
            $drugMap[$d] = array('like','%敏感%');
        }
//      var_dump($drugMap, true);
        $data = null;
        //按照时间,地区,药品来查找
        for ($y=$initYear; $y<=$thisYear; $y++) {
            foreach($region as $r){
                foreach($drug as $d){
                    $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率
                    $soleCount = 0;
                    $map = null;
                    $map['year'] = array('like',"%".$y."%");
                    $map['region'] = array('like',"%".$r."%");
                    $map['drug'] = array('like',"%".$d."%");
                    //查找是否已经存在耐药率数据
                    $data = $DrugResistanceRate->where($map)->find();

                    if(!$data){
//                    if(true){
                        //计算某年某地区某药物耐药率
                        $map[$d] =  array('like',array('%耐药%','%敏感%'),'OR');
                        $data['count']= $DrugResistance->where($map) ->count();
                        $map[$d] = array('like', '耐药');
                        $data['resistance']= $DrugResistance->where($map) ->count();

                        //计算某一年, 某个区域, 呼气检查, 对所有抗生素都不耐药, 和只有对单一抗生素耐药的用户数量
                        $tempmap = $drugMap;
                        $tempmap['year'] = array('like',"%".$y."%");
                        $tempmap['region'] = array('like',"%".$r."%");
                        $tempmap[$d] = array('like',"%".'耐药'."%");
                        $data['soleresistance']= $DrugResistance->where($tempmap) ->count();
                        $data['solerate']= $data['soleresistance'] / $data['count'];

                        $data['year'] = $y."";
                        $data['region'] = $r;
                        $data['drug'] = $d;
                        $DrugResistanceRate->data($data)->add();
                    }

                    $dataD[$d] = $data;

                }
                if(!$data['mutilresistance']){
                    //当多耐药为空时,需要计算多耐药率情况

                    $map = null;
                    $map['year'] = array('like',"%".$y.""."%");
                    $map['region'] = array('like',"%".$r."%");
                    $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率
                    //计算某年某地区所有药品单耐药率和
                    $soleCount = $DrugResistanceRate->where($map)->sum('soleresistance');

                    $tempmap = null;
                    $tempmap = $drugMap;
                    $tempmap['year'] = array('like',"%".$y."%");
                    $tempmap['region'] = array('like',"%".$r."%");
                    //计算某年某地区所有药品敏感人群
                    $noresistanceCount =  $DrugResistance->where($tempmap) ->count();
                    \Think\Log::write('upload myStudy:'.$soleCount, "INFO");
                    \Think\Log::write('upload myStudy:'.$noresistanceCount, "INFO");

                    $data['mutilresistance']= $data['count'] - $soleCount- $noresistanceCount;;
                    $data['mutilrate']= $data['mutilresistance']  / $data['count'];

                    $DrugResistanceRate->where($map)->setField(array('mutilresistance'=> $data['mutilresistance'],'mutilrate'=>$data['mutilrate']));
                }
                $dataRD[$r] = $dataD;
            }
            $dataYRD[$y]=$dataRD;
        }
//        var_dump($dataYRD, true);

        \Think\Log::write('myStudy end.', "INFO");

    }


    function dataImport(){
        \Think\Log::write('dataImport begin:', "INFO");
//        $this -> display();
//        return;
//
//        $token = session('token');
//        if(empty($token)) {
//            redirect(CONTROLLER.'/register', 2, '页面跳转中...');
//        }


        if(!empty($_POST)){
            \Think\Log::write('dataImport begin:'.$_POST, "INFO");
            $Study = new \Portal\Model\CaseModel(); // 实例化 Patient对象

            try{
                if(!$Study->create($_POST, 1)){
                    echo $Study->getError();
                }
                else{
                    $process = 0;
                    if (($_POST['phoneNumber'] != null) && ($_POST['name'] != null) && ($_POST['inclination'] != null)) { //baseInfo 基本信息
                        $process = $process + 20;
                    }
                    if (($_POST['carbonType'] != null) && ($_POST['carbon'] != null) && ($_POST['PPIdxtype'] != null)) { //experiment 实验诊断
                        $process = $process + 25;
                    }
                    if (($_POST['perscription'] != null) && ($_POST['period'] != null)) { //cureMethod 治疗方案
                        $process = $process + 25;
                    }
                    if ($_POST['firstTimeType'] != null && $_POST['firstTimeResult'] != null) { //result 疗效随访
                        $process = $process + 20;
                    }
                    if ($_POST['badResponse'] != null) {             //response 不良反应
                        $process = $process  + 10;
                    }
                    $Study -> process = $process;

                    $Study -> badResponse = implode(',', $_POST['badResponse']);
                    \Think\Log::write('dataImport begin $_POST:'.$_POST, "INFO");

                    if($_POST['id']){
                        if (!$Study->save()) {
                            echo $Study->getError();
                        } else {
                            $this->success('更新成功', 'myStudy');
                        }
                    }
                    else{
                        if (!$Study->add()) {
                            echo $Study->getError();
                        } else {
                            $this->success('新增成功', 'myStudy');
                        }
                    }

                    $this->success('成功', 'myStudy');

                }
            }
            catch(\Exception $e){ //这里的\Exception不加斜杠的话回使用think的Exception类
                $this->error('失败', 'myStudy');
            }
        }
        else{
            \Think\Log::write('caseId begin:'.$_GET['caseId'], "INFO");

            if (isset($_GET['caseId'])) {
                \Think\Log::write('caseId begin:'.$_GET['caseId'], "INFO");

                $Study = new \Portal\Model\CaseModel(); // 实例化 Patient对象


                $this->data = $Study->alias('a')
                    ->where('a.id='.$_GET['caseId'])->find();

//                $badResponse = explode(',', $this->data["badResponse"]);
//                $badResponse = [];
//                foreach($cureTime as $br){
//                    $badResponses[$br["id"]] = 'checked';
//                }
//                $this->badResponses =$badResponses;

                $this->retCode = "00";
                $this->msg = "查找成功";
                $this->caseId = $_GET['caseId'];
                $this -> display();


            }
            else{
                $data["studyNo"] = build_order_no();
                $this->data = $data;
//                $this->studyNo = build_order_no();
                $this -> display();
            }

        }
    }



    function register(){

        \Think\Log::write('register begin:', "INFO");

        //获取session中token
//        $token = session('token');
//        \Think\Log::write('register begin:'.$token, "INFO");

        if(!empty($_POST)){
            $users_model=M("Users");

            $mobile=I('post.phoneNumber');
            if(!$mobile){
                $this->error("手机号不能为空！");
            }

            $where['mobile']=$mobile;

            $users_model=M("Users");
            $result = $users_model->where($where)->count();
            \Think\Log::write('register $result:'.$result, "INFO");

            if($result){
                $this->error("手机号已被注册！");
                //直接通过
                //还需要处理
                //不会发生这种情况
            }else{
                $data=array(
                    'user_login' => '',
                    'user_email' => '',
                    'mobile' =>$mobile,
                    'user_nicename' =>'',
                    'user_pass' => sp_password('111111'),
                    'last_login_ip' => get_client_ip(0,true),
                    'create_time' => date("Y-m-d H:i:s"),
                    'last_login_time' => date("Y-m-d H:i:s"),
                    'user_status' => 1,
                    "user_type"=>DOCTOR,//医生
                    "openid"=>"",//医生
                );

                $rst = $users_model->add($data);
                \Think\Log::write('register $rst:'.$rst, "INFO");

                $this->_to_register($rst, $data);
            }

        }
        else{
            $phoneNumber=I('get.phoneNumber');

            $this->phoneNumber = $phoneNumber;
            $this -> display();
        }

        \Think\Log::write('register end.', "INFO");

    }

    function myPatient($queryStr = '', $page = 1, $pagesize = 5){
        \Think\Log::write('myPatient appointment:', "INFO");

        if($queryStr == "tablelist") {
            $status=APPOINTMENT_ADMIN_PASS ;
        }
        else if($queryStr == "tablepasslist"){
            $status=APPOINTMENT_PASS;
        }
        else{
            $status="";
            \Think\Log::write('myPatient appointment 这里不显示内容', "INFO");
        }
        \Think\Log::write('myPatient appointment:'.$queryStr, "INFO");
        \Think\Log::write('myPatient appointment $status:'.$status, "INFO");

        if($queryStr){
            list($data, $recordnum, $pagenum) = $this->innerAppointment($status, $page, $pagesize);
        }

        \Think\Log::write('myPatient appointment $recordnum:'.$recordnum, "INFO");

        $this->queryStr=$queryStr;
        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "预约信息";

        $this->display();
        \Think\Log::write('myPatient end', "INFO");
    }

    /**
     * 预约信息查询
     * @param $queryStr
     * @param $page
     * @param $pagesize
     * @param $where
     * @param $map
     * @return array
     */
    public function innerAppointment($queryStr, $page, $pagesize)
    {
        \Think\Log::write('innerAppointment page: ' . $page, "INFO");

//        $db=D("Appointment");   //D方法无效
        $db = new AppointmentModel();

        //使用map作为查询条件,混合模式
        $where['patient.realname'] = array('like', '%' . $queryStr . '%');
        $where['doctor.realname'] = array('like', '%' . $queryStr . '%');
        $where['a.status'] = array('like', '%' . $queryStr . '%');   //当myPatient调用时需要区分出审核通过和待审核的患者,该条件不影响原有查询
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
//        dump($map);

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')->page($page, $pagesize)->join('__DOCTOR__ as doctor ON doctor.id = a.doctor_id')
            ->join('__PATIENT__ as patient ON a.patient_id = patient.id')
            ->join('__DICT_NEGATIVE_POSITIVE__ as np ON np.id = patient.checkStatus')
            ->join('__DICT_NOTIFY__ as notify ON notify.id = a.notify')
            ->join('__DICT_STATUS__ as status ON status.id = a.status')
            ->field('a.id, a.begindate, a.enddate, patient.realname as patientname, doctor.realname as doctorname,status.title as status,a.status as statusCode, np.title as checkstatus, notify.title as notify, patient.birthday as birthday')
            ->where($map)
            ->select();
        $recordnum = $db->alias('a')->join('__DOCTOR__ as doctor ON doctor.id = a.doctor_id')
            ->join('__PATIENT__ as patient ON a.patient_id = patient.id')
            ->join('__DICT_NEGATIVE_POSITIVE__ as np ON np.id = patient.checkStatus')
            ->join('__DICT_NOTIFY__ as notify ON notify.id = a.notify')
            ->join('__DICT_STATUS__ as status ON status.id = a.status')
            ->where($map)
            ->count();

        //计算分页
        $pagenum = $recordnum / $pagesize;
        //如果不能整除，则自动加1页
        if ($pagenum % 1 !== 0) {
            \Think\Log::write('login record pagenum: ' . $pagenum, "INFO");
            $pagenum = (int)$pagenum + 1;
        }

        return array($data, $recordnum, $pagenum);
    }


    //审核通过预约
    function passAppointment($id)
    {
        \Think\Log::write('passAppointment record:'.$id, "INFO");
        $db = new AppointmentModel();
        $map['id']=array('in',$id);
        $db->where($map)->setField('status',APPOINTMENT_PASS);
        \Think\Log::write('passAppointment record end', "INFO");
    }

    //审核失败预约
    function cancelAppointment($id)
    {
        \Think\Log::write('cancelAppointment record:'.$id, "INFO");
        $db = new AppointmentModel();
        $map['id']=array('in',$id);

        $db->where($map)->setField('status',APPOINTMENT_FAIL);
        \Think\Log::write('cancelAppointment record end', "INFO");
    }

    /**
     * @param $rst
     * @param $data
     * @param $Doctor
     */
    public function _to_register($rst, $data)
    {
        $Doctor = new \Portal\Model\DoctorModel(); // 实例化 Patient对象

        if ($rst) {
            //注册成功页面跳转
            $data['id'] = $rst;
            session('user', $data);

            $Doctor->create($_POST, 1);
            $Doctor->cureTime = implode(',', $_POST['cureTime']);
            $Doctor->userId = $rst;

            $z = $Doctor->add();
            \Think\Log::write('register $z:' . $z, "INFO");

            if ($z) {
                $data['id'] = $z;
                session('doctor', $data);
                $this->success('新增成功', 'myStudy');
            } else {
                $this->error('新增失败', 'myStudy');
            }
//                    $this->success("注册成功！",__ROOT__."/");

        } else {
            $this->error("注册失败！", U("user/register/index"));
        }
    }



    public function upload(){
        \Think\Log::write('upload record:', "INFO");

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     553145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/'; // 设置附件上传根目录
        $upload->savePath = '/Uploads/Doctor/';
        \Think\Log::write('upload record: $_FILES'.$_FILES, "INFO");


//        while ($elem = each($_FILES)) {
//            \Think\Log::write('upload record: $elem '.$elem['key'], "INFO");
//            \Think\Log::write('upload record: $elem '.$elem['value'], "INFO");
//        }

        if (!is_writable($upload->rootPath)) {
            \Think\Log::write('upload record: is_writable', "INFO");
        }


        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['photoFile']);
        if(!$info) {// 上传错误提示错误信息
            \Think\Log::write('upload record err.'.$upload->getError(), "INFO");

            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            \Think\Log::write('upload record success.'.$info['savepath'].$info['savename'], "INFO");
//            $jobj = new JSONArray();
            $arr = array ('savepath'=>$info['savepath'],'savename'=>$info['savename']);
            \Think\Log::write('upload record success.'.json_encode($arr), "INFO");

            $this->ajaxReturn($arr);
        }
    }

    public function index(){
        $this->display();
    }


    public function doctorInfo(){
        $this->display();
    }

    public function caseSearch($queryStr = "", $page = 1, $pagesize = 3){
        \Think\Log::write('caseSearch table:', "INFO");

        $db = new \Portal\Model\CaseModel();

        //使用map作为查询条件,混合模式 1: 表示未完成, 2:表示已经完成, 0或没有查询条件:表示所有
        if($queryStr == "1"){
            $where['process'] = array('NEQ', '100');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        else if($queryStr == "2"){
            $where['process'] = array('EQ', '100');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        else{
            $map['_complex'] = "1=1";
        }

//        dump($map);

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')->page($page, $pagesize)
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

        \Think\Log::write('caseSearch write', 'WARN' . $data);

        $this->data = $data;
        $this->pagenum = $pagenum;
        $this->page = $page;
        $this->pagesize = $pagesize;
        $this->recordnum = $recordnum;
        $this->title = "病例列表";

        $this->display();
        \Think\Log::write('caseSearch end', "INFO");    }


}