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

    public function read_post_json(){
        echo "read_post_json";
    }

    /*
     * 获取医生信息
     * @doctorId 医生用户id
     * return 医生信息
     * */
    public function getDoctorInfoById(){

        $doctorId = $_REQUEST['doctorId'];
        $data=null;
        \Think\Log::write('chart doctorId:'.$doctorId, "INFO");

        if(!$doctorId){
            $data["info"]="医生id不能为空";
        }
        else{
            $doctor = M('Doctor')->find($doctorId);
            \Think\Log::write('doctorInfo:'.$doctor['realname'], "INFO");
            if(!$doctor){
                $data["info"]="未查询到相关信息";
            }
            else{
                $data["doctor"]=$doctor;
                $data["info"]="查询成功";
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
            if(!$appointmentId){
                $data["info"]="未查询到相关信息";
            }
            else{
                $data["appointment"]=$appointment;
                $data["info"]="查询成功";
            }

        }
        $this->response($data,'json');

    }

    /*
     * 通过位置信息获取医生列表
     * @appointmentId 预约id
     * return 预约信息
     * */
    public function getDoctorByLBS(){

        $x = $_REQUEST['x'];
        $y = $_REQUEST['y'];
        $data=null;
        \Think\Log::write('chart doctorId:'.$x, "INFO");


        if(!$x || !$y){
            $data["info"]="预约id不能为空";
        }
        else{
            $Model = new Model(); // 实例化一个model对象 没有对应任何数据表

            $doctorList = $Model->query("select * from __DOCTOR__ doctor order by sqrt(power(abs(doctor.x - $x),2) + power(abs(doctor.y - $y),2)) limit 5");

            if(!$doctorList){
                $data["info"]="未查询到相关信息";
            }
            else{
                $data["doctorList"]=$doctorList;
                $data["info"]="查询成功";
            }

        }
        $this->response($data,'json');

    }
}