<?php

class UsersController extends CController
{

    // Действие регистрации
    public function actionRegistration()
    {
        // Создать модель и указать ей, что используется сценарий регистрации
        $user = new Users(Users::SCENARIO_SIGNUP);

        // Если пришли данные для сохранения
        if(isset($_POST['Users']))
        {
            // Безопасное присваивание значений атрибутам
            $user->attributes = $_POST['Users'];

            // Проверка данных
            if($user->validate())
            {
                // Сохранить полученные данные
                // false нужен для того, чтобы не производить повторную проверку
                $user->save(false);

                // Перенаправить на список зарегестрированных пользователей
                $this->redirect($this->createUrl('site/'));
            }
       }

        // Вывести форму
        $this->render('registration', array('form'=>$user));
    }

}