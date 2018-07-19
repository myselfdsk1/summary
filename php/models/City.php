<?php


class City extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'city';
	}

	public function relations()
	{
		return array(
		    'clCity'=>array(self::HAS_ONE, 'ClCity', 'fnrec')
        );

    }
}
?>
