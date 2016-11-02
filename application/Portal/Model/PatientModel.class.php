<?php


namespace Portal\Model;
use Think\Model\RelationModel;

class PatientModel extends RelationModel{
    protected $_link = array(
        'nationality' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_nationality',
            'foreign_key' => 'id',
            'as_fields' => 'title:nationality'
            // 定义更多的关联属性
        ),
        'maritalStatus' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_marital_status',
            'foreign_key' => 'id',
            'as_fields' => 'title:maritalStatus'
            // 定义更多的关联属性
        ),
        'occupation' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_occupation',
            'foreign_key' => 'id',
            'as_fields' => 'title:occupation'
            // 定义更多的关联属性
        ),
        'avocation' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_occupation',
            'foreign_key' => 'id',
            'as_fields' => 'title:avocation'
            // 定义更多的关联属性
        ),
        'gastroScope' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_gastroScope',
            'foreign_key' => 'id',
            'as_fields' => 'title:gastroScope'
            // 定义更多的关联属性
        ),
        'pathology' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_pathology',
            'foreign_key' => 'id',
            'as_fields' => 'title:pathology'
            // 定义更多的关联属性
        ),
        'stomachHistory' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_stomachHistory',
            'foreign_key' => 'id',
            'as_fields' => 'title:stomachHistory'
            // 定义更多的关联属性
        ),
        'familyCancer' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_cancer',
            'foreign_key' => 'id',
            'as_fields' => 'title:familyCancer'
            // 定义更多的关联属性
        ),
        'relationship' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_relationship',
            'foreign_key' => 'id',
            'as_fields' => 'title:relationship'
            // 定义更多的关联属性
        ),
        'similarHistory' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_stomachSymtom',
            'foreign_key' => 'id',
            'as_fields' => 'title:similarHistory'
            // 定义更多的关联属性
        ),
        'drug' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'dict_drug',
            'foreign_key' => 'id',
            'as_fields' => 'title:drug'
            // 定义更多的关联属性
        ),
    );
}