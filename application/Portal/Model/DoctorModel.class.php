<?php


namespace Portal\Model;
use Think\Model\RelationModel;

class DoctorModel extends RelationModel{
    protected $_link = array(

        'title' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'dict_title',
            'mapping_name' => 'titles',
            'foreign_key' => 'id',
            'as_fields' => 'title:title'

            // 定义更多的关联属性
        ),
    );
}