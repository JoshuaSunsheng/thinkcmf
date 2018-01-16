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
use Portal\Model\ScoreItemModel;
use Portal\Model\ScoreModel;
use Portal\Model\DoctorModel;
use Portal\Model\CardModel;
use Common\Model\CommonModel;
use Think\Exception;


define(CONTROLLER, __CONTROLLER__);

class  DoctorController extends HomebaseController{

    function _initialize() {
        parent::_initialize();


        $functionName=I('get.functionName');
        \Think\Log::write('_initialize '.$functionName.ACTION_NAME, "INFO");

        if($functionName != "register" && ACTION_NAME != "appointment"){
            parent::check_login();
        }
        else{
            \Think\Log::write('_initialize register end.'.$functionName.ACTION_NAME, "INFO");
        }

        \Think\Log::write($functionName.ACTION_NAME.' begin:', "INFO");

    }

    function myStudy(){
        \Think\Log::write('myStudy begin:', "INFO");
        $doctorId = $this->get_doctor_id();
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        $doctor = M('Doctor')->find($doctorId);

        if(!empty($_POST)){
            \Think\Log::write('myStudy POST.', "INFO");

            $DrugResistanceRate = new \Portal\Model\DrugResistanceRateModel(); // 实例化 DrugResistanceRate 耐药率

            \Think\Log::write('chart city:'.$doctor["city"], "INFO");

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

            //根除率
            $doctor["totalradication"] =$this->getEradictionRate("", "", $doctor["id"]); //  所有 所有

            //以下为细分
            $doctor["personal3rate"] = $this->getEradictionRate("0", "1", $doctor["id"]); //  三联 个体化
            $doctor["personal4rate"] = $this->getEradictionRate("1", "1", $doctor["id"]); // 四联 个体化

            $doctor["exp3rate"] = $this->getEradictionRate("0", "0", $doctor["id"]); // 三联 经验
            $doctor["exp4rate"] = $this->getEradictionRate("1", "0", $doctor["id"]); // 四联 经验
            $otherrate1 = $this->getEradictionRate("2", "1", $doctor["id"]);
            $otherrate2 = $this->getEradictionRate("2", "0", $doctor["id"]);
            $doctor["otherrate"] = $this->getEradictionRate("2", "1", $doctor["id"]) + $this->getEradictionRate("2", "0", $doctor["id"]); // 其它 个体化 + 经验
            \Think\Log::write('totalradiction $data:'.$this->getEradictionRate("2", "1", $doctor["id"]), "INFO");
            \Think\Log::write('totalradiction $data:'.$this->getEradictionRate("2", "0", $doctor["id"]), "INFO");
            \Think\Log::write('totalradiction $data:'.$doctor["otherrate"], "INFO");


            $infoController = new InfoController();
            $data = $infoController->_getDoctorByLBS($doctor["x"], $doctor["y"], 10);

            $doctorList = $data["doctorList"];
            \Think\Log::write('totalradiction $data:'.$data["info"], "INFO");

            $this->assign('doctor',$doctor);
            $this->assign('doctorList',$doctorList);
            \Think\Log::write('totalradiction $data:'.$doctorList, "INFO");

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
                    $Study -> doctorId = $this->get_doctor_id();

                    if (!$_POST['badResponse'])
                        $Study->badResponse = implode(',', $_POST['badResponse']);
                    else
                        $Study->badResponse = null;
                    \Think\Log::write('dataImport begin $_POST:'.$_POST, "INFO");
                    \Think\Log::write('dataImport end $_POST:'.$_POST, "INFO");

                    $caseId = null;

                    if($_POST['id']){
                        \Think\Log::write('开始更新信息:', "INFO");

                        $caseId = $_POST['id'];
                        if (!$Study->save()) {
                            \Think\Log::write('更新信息失败:', "INFO");

                            echo $Study->getError();
                        }
                        \Think\Log::write('成功更新信息:', "INFO");

                    }
                    else{
                        $caseId = $Study->add();
                        if (!$caseId) {
                            \Think\Log::write('录入信息失败:', "INFO");

                            echo $Study->getError();
                        }
                        \Think\Log::write('成功录入信息:', "INFO");

                    }

                    //判断此次录入信息, 是否可以增加积分
                    $this->addDoctorScore($this->get_doctor_id(), $caseId);

                    $this->success('成功', 'index');

                }
            }
            catch(\Exception $e){ //这里的\Exception不加斜杠的话回使用think的Exception类
                \Think\Log::write('录入信息失败:', "INFO");
                \Think\Log::write('录入信息失败:'.$e, "INFO");

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

    //增加医生录入信息积分
    function addDoctorScore($doctorId, $caseId){

        \Think\Log::write('addDoctorScore begin: $doctorId: '.$doctorId."; ".$caseId, "INFO");

        $study = M("Case") ->find($caseId);

        //众包端-数据录入-基本信息-患者HP根除治疗意愿。医生将基本信息完成输入可获得积分
        if($study["inclination"] != null ){
            $infoController = new InfoController();
            $infoController->addScoreRecord($doctorId, 1, $caseId);
        }

        //完成实验诊断-HP呼气实验数据录入
        \Think\Log::write('addDoctorScore begin: : '.$study["carbontype"]."; ".$study["carbon"], "INFO");

        if($study["carbontype"] != null && $study["carbon"] != null){
            $infoController = new InfoController();
            $infoController->addScoreRecord($doctorId, 2, $caseId);
        }

        //完成疗效随访
        if($study["firsttimetype"] != null && $study["firsttimeresult"] != null){
            $infoController = new InfoController();
            $infoController->addScoreRecord($doctorId, 3, $caseId);
        }

        //众包端-数据录入-实验诊断-HP细菌培养（药敏实验）。
        if($study["germ"] != null && $study["clarithromycin"] != null && $study["levofloxacin"] != null && $study[" amoxycillin"] != null && $study["furazolidone"] != null && $study["tetracycline"] != null && $study["metronidazole"] != null){
            $infoController = new InfoController();
            $infoController->addScoreRecord($doctorId, 4, $caseId);
        }
        //完成众包端-数据录入的全部数据录入
        if($study["process"] == 100) {
            $infoController = new InfoController();
            $infoController->addScoreRecord($doctorId, 5, $caseId);
        }

    }

    /*已经转移到InfoController.class.php
     * 增加积分记录 和 医生积分
     * @doctorId
     * @scoreItemId
     * @caseId
     * return $rst
     * */
    function addScoreRecord($doctorId, $scoreItemId, $caseId, $description = ""){
//$User->where('id=5')->setInc('score',3);
        \Think\Log::write('addScoreRecord begin: $doctorId: '.$doctorId.";: $scoreItemId ".$caseId, "INFO");

        $scoreItem=M("ScoreItem")->where("id=".$scoreItemId)->find();

        $score=M("Score");
        $map = null;
        $map['doctorId'] = $doctorId;
        $map['scoreItemId'] = $scoreItemId;
        $map['caseId'] = $caseId;
        $map['description'] = $description;
        //查找是否已经存在耐药率数据
        $data = $score->where($map)->find();

        if(!$data){

            $data=array(
                'title' => $scoreItem['title'],
                'value' => $scoreItem['value'],
                'doctorId' => $doctorId,
                'scoreItemId' => $scoreItemId,
                'caseId' => $caseId,
            );
            $rst = $score->add($data);
            if($rst){
                //同步增加医生积分
                $doctor=M("Doctor");
                $doctor->where('id='.$doctorId)->setInc('score',$scoreItem['value']);
            }

            \Think\Log::write('addScoreRecord $rst:'.$rst, "INFO");
            return $rst;
        }
        else{
            return $data;
        }
    }



    function register(){

        \Think\Log::write('register begin:', "INFO");

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
        //$where['patient.realname'] = array('like', '%' . $queryStr . '%');
        $where['doctor.realname'] = array('like', '%' . $queryStr . '%');
        $where['a.status'] = array('like', '%' . $queryStr . '%');   //当myPatient调用时需要区分出审核通过和待审核的患者,该条件不影响原有查询
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
//        dump($map);

        //利用page函数。来进行自动的分页
        $data = $db->alias('a')->page($page, $pagesize)->join('__DOCTOR__ as doctor ON doctor.id = a.doctor_id')
            //->join('__PATIENT__ as patient ON a.patient_id = patient.id')
            //->join('__DICT_NEGATIVE_POSITIVE__ as np ON np.id = patient.checkStatus')
            ->join('__DICT_NOTIFY__ as notify ON notify.id = a.notify')
            ->join('__DICT_STATUS__ as status ON status.id = a.status')
            ->field('a.id, a.patientname as patientname, doctor.realname as doctorname,status.title as status,a.status as statusCode, a.curetime, a.flag, a.mobile')
            ->where($map)
            ->select();


        \Think\Log::write('updateCard :' . $db->_sql(), "INFO");


        $recordnum = $db->alias('a')->join('__DOCTOR__ as doctor ON doctor.id = a.doctor_id')
            //->join('__PATIENT__ as patient ON a.patient_id = patient.id')
            //->join('__DICT_NEGATIVE_POSITIVE__ as np ON np.id = patient.checkStatus')
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
        $this->_updateAppointment($id, APPOINTMENT_PASS, PASS_APPOINTMENT_NOTIFY);

        \Think\Log::write('passAppointment record end', "INFO");
    }

    //审核失败预约
    function cancelAppointment($id)
    {
        \Think\Log::write('cancelAppointment record:' . $id, "INFO");

        $this->_updateAppointment($id, APPOINTMENT_FAIL, FAIL_APPOINTMENT_NOTIFY);
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

    /**医生更新自身信息
     * @param $rst
     * @param $data
     * @param $Doctor
     */
    public function updateDoctor()
    {
        $Doctor = new \Portal\Model\DoctorModel(); // 实例化 Patient对象

        if(!$Doctor->create($_POST, 1)){
            echo $Doctor->getError();
        }
        else{
            if($_POST['id']){
                $Doctor->cureTime = implode(',', $_POST['cureTime']);

                if (!$Doctor->save()) {
                    echo $Doctor->getError();
                }
            }
        }
    }

    /**医生更新银行卡信息
     * @param $rst
     * @param $data
     * @param $Doctor
     */
    public function updateCard()
    {
        \Think\Log::write('updateCard :', "INFO");

        $card = new \Portal\Model\CardModel(); // 实例化 card

        $isInsert = $card->create($_POST, CommonModel::MODEL_BOTH);

        \Think\Log::write('updateCard :' . D("Card")->_sql(), "INFO");
        if (!$isInsert) {
            echo $card->getError();
        } else {
            \Think\Log::write('updateCard :' . $_POST['id'], "INFO");

            if ($_POST['id']) {
                if (!$card->save()) {
                    echo $card->getError();
                }
            } else {
                if (!$card->add()) {
                    echo $card->getError();
                }
            }
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
        $doctorId = $this->get_doctor_id();
        $doctor = M('Doctor')->find($doctorId);
        $this->data = $doctor;

        $this->display();
    }

    //审核通过预约
    function getDoctor()
    {
        $doctorId = $this->get_doctor_id();
        $doctor = M('Doctor')->find($doctorId);
        //$this->user_login($doctor['phonenumber']);

//        define('DOCTOR_SUBMIT', 0);
//        define('DOCTOR_FAIL', 1);
//        define('DOCTOR_PASS', 2);
        $ret['rescode'] = "00";
        $ret['msg'] = "成功";
        $ret['status'] = $doctor['status'];
        $ret['id'] = $doctor['id'];
        if($doctor['status'] == 0){
            $ret['rescode'] = "01";
            $ret['msg'] = "审批中";
        }
        else if($doctor['status'] == 1){
            $ret['rescode'] = "02";
            $ret['msg'] = "审批失败";
        }

        \Think\Log::write('getDoctor record end', "INFO");
        $this->ajaxReturn($ret);

    }


    public function doctorInfo(){
        $doctorId = $this->get_doctor_id();
//        var_dump(session('user.id'), true);
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        $doctor = M('Doctor')->find($doctorId);
//        $doctor = M('Doctor')->find(53);

        \Think\Log::write('doctorInfo:'.$doctor['realname'], "INFO");

        $db = new ScoreItemModel();
        $scoreItem = $db->select();
        $this->score = $scoreItem;

        $db = new ScoreModel();
        $scoreList = $db->where('doctorId='.$doctor['id'])->select();
        $this->scoreList = $scoreList;

        $db = new CardModel();
        $bankCard = $db->where('doctorId='.$doctor['id'])->find();
        $this->bankCard = $bankCard;

        $this->data = $doctor;
        $this->title = "医生账号";
        $this->display();
    }

    public function caseSearch($queryStr = "", $page = 1, $pagesize = 3){
        \Think\Log::write('caseSearch table:', "INFO");

        $db = new \Portal\Model\CaseModel();

        //使用map作为查询条件,混合模式 1: 表示未完成, 2:表示已经完成, 0或没有查询条件:表示所有
        if($queryStr == "1"){
            $where['process'] = array('NEQ', '100');
            $where['_logic'] = 'and';
            $where['doctorId'] = $this->get_doctor_id() ;
            $map['_complex'] = $where;
        }
        else if($queryStr == "2"){
            $where['process'] = array('EQ', '100');
            $where['_logic'] = 'and';
            $where['doctorId'] = $this->get_doctor_id() ;
            $map['_complex'] = $where;
        }
        else{
            $where['doctorId'] = $this->get_doctor_id() ;
            $map['_complex'] = $where;
        }

//        dump($map);

        //利用page函数。来进行自动的分页
        $data = $db->page($page, $pagesize)
            ->where($map)
            ->select();
        $recordnum = $db->page($page, $pagesize)
            ->where($map)
            ->count();
        \Think\Log::write('login record $recordnum: '.$recordnum, "INFO");


        $finishnum = $db->where('process like "100" and doctorId='.$this->get_doctor_id())
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
        $this->recordnum = $recordnum;  //只有第一次查询所有的数据, 将结果显示为页面
        $this->finishnum = $finishnum;  //只有第一次查询所有的数据, 将结果显示为页面
        $this->title = "病例列表";

        $this->display();
        \Think\Log::write('caseSearch end', "INFO");
    }


    /**增加每日第一次登录积分
     * @return 增加成功或失败
     */
    public function addLoginScore(){
        \Think\Log::write('addLoginScore', "INFO");

        $doctorId = $this->get_doctor_id() ;
        $caseId = 0;         //每改加分项,与caseId无关,默认为0
        $scoreItemId = 6;         //每天第一次输入账号密码登入加分
        $infoController = new InfoController();
        $infoController->addScoreRecord($doctorId, $scoreItemId, $caseId, "");
//        function addScoreRecord($doctorId, $scoreItemId, $caseId, $description = ""){

        }

    /**
     * @param $perscription 0为三联 1为四联
     * @param $germ 1 阳性为个体化治疗, 0 阴性为经验治疗
     * @return float 根除率
     */
    public function getEradictionRate( $perscription, $germ, $doctor_id)
    {
        \Think\Log::write('getEradictionRate $perscription, $germ:'.$perscription.", ".$germ, "INFO");

        //根除率
        $Study = new \Portal\Model\CaseModel(); // 实例化 Patient对象
        $map = null;
        $map['process'] = array('like',"%100%");         //治疗结束
        $map['carbon'] = array('like',"%1%");           //呼气结果: 1阳性, 0阴性
        $map['doctorId'] = $doctor_id;          //呼气结果: 1阳性, 0阴性

        if($perscription != "") $map['perscription'] = array('like', "%".$perscription."%");           //三联, 四联
        if($germ != "") $map['germ'] = array('like',"%".$germ."%");           //呼气结果为阳性, 个体化治疗方案

        $total = $Study->where($map)->count();
        \Think\Log::write('getEradictionRate $total:'.$total, "INFO");
        $map['firstTimeResult'] = array('like', "%0%");  //第一次随访 阴性为已经根除
        $count = $Study->where($map)->count();
        \Think\Log::write('getEradictionRate $count:'.$count, "INFO");;
        if(!$total || $total == 0 || $total == "0") return "暂无";
        else{
            \Think\Log::write('getEradictionRate $total:'.$total, "INFO");;

        }
        return $count * 100 / $total;

    }

    /**
     * @param $id
     * 处理预约
     */
    public function _updateAppointment($id, $status, $templateId)
    {
        \Think\Log::write('cancelAppointment record:' . $id, "INFO");
        $appointment = M("Appointment");
        $appointment->status = $status; //预约状态
        $doctor = M("Doctor")->find($appointment['doctor_id']);
        $infoController = new InfoController();
        $ret = $infoController->sendNotify($appointment['mobile'], $templateId, $doctor['realName'] . "," . $appointment['cureTime']);
        if ($ret['errCode'] == '00') {
            $appointment->notify = 1; //通知患者成功
        }
        $appointment->save(); // 保存当前数据对象

        \Think\Log::write('cancelAppointment record end', "INFO");
    }




//    /**
//     * @param $options
//     * @param $authnum
//     * @throws \Org\Com\Exception
//     */
//    public function verifyCode(){
//
//
//        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
//        $phoneNumber = $_POST['to'];
//        $code = $_POST['msgCode'];
//
//        $Msgcode = M("Msgcode");
//
//        //echo $Msgcode;
//        $data = $Msgcode->where('status=0 AND phoneNumber=%s', $phoneNumber)->order('CREATETIME desc')->limit(1)->find();
//
//        //echo $data;
////        var_dump($data);
//        $currentTime = date ( "Y-m-d H:i:s");
//
//        $ret['rescode'] = "01";
//
//        //验证码失效
//        if(strtotime($currentTime)>strtotime($data['validtime'])){
//            $ret['msg'] = "验证码失效";
//        }
//        else{
//            if(trim($data['code']) == trim($code)){
//                $ret['rescode'] = "00";
//                $ret['msg'] = "验证通过";
//            }
//            else{
//                $ret['msg'] = "验证码错误";
//            }
//        }
//
//        $token = session('token'); //保存授权信息
//        $token['phoneNumber'] = $phoneNumber;
//
//        session('token', $token); //保存授权信息
//
//        if($ret['rescode'] == "00"){
//            \Think\Log::write('login success: ', "INFO");
//
//            $doctorId = $this->get_doctor_id() ;
//            \Think\Log::write('login success: doctorId: '.$doctorId, "INFO");
//
//            $this->addLoginScore();
//        }
//        else{
//            \Think\Log::write('login fail: ', "INFO");
//
//        }
//
//        $this->ajaxReturn($ret);
//
//    }

}