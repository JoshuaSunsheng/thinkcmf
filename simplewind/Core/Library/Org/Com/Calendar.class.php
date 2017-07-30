<?php
namespace Org\Com;

/**
 * Created by PhpStorm.
 * User: UCPAAS JackZhao
 * Date: 2014/10/22
 * Time: 12:04
 * Dec : ucpass php sdk
 */
class Calendar{
    protected $_table1;//table表格
    /*
     * 根据日期获取一周的日期
     */
    public function showOneWeek($week)
    {

        $whichD=date('w',strtotime($week));
        $weeks=array();
        for($i=0;$i<7;$i++){
            if($i<$whichD){
                $date=strtotime($week)-($whichD-$i)*24*3600;
            }else{
                $date=strtotime($week)+($i-$whichD)*24*3600;
            }
            $weeks[$i]=date('Y-m-d',$date);

        }
//        $this->_table1="<table style='width: 100%;'><tr>";
//        $this->_table1.="<tbody>";
//        foreach($weeks as $k=>$v){
//            $i=date('d',strtotime($v));
//            $this->_table1.="<td style='color:#ffffff;'><div style='margin-left: 30%;line-height: 30px;background-color: #005299;border-radius: 15px;height: 30px;text-align: center;width: 30px;'>$i</div></td>";
//        }
//        $this->_table1.="</tr></tbody></table>";
//        echo $this->_table1;
        return $weeks;
    }
    /**
     * 输出日历
     */
    public function showCalendar()
    {
        echo $this->_table;
    }
}