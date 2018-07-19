<?php

class UsersOlympic extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'users_olympic';
	}

	public function relations()
	{
        return array(
                'user'=>array(self::BELONGS_TO, 'Users', 'userid'),
        );

	}
}
