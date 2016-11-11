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
        $this -> display();
    }


    function dataImport(){
        \Think\Log::write('dataImport begin:', "INFO");
        $this -> display();
return;

        $token = session('token');
        if(empty($token)) {
            redirect(CONTROLLER.'/register', 2, '页面跳转中...');
        }


        if(!empty($_POST)){
            \Think\Log::write('dataImport begin:'.$_POST, "INFO");


            $Study = new \Portal\Model\CaseModel(); // 实例化 Patient对象
//            throw new \Exception("测试错误");


            try{

                if(!$Study->create($_POST, 1)){
                    //echo $Study->_sql();//最后一条执行的Sql
                    echo $Study->getError();
                }
                else{
                    $Study -> badResponse = implode(',', $_POST['badResponse']);
                    \Think\Log::write('dataImport begin $_POST:'.$_POST, "INFO");

                    if (!$Study->add()) {
                        echo $Study->getError();
                    } else {
                        $this->success('新增成功', 'myStudy');
                    }
                    \Think\Log::write('dataImport begin $z:' . $z, "INFO");

                    $this->error('失败', 'myStudy');

                }
            }
                //这里的\Exception不加斜杠的话回使用think的Exception类
            catch(\Exception $e){
                $this->error('失败', 'myStudy');
            }



        }
        else{

            $this->data = "";
            $this->studyNo = build_order_no();
            $this -> display();
        }
    }

    function register(){

        \Think\Log::write('register begin:', "INFO");

        //获取session中token
        $token = session('token');
        \Think\Log::write('register begin:'.$token, "INFO");

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
                    "openid"=>$token['openid'],//医生
                );

                $rst = $users_model->add($data);
                \Think\Log::write('register $rst:'.$rst, "INFO");

                $this->_to_register($rst, $data);
            }

        }
        else{
            $this -> display();
        }

        \Think\Log::write('register end.', "INFO");

    }

    function myPatient($queryStr = '', $page = 1, $pagesize = 5){
        \Think\Log::write('myPatient appointment:', "INFO");

//        $queryStr = "tablelist";
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
//        var_dump($data, true);
//        var_dump($recordnum, true);
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
}