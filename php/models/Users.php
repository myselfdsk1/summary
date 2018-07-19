<?php

class Users extends CActiveRecord
{

    // Сценарий регистрации
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_LOGIN  = 'login';

    public $password_repeat;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
	    // NOTE: you should only define rules for those attributes that
	    // will receive user inputs.
	    return array(

		array('login, passwd', 'required','on'=>self::SCENARIO_SIGNUP,
              'message'=>'Поле "{attribute}" обязательно для заполнения'),

		array('login, passwd, email', 'length', 'max'=>128,
              'message'=>'Длина поля "{attribute}" не может превышать 128 символов'),
		// The following rule is used by search().
		// Please remove those attributes that should not be searched.
		array('login, passwd, email, userid', 'safe', 'on'=>'search'),
        // Логин должен соответствовать шаблону
        array('login', 'match', 'pattern'=>'/^[A-z][\w]+$/',
              'message'=>'Логин может содержать только буквы латинского алфавита, цифры,
               символы подчеркивания и должен начинаться с буквы'),
        // Логин должен быть уникальным
        array('login', 'unique','on'=>self::SCENARIO_SIGNUP, 'message'=>'Логин уже занят'),
        // Почта проверяется на соответствие типу
        array('email', 'email', 'message'=>'Некорректный адрес электронной почты'),
        // Почта должна быть уникальной
        array('email', 'unique', 'message'=>'Уже зарегистрирован пользователь с таким адресом'),
        // Почта должна быть написана в нижнем регистре
        array('email', 'filter', 'filter'=>'mb_strtolower',
              'message'=>'Адрес электронной почты должен быть набран в нижнем регистре'),
	    );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
            return array(
                'userinfo'=>array(self::HAS_ONE, 'UserInfo', 'userid')
            );

	}

	public function attributeLabels()
	{
	    return array(
           	'login' => 'Ник/Логин',
           	'passwd'  => 'Пароль',
	       	'email' => 'email',
           	
	    );
	}

    protected function beforeSave()
    {
        if(parent::beforeSave())
         {
            if($this->isNewRecord)
            {
                              
                // Хешировать пароль
                $this->passwd = $this->hashPassword($this->passwd);
            }

            return true;
         }

        return false;
    }

    public function hashPassword($password)
    {
        return md5($password);
    }

}
