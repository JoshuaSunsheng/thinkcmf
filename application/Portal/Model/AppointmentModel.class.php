<?php


namespace Portal\Model;

use Think\Model\RelationModel;

class AppointmentModel extends RelationModel
{

//定义用户与用户信处表关联关系属性
    Protected $_link = array(
        'Patient' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Patient',
            'as_fields' => 'realname:patientname'
        ),

        'Doctor' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Doctor',
            'as_fields' => 'realname:doctorname'

        )
    );

}