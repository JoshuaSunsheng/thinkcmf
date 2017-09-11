<?php
/**
 * Created by PhpStorm.
 * User: susunsheng
 * Date: 2017/7/10
 * Time: 下午8:49
 */

namespace Portal\Controller;
use Think\Controller\RestController;
use Portal\Model\DoctorModel;
use Portal\Model\AppointmentModel;


Class InfoController extends RestController {
    protected $allowMethod    = array('get','post','put'); // REST允许的请求类型列表
    protected $allowType      = array('html','xml','json'); // REST允许请求的资源类型列表

    public function login()
    {
        switch ($this->_method)
        {
            case 'get': // get请求处理代码
                if ($this->_type == 'html'){
                }elseif($this->_type == 'xml'){
                }
                $this -> doctorInfoById();
                break;
            case 'put': // put请求处理代码
                break;
            case 'post': // post请求处理代码
                $name['name']=I('post.name');
                $this->response($name,'json');
                break;
        }
    }

    Public function read_get_xml(){
        $doctorId = 12345;
        \Think\Log::write('chart read_get_xml:'.$doctorId, "INFO");
        $data["doctorId"]=$doctorId;
        $this->response($data,'json');
        // 输出id为1的Info的XML数据
    }

    /*
     * 获取医生信息
     * @doctorId 医生用户id
     * return 医生信息
     * curl -d "doctorId=53" "http://www.thinkcmf.su:8888/Info/getDoctorInfoById"
     * curl -d "doctorId=53" "http://www.hatbrand.cn/thinkcmf/Info/getDoctorInfoById"
     *
     * */
    public function getDoctorInfoById(){

        $doctorId = $_REQUEST['doctorId'];
        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
        }
        else{
            $doctor = M('Doctor')->field('docfile,idnofile',true)->find($doctorId);
            \Think\Log::write('doctorInfo:'.$doctor['realname'], "INFO");
            if(!$doctor){
                $data["errCodeDes"]="未查询到相关信息";
                $data["errCode"]="01";
            }
            else{
                $doctor.
                $data["doctor"]=$doctor;
                $data["errCodeDes"]="查询成功";
                $data["errCode"]="00";
            }
        }
        $this->response($data,'json');

    }

    /*
     * 获取医生就诊时间信息
     * @doctorId 医生用户id
     * return 医生信息
     * curl -d "doctorId=53" "http://www.thinkcmf.su:8888/Info/getDoctorCureTime"
     * curl -d "doctorId=53" "http://www.hatbrand.cn/thinkcmf/Info/getDoctorCureTime"
     *
     * */
    public function getDoctorCureTime(){

        $doctorId = $_REQUEST['doctorId'];
        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
        }
        else{
            $doctor = M('Doctor')->find($doctorId);
            $cureTime = $doctor["curetime"];

            if($cureTime == "0" || $cureTime){
                $data["doctorId"]=$doctorId;
                $data["cureTime"]=$cureTime;
                $data["errCodeDes"]="查询成功";
                $data["errCode"]="00";
            }
            else{
                $data["errCodeDes"]="未查询到相关信息";
                $data["errCode"]="01";
            }
        }
        $this->response($data,'json');

    }

    /*
     * 获取医生预约信息
     * @doctorId 医生id
     * @start 默认为0
     * @pageSize 列表数量

     * return 预约信息列表
     *  curl -d "doctorId=11&start=2&pageSize=3&date=2017-07-31" "http://www.thinkcmf.su:8888/Info/getDoctorAppointments"
     *  curl -d "doctorId=11&start=2&pageSize=3&date=2017-07-31" "http://www.hatbrand.cn/thinkcmf/Info/getDoctorAppointments"
     * */
    public function getDoctorAppointments(){

        $doctorId = $_REQUEST['doctorId'];
        $date = $_REQUEST['date'];
        $start = $_REQUEST['start']?$_REQUEST['start']:0;
        $pageSize = $_REQUEST['pageSize']?$_REQUEST['pageSize']:5;

        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
            $data["errCode"]="03";

        }
        else if(!$date){
            $data["info"]="时间不能为空";
            $data["errCode"]="04";
        }
        else{
//            if(strtotime($currentTime)>strtotime($data['validtime']))
            $currentTime = date ( "Y-m-d", strtotime($date));

            $map['cureTime'] = array('eq',$currentTime);
            $map['doctor_id'] = $doctorId;
            $appointmentList = M('Appointment')->where($map)->limit($start,$pageSize)->select();
            $total = M('Appointment')->where($map)->count();
            if(!$appointmentList){
                $data["info"]="未查询到相关信息";
                $data["errCode"]="01";
            }
            else{
                $data["appointmentList"]=$appointmentList;
                $data["info"]="查询成功";
                $data["total"]=$total;
                $data["errCode"]="00";
            }
        }
        $this->response($data,'json');

    }


    /*
     * check医生预约是否已满
     * @doctorId 医生id
     * @date 日期
     * return 预约信息列表
     *  curl -d "doctorId=11&date=2017-09-04&flag=0" "http://www.thinkcmf.su:8888/Info/checkDoctorAppointment"
     *  curl -d "doctorId=11&date=2017-09-25&flag=0" "http://www.hatbrand.cn/thinkcmf/Info/checkDoctorAppointment"
     * */
    public function checkDoctorAppointment(){

        $doctorId = $_REQUEST['doctorId'];
        $date = $_REQUEST['date'];
        $flag = $_REQUEST['flag'];

        if($flag != '0' && $flag != '1'){ //如果flag送错了, 默认为上午
            $flag = '0';
        }

        $currentTime = date("Y-m-d");

        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
            $data["errCode"]="02";

        }
        else if(!$date){
            $data["info"]="时间不能为空";
            $data["errCode"]="03";
        }
        else if(strtotime($date) < strtotime($currentTime)){
            $data["info"]="查询日期不能早于今天";
            $data["errCode"]="04";
        }
        else{
//            if(strtotime($currentTime)>strtotime($data['validtime']))
            $doctor = M('Doctor')->find($doctorId);

            $number_wk = date("w", strtotime($date));

            $cureTimeArray = $doctor['curetime'];
            $needle = (String)($number_wk * 2 - 2 + $flag);
            $pos = strpos($cureTimeArray, $needle);
            if ($pos === false) {
                $data["info"] = "非医生可预约日期范围";
                $data["cureTime"] = $doctor['curetime'];
                $data["isFull"] = true;
                $data["date"] = $date;
                $data["errCode"] = "01";
            } else {
                $map['cureTime'] = array('eq',$date);
                $map['doctor_id'] = $doctorId;
                $total = M('Appointment')->where($map)->count();
                if($total >= $doctor["maxpatient"]){
                    $data["isFull"]=true;
                    $data["info"]="查询成功";
                    $data["errCode"]="00";
                }
                else{
                    $data["isFull"]=false;
                    $data["info"]="查询成功";
                    $data["errCode"]="00";
                }

            }

        }
        $this->response($data,'json');

    }

    /*
     * 获取医生指定日期预约信息
     * @doctorId 医生id
     * @start 默认为0
     * @pageSize 列表数量

     * return 预约信息列表
     *  curl -d "doctorId=11&start=2&pageSize=3&start_date=2017-07-30&end_date=2017-08-01" "http://www.thinkcmf.su:8888/Info/getAppointments"
     *  curl -d "doctorId=11&start=2&pageSize=3&start_date=2017-07-30&end_date=2017-08-01" "http://www.hatbrand.cn/thinkcmf/Info/getAppointments"
     * */
    public function getAppointments(){

        $doctorId = $_REQUEST['doctorId'];
        $start_time = $_REQUEST['start_date'];
        $end_time = $_REQUEST['end_date'];

        $data = $this->_getAppointments($doctorId, $start_time, $end_time);
        $this->response($data,'json');

    }

    /*
     * 获取医生未来三个月预约信息
     * @doctorId 医生id
     * @start 默认为0
     * @pageSize 列表数量

     * return 预约信息列表
     *  curl -d "doctorId=53" "http://www.thinkcmf.su:8888/Info/getAppointment3month"
     *  curl -d "doctorId=11" "http://www.hatbrand.cn/thinkcmf/Info/getAppointment3month"
     * */
    public function getAppointment3month(){

        $doctorId = $_REQUEST['doctorId'];
        $start_time = date("Y-m-d", strtotime("now"));
        $end_time =  date("Y-m-d", strtotime("+3 month"));


        $data = $this->_getAppointments($doctorId, $start_time, $end_time);
        $this->response($data,'json');

    }

    /*
     * 新增预约
     * @doctorId 医生id
     * @patientId 患者id
     * @cureTime 预约时间
     * return 预约结果
     *  curl -d "doctor_id=53&patient_id=53&patientName=李白&cureTime=2017-09-05&flag=0&mobile=18555555555" "http://www.thinkcmf.su:8888/Info/addAppointment"
     *  curl -d "doctor_id=53&patient_id=53&patientName=李白&cureTime=2017-09-18&flag=1&mobile=18555555555" "http://www.hatbrand.cn/thinkcmf/Info/addAppointment"
     * */
    public function addAppointment(){

        $doctorId = $_REQUEST['doctor_id'];
        $patientId = $_REQUEST['patient_id'];
        $patientName = $_REQUEST['patientName'];
        $cureTime = $_REQUEST['cureTime'];
        $mobile = $_REQUEST['mobile'];
        $flag = $_REQUEST['flag'];
        \Think\Log::write("addAppointment, doctorId: $doctorId patientId: $patientName cureTime: $cureTime", "INFO");

        $data=null;
        $currentTime = date ( "Y-m-d");
        \Think\Log::write("addAppointment, oneWeek:".$currentTime, "INFO");

        if(!$doctorId){
            $data["errCode"]="03";

            $data["info"]="医生id不能为空";
        }
        else if(!$patientName || !$patientId){
            $data["errCode"]="04";

            $data["info"]="患者姓名或患者id不能为空";
        }
        else if(is_null($cureTime)){
            $data["errCode"]="05";

            $data["info"]="就诊时间不能为空";
        }
        else if(is_null($flag)){
            $data["errCode"]="06";

            $data["info"]="就诊上下午时间不能为空";
        }
        else if(!$mobile){
            $data["errCode"]="07";

            $data["info"]="患者手机号不能为空";
        }
        else if(strtotime($cureTime) < strtotime($currentTime)){
            $data["info"]="就诊时间不能小于今天";
            $data["cureTime"]=$cureTime;
            $data["errCode"]="08";
        }
        else{
            $doctor = M('Doctor')->find($doctorId);
                    //           0,1   2,3   4,5    6,7     8,9
            //$weekArr = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            $number_wk = date("w", strtotime($cureTime));
            $cureTimeArray = $doctor['curetime'];
            $needle = (String)($number_wk * 2 - 2 + flag);
            $pos = strpos($cureTimeArray, $needle);
            if($pos === false){
                $data["info"]="就诊时间不在可预约范围";
                $data["cureTimeArray"]=$cureTimeArray;
                $data["errCode"]="09";
            }
            else{
                $total = M('Appointment')->where("doctor_id=$doctorId and cureTime='$cureTime'")->count();
                \Think\Log::write("addAppointment, doctorId:".$doctor["maxpatient"], "INFO");

                if($total<$doctor["maxpatient"]){
                    M('Appointment')->create($_POST,1);
                    $appointmentId = "";
                    try {
                        $appointmentId = M('Appointment')->add();
                        \Think\Log::write("addAppointment, appointmentId: $appointmentId", "INFO");
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        \Think\Log::write("addAppointment, error: $e->getMessage()", "INFO");
                        $data["errCode"]="01";

                        $data["info"]="提交失败";
//                $data["e"]=$e->getMessage();
                    }

                    if(!$appointmentId){
                        $data["errCode"]="01";

                        $data["info"]="提交失败";
                    }
                    else{
                        \Think\Log::write("addAppointment, appointmentId: $appointmentId", "INFO");

                        $data["appointmentId"]=$appointmentId;
                        $data["maxPatient"]=$doctor["maxpatient"];
                        $data["hasAppointed"]=$total;

                        $data["info"] = "提交成功";
                        $data["errCode"] = "00";
                    }
                } else {
                    $data["info"] = "超出医生可预约病人数";
                    $data["maxPatient"] = $doctor["maxpatient"];
                    $data["hasAppointed"] = $total;
                    $data["errCode"] = "02";
                }
            }
        }
        $this->response($data,'json');

    }


    /*
    * 新增积分
    * @doctorId 医生id
    * @patientId 患者id
    * @cureTime 预约时间
    * @scoreItemId 预约时间
    * return 预约结果
    *  curl -d "doctor_id=53&description=李白&scoreItemId=7" "http://www.thinkcmf.su:8888/Info/addScore"
    *  curl -d "doctor_id=53&description=李白&scoreItemId=7" "http://www.hatbrand.cn/thinkcmf/Info/addScore"
    * */
    public function addScore(){

        $doctorId = $_REQUEST['doctor_id'];
        $patientInfo = $_REQUEST['description'];
        $scoreItemId = $_REQUEST['scoreItemId'];
        \Think\Log::write("addAppointment, doctorId: $doctorId patientId: $patientInfo cureTime: $scoreItemId", "INFO");

        $data=null;

        if(!$doctorId){
            $data["errCode"]="03";

            $data["info"]="医生id不能为空";
        }
        else if(!$patientInfo){
            $data["errCode"]="04";

            $data["info"]="患者信息不能为空";
        }
        else if(is_null($scoreItemId)){
            $data["errCode"]="05";

            $data["info"]="增加积分类型不能为空";
        } else {
            $scoreItem = M('ScoreItem')->find($scoreItemId);
            //           0,1   2,3   4,5    6,7     8,9
            //$weekArr = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            if (!$scoreItem) {
                $data["errCode"] = "02";
                $data["info"] = "增加积分类型不存在";
            } else {
                $rst = $this->addScoreRecord($doctorId, $scoreItemId, 0, $patientInfo);//无caseId, 默认为0
                if(!rst){
                    $data["info"] = "增加积分失败";
                    $data["errCode"] = "01";
                }else{
                    $data["info"] = "增加积分成功";
                    $data["errCode"] = "00";
                }
            }
        }

        $this->response($data,'json');

    }


    /*
    * 增加积分记录 和 医生积分
    * @doctorId
    * @scoreItemId
    * @caseId
    * return $rst
    * */
    function addScoreRecord($doctorId, $scoreItemId, $caseId, $description = ""){
//$User->where('id=5')->setInc('score',3);
        \Think\Log::write('addScoreRecord begin: $doctorId: '.$doctorId.";scoreItemId: $scoreItemId, caseId: ".$caseId, "INFO");

        $scoreItem=M("ScoreItem")->where("id=".$scoreItemId)->find();

        $score=M("Score");
        $map = null;
        $map['doctorId'] = $doctorId;
        $map['scoreItemId'] = $scoreItemId;
        $map['caseId'] = $caseId;
        //查找是否已经存在数据
        if(strpos("1,2,3,4,5", (String)$scoreItemId) !== false){//相同的病例只能增加一次积分
            $data = $score->where($map)->find();
        }
        else if(strpos("6", (String)$scoreItemId) !== false){ //统计今天是否有增加过第一次登录积分
            $data = $score->where($map)->find();
            \Think\Log::write('addScoreRecord recordtime:' .$data['recordtime'], "INFO");
            \Think\Log::write('addScoreRecord recordTime:' .strtotime($data['recordTime']), "INFO");

            $timeToday = strtotime(date("Y-m-d",time()));//今天0点的时间点
            $timeTomorrow = $timeToday + 3600*24;//第二天
            if($timeToday<=strtotime($data['recordtime']) &&  strtotime($data['recordtime']) < $timeTomorrow) {
                \Think\Log::write('addScoreRecord recordTime:' .strtotime($data['recordtime']), "INFO");
            }
            else{
                \Think\Log::write('addScoreRecord recordtime:' .strtotime($data['recordtime']), "INFO");

                $data = null;
            }
            \Think\Log::write('addScoreRecord doctorId:' .M('Score')->getLastSql(), "INFO");
        }
        else{
            $map['description'] = $description;

            $data = null;
        }

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


    /*
     * 获取预约信息
     * @appointmentId 预约id
     * return 预约信息
     * curl -d "appointmentId=5" "http://www.thinkcmf.su:8888/Info/getAppointmentInfoById"
     * curl -d "appointmentId=5" "http://www.hatbrand.cn/thinkcmf/Info/getAppointmentInfoById"
     * */
    public function getAppointmentInfoById(){

        $appointmentId = $_REQUEST['appointmentId'];
        $data=null;
        \Think\Log::write('chart doctorId:'.$appointmentId, "INFO");

        if(!$appointmentId){
            $data["info"]="预约id不能为空";
        }
        else{
            $appointment = M('Appointment')->find($appointmentId);
            if(!$appointment){
                $data["info"]="未查询到相关信息";
                $data["errCode"]="01";
            }
            else{
                $data["appointment"]=$appointment;
                $data["info"]="查询成功";
                $data["errCode"]="00";
            }
        }
        $this->response($data,'json');

    }

    /*
     * 通过位置信息获取医生列表
     * @x x坐标
     * @y y坐标
     * @pageSize 列表数量
     * return 预约信息
     * curl -d "x=53&y=44&pageSize=5" "http://www.thinkcmf.su:8888/Info/getDoctorByLBS"
     * curl -d "x=53&y=44&pageSize=5" "http://www.hatbrand.cn/thinkcmf/Info/getDoctorByLBS"
     * */
    public function getDoctorByLBS(){

        $x = $_REQUEST['x'];
        $y = $_REQUEST['y'];
        $pageSize = $_REQUEST['pageSize']?$_REQUEST['pageSize']:5;


        $data = $this->_getDoctorByLBS($x, $y, $pageSize);

        $this->response($data, 'json');


    }

    /*
         * 发送模板短信
         * @appointmentId 预约id
         * return 预约信息
         * curl -d "phoneNumber=13764610737&templateId=25567&param=123456" "http://www.thinkcmf.su:8888/Info/sendSMS"
         * curl -d "phoneNumber=13764610737&templateId=25567&param=123456" "http://www.hatbrand.cn/thinkcmf/Info/sendSMS"
         * */
    public function sendNotify($to, $templateId, $param){

//        $param = $_REQUEST['param'];
//        $to = $_REQUEST['phoneNumber'];
//        $templateId = $_REQUEST['templateId'];
        $data=null;
        \Think\Log::write('sendNotify:'.$param, "INFO");

        if (!$to) {
            $data["info"] = "手机号码不能为空";
        } else if (!$templateId) {
            $data["info"] = "模板id不能为空";
        }
        else{
            //初始化必填
            $options['accountsid']=UCPASS_ACCOUNTSID; //填写自己的
            $options['token']=UCPASS_TOKEN; //填写自己的
            //初始化 $options必填
            $ucpass = new \Org\Com\Ucpaas($options);

            $arr=$ucpass->templateSMS(UCPASS_APPID,$to,$templateId,$param);
            \Think\Log::write('send:'.$arr, "INFO");
            if (substr($arr, 21, 6) == 000000) {
                \Think\Log::write('send ' . $to . '验证码:' . $param, "INFO");
                $data["info"] = "短信发送成功";
                $data["errCode"] = "00";

            } else {
                //如果不成功
//                echo "短信验证码发送失败，请联系客服";
                $data["info"] = "短信发送失败";
                $data["errCode"] = "01";
            }

        }
        return $data;
//        $this->response($data,'json');
    }

    /*
     * 发送模板短信
     * @appointmentId 预约id
     * return 预约信息
     * curl -d "phoneNumber=13764610737&templateId=25567&param=123456" "http://www.thinkcmf.su:8888/Info/sendSMS"
     * curl -d "phoneNumber=13764610737&templateId=25567&param=123456" "http://www.hatbrand.cn/thinkcmf/Info/sendSMS"
     * */
    public function sendSMS(){

        $param = $_REQUEST['param'];
        $to = $_REQUEST['phoneNumber'];
        $templateId = $_REQUEST['templateId'];
        $data=null;
        \Think\Log::write('sendSMS:'.$param, "INFO");

        if (!$to) {
            $data["info"] = "手机号码不能为空";
        } else if (!$templateId) {
            $data["info"] = "模板id不能为空";
        } else if (!$param) {
            $data["info"] = "参数不能为空";
        }
        else{
            //初始化必填
            $options['accountsid']=UCPASS_ACCOUNTSID; //填写自己的
            $options['token']=UCPASS_TOKEN; //填写自己的
            //初始化 $options必填
            $ucpass = new \Org\Com\Ucpaas($options);

            $arr=$ucpass->templateSMS(UCPASS_APPID,$to,$templateId,$param);
            \Think\Log::write('send:'.$arr, "INFO");
            //记录短信验证码至数据库
            $msg['CODE'] = $param;
            $msg['phoneNumber'] = $to;
            $msg['SENDFLAG'] = 0;
            $msg['VALIDTIME'] = date ( "Y-m-d H:i:s", mktime ( date ( "H" ), date ( "i" ) + 5, date ( "s" ), date ( "m" ), date ( "d" ), date ( "Y" ) ) );
            if (substr($arr,21,6) == 000000) {
                \Think\Log::write('send '.$to.'验证码:'.$param, "INFO");
                $MSGCODE = M('Msgcode')->data($msg)->add();

                if(!$MSGCODE){
                    $data["info"]="短信发送失败";
                    $data["errCode"]="01";
                }
                else{
                    $data["info"]="短信发送成功";
                    $data["errCode"]="00";
                }
            }else{
                //如果不成功
//                echo "短信验证码发送失败，请联系客服";
                $data["info"]="短信发送失败";
                $data["errCode"]="01";

                $msg['SENDFLAG'] = 1;
            }


        }
        $this->response($data,'json');
    }

    /*
     * 发送模板短信
     * @phoneNumber
     * @msgCode
     * curl -d "phoneNumber=13764610737&msgCode=123456" "http://www.thinkcmf.su:8888/Info/verifyCode"
     * curl -d "phoneNumber=13764610737&msgCode=123456" "http://www.hatbrand.cn/thinkcmf/Info/verifyCode"
     * return
     * */
    public function verifyCode(){

        $code = $_REQUEST['msgCode'];
        $phoneNumber = $_REQUEST['phoneNumber'];
//        $templateId = $_REQUEST['templateId'];
        \Think\Log::write("verifyCode: $code $phoneNumber", "INFO");

        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

        $Msgcode = M("Msgcode");
        $data=null;

        $data = $Msgcode->where('status=0 AND phoneNumber=%s', $phoneNumber)->order('CREATETIME desc')->limit(1)->find();

        $currentTime = date ( "Y-m-d H:i:s");
        $ret['errCode'] = "01";

        //验证码失效
        if(strtotime($currentTime)>strtotime($data['validtime'])){
            $ret['info'] = "验证码失效";
            \Think\Log::write("verifyCode info: ".strtotime($currentTime), "INFO");
            \Think\Log::write("verifyCode info: ".strtotime($data['validtime']), "INFO");

        }
        else{
            if(trim($data[code]) == trim($code)){
                $ret['errCode'] = "00";
                $ret['info'] = "验证通过";
            }
            else{
                $ret['errCode'] = "01";
                $ret['info'] = "验证码错误";
            }
        }
        \Think\Log::write("verifyCode info: ".$ret['info'], "INFO");

        $this->response($ret,'json');

    }

    /*
     * 通过位置信息获取医生列表
     * @x x坐标
     * @y y坐标
     * @pageSize 列表数量
     * return 预约信息
     */
    public function _getDoctorByLBS($x, $y, $pageSize)
    {
        $data = null;
        \Think\Log::write("getDoctorByLBS, x: $x y: $y", "INFO");


        if (!$x || !$y && $x != "0" && $y != "0") {
            $data["info"] = "医生位置信息不能为空";
        } else {
            $Model = M('Doctor'); // 实例化一个model对象 没有对应任何数据表
            //根据距离选取距离最近的医生
            $doctorList = $Model->query("select id,phonenumber,realname,sex,birthday,province,city,district,hospital,title,description,headlogofile,curetime,maxpatient, sqrt(power(abs(doctor.x - $x),2) + power(abs(doctor.y - $y),2)) as distance from __DOCTOR__ doctor order by  distance asc limit $pageSize");

            if (!$doctorList) {
                $data["info"] = "未查询到相关信息";
                $data["errCode"] = "01";
            } else {
                $data["doctorList"] = $doctorList;
                $data["info"] = "查询成功";
                $data["errCode"] = "00";
            }
        }

        return $data;
    }

    /**
     * @param $doctorId
     * @param $start_time
     * @param $end_time
     * @param $start
     * @param $pageSize
     * @return null
     */
    public function _getAppointments($doctorId, $start_time, $end_time)
    {
        $data = null;
        \Think\Log::write('chart doctorId:' . $doctorId, "INFO");

        if (!$doctorId) {
            $data["info"] = "医生id不能为空";
            $data["errCode"] = "03";
            return $data;

        } else if (!$start_time || !$end_time) {
            $data["info"] = "时间不能为空";
            $data["errCode"] = "04";
            return $data;

        } else {
            $appointment = M('Appointment');
            $doctor = M('Doctor')->find($doctorId);

            //自定义星期数组
            $weekArr = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");

            $map = null;
            $info = null;
            $appointmentInfo = null;

            //获取数字对应的星期
            //return $weekArr[$number_wk];

            for ($i = strtotime($start_time); $i <= strtotime($end_time); $i += 86400) {
                $info = null;
                $t = date("Y-m-d", $i);
                $currentTime = $t;

                foreach(array(0,1) as $flag){

                    $number_wk = date("w", $i);

                    $cureTimeArray = $doctor['curetime'];
                    $needle = (String)($number_wk * 2 - 2 + $flag);
                    $pos = strpos($cureTimeArray, $needle);
                    if($pos === false){
                        continue;
                    }

                    $map['cureTime'] = array('eq', (String)$currentTime);
                    $map['doctor_id'] = $doctorId;
                    $hasAppoint = $appointment->where($map)->count();

                    \Think\Log::write('chart doctorId:' .M('Appointment')->getLastSql(), "INFO");

                    $info["week"] = $weekArr[$number_wk];
                    $info["flag"] = $info["flag"] == '0' ? '0,1' : (String)$flag;
                    $info["hasAppoint"] = $hasAppoint;
                    if($info["hasAppoint"] >= $doctor["maxpatient"])
                        continue;//如果预约人数满了,跳过
                    //$info["isFull"] = $info["hasAppoint"] >= $doctor["maxpatient"];
                    $appointmentInfo[$currentTime] = $info;

                }

            }

            if (!$appointmentInfo) {
                $data["info"] = "未查询到相关信息";
                $data["errCode"] = "01";
            } else {
                $data["appointmentList"] = $appointmentInfo;
                $data["info"] = "查询成功";
                $data["total"] = $doctor["maxpatient"];
                $data["cureTime"] = $doctor['curetime'];
                $data["start_date"] = $start_time;
                $data["end_date"] = $end_time;
                $data["errCode"] = "00";
            }
            return $data;

        }
    }
}