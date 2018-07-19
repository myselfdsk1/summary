<?php

class CityController extends CController
{

	public function actionAutocomplete() {
        $term = Yii::app()->getRequest()->getParam('term');

        if(Yii::app()->request->isAjaxRequest && $term) {

			$country = Yii::app()->getRequest()->getParam('country');

            $def_country = Address::defaultAddress();
	        if ($country != $def_country->country->name){
                echo CJSON::encode(array());
                return;
            }

			$regionid = Yii::app()->getRequest()->getParam('reg_fnrec');
            $regname = Yii::app()->getRequest()->getParam('region');

            if ($regionid == ''){
                $criteria = new CDbCriteria;
                $criteria->condition = "status > 0 AND name=:name";
                $criteria->params = array(':name' => $regname);
                $cl_region = ClRegion::model()->find($criteria);
                if ($cl_region){
                    $regionid = $cl_region->fnrec;
                }
            }

			$sql = "select * from cl_city where status > 0 and region = '".$regionid."'";
    
            if (!empty($term)){
                $sql = "(select * from cl_city where status > 0 and region = '".$regionid."' and name like '%".$term."%')";
                $words = preg_split("/[^(\w)|(\x7F-\xFF)]+/", $term, -1, PREG_SPLIT_NO_EMPTY);
                if (count($words) > 0){
                    $sql_split = " union (select * from cl_city where status > 0 and region = '".$regionid."'";
                    foreach ($words as $word){
                        $sql_split .= " and name like '%".$word."%'";
                    }
                    $sql_split .= ")";
                    $sql .= $sql_split;
                }
                foreach ($words as $word){

                    $sql .= " union (select * from cl_city where status > 0 and region = '".$regionid."' and name like '%".$word."%')";
                }
            }
            $cmd = Yii::app()->db->createCommand($sql);
            $cities = $cmd->queryAll();

            $result = array();
			$i = 1;
            foreach($cities as $city) {
                $lable = $city['name'];
                $result[] = array('id'=>$city['fnrec'], 'label'=>$lable, 'value'=>$lable);
				$i += 1;
                if ($i > 10) break;
            }
            echo CJSON::encode($result);
            Yii::app()->end();


        }
    }
}
?>
