<?php

class CountryController extends CController
{

	public function actionAutocomplete() {
        $term = Yii::app()->getRequest()->getParam('term');

        if(Yii::app()->request->isAjaxRequest && $term) {
            $criteria = new CDbCriteria;
            // формируем критерий поиска
			$criteria->condition = 'status > 0';
            $criteria->addSearchCondition('name', $term);
            $countries = Country::model()->findAll($criteria);
            // обрабатываем результат
            $result = array();
			$i = 1;
            foreach($countries as $country) {
                $lable = $country['name'];
                $result[] = array('id'=>$country['countryid'], 'label'=>$lable, 'value'=>$lable);
				$i += 1;
				if ($i > 10) break;
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }
    }
}
?>
