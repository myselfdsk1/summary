<?php

class SettingsController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

    public function getSubMenu() {
      $subMenuItems=array();
      /*if (!Yii::app()->user->isGuest) {
              $subMenuItems[] = array('Item5','');
              $subMenuItems[] = array('Item6','');
        }*/
      return $subMenuItems;
    }


	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if (!Yii::app()->user->checkAccess('settings')){
            if (Yii::app()->user->isGuest) $this->redirect(array('site/login'));
            else $this->redirect(array('site/index'));
        }
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function getHint() {
		$action_text = Yii::app()->controller->action->id;
		$text_hint = 'На этой странице Вы можете установить настройки Вашего личного кабинета';
		return $text_hint;
	}

}
