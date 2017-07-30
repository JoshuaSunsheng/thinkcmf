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
     * curl -d "doctorId=53" "www.hatbrand.cn/thinkcmf/Info/getDoctorInfoById"
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
     * curl -d "doctorId=53" "www.hatbrand.cn/thinkcmf/Info/getDoctorCureTime"
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
            if(!$cureTime){
                $data["errCodeDes"]="未查询到相关信息";
                $data["errCode"]="01";
            }
            else{
                $data["doctorId"]=$doctorId;
                $data["cureTime"]=$cureTime;
                $data["errCodeDes"]="查询成功";
                $data["errCode"]="00";
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
     *  curl -d "doctorId=11&start=2&pageSize=3" "http://www.thinkcmf.su:8888/Info/getDoctorAppointments"
     * */
    public function getDoctorAppointments(){

        $doctorId = $_REQUEST['doctorId'];
        $start = $_REQUEST['start']?$_REQUEST['start']:0;
        $pageSize = $_REQUEST['pageSize']?$_REQUEST['pageSize']:5;

        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
        }
        else{
//            if(strtotime($currentTime)>strtotime($data['validtime']))
            $currentTime = date ( "Y-m-d");

            $map['cureTime'] = array('egt',$currentTime);
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
     * 新增预约
     * @doctorId 医生id
     * @patientId 患者id
     * @cureTime 预约时间
     * return 预约结果
     *  curl -d "doctor_id=53&patient_id=11&cureTime=2017-06-12&flag=1" "http://www.thinkcmf.su:8888/Info/addAppointment"
     * */
    public function addAppointment(){

        $doctorId = $_REQUEST['doctor_id'];
        $patientId = $_REQUEST['patient_id'];
        $cureTime = $_REQUEST['cureTime'];
        $flag = $_REQUEST['flag'];
        \Think\Log::write("addAppointment, doctorId: $doctorId patientId: $patientId cureTime: $cureTime", "INFO");

        $data=null;
        $data["errCode"]="01";
        $currentTime = date ( "Y-m-d");
        \Think\Log::write("addAppointment, oneWeek:".$currentTime, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
        }
        else if(!$patientId){
            $data["info"]="患者id不能为空";
        }
        else if(!$cureTime){
            $data["info"]="就诊时间不能为空";
        }
        else if(!$flag){
            $data["info"]="就诊时间不能为空";
        }
        else if($cureTime < $currentTime){
            $data["info"]="就诊时间不能小于今天";
            $data["cureTime"]=$cureTime;
            $data["errCode"]="03";
        }
        else{
            $doctor = M('Doctor')->find($doctorId);
            $total = M('Appointment')->where("doctor_id=$doctorId")->count();
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

                    $data["info"]="提交失败";
//                $data["e"]=$e->getMessage();
                }

                if(!$appointmentId){
                    $data["info"]="提交失败";
                }
                else{
                    \Think\Log::write("addAppointment, appointmentId: $appointmentId", "INFO");

                    $data["appointmentId"]=$appointmentId;
                    $data["info"]="提交成功";
                    $data["errCode"]="00";
                }
            }
            else{
                $data["info"]="超出医生可预约病人数";
                $data["maxPatient"]=$doctor["maxpatient"];
                $data["hasAppointed"]=$total;
                $data["errCode"]="02";
            }

        }
        $this->response($data,'json');

    }

    /*
     * 获取预约信息
     * @appointmentId 预约id
     * return 预约信息
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
     * */
    public function getDoctorByLBS(){

        $x = $_REQUEST['x'];
        $y = $_REQUEST['y'];
        $pageSize = $_REQUEST['pageSize']?$_REQUEST['pageSize']:5;

        $data=null;
        \Think\Log::write("getDoctorByLBS, x: $x y: $y", "INFO");


        if(!$x || !$y){
            $data["info"]="预约id不能为空";
        }
        else{
            $Model = M('Doctor'); // 实例化一个model对象 没有对应任何数据表
            //根据距离选取距离最近的医生
            $doctorList = $Model->query("select id,phonenumber,realname,sex,birthday,province,city,district,hospital,title,description,headlogofile,curetime,maxpatient, sqrt(power(abs(doctor.x - $x),2) + power(abs(doctor.y - $y),2)) as distance from __DOCTOR__ doctor order by  distance asc limit $pageSize");

            if(!$doctorList){
                $data["info"]="未查询到相关信息";
                $data["errCode"]="01";
            }
            else{
                $data["doctorList"]=$doctorList;
                $data["info"]="查询成功";
                $data["errCode"]="00";
            }
        }
        $this->response($data,'json');

    }


    /*
     * 发送模板短信
     * @appointmentId 预约id
     * return 预约信息
     * curl -d "phoneNumber=13764610737&templateId=25567&param=123456" "http://www.thinkcmf.su:8888/Info/sendSMS"
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
}