<?php


$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.yiiactiveform.js');
$cs->registerCoreScript('jquery');

?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'register-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>false,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange' => false
        ),
    )); ?>

    <div class="input">
        <?php echo $form->labelEx($model1,'username')?>
        <?php echo $form->textField($model1,'username',
                                    array('disabled'=>!Yii::app()->user->isGuest, 'class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'username'); ?></div>

    <div class="input">
        <?php echo $form->labelEx($model1,'passwd')?>
        <?php echo $form->passwordField($model1,'passwd', array('disabled'=>!Yii::app()->user->isGuest, 'class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'passwd'); ?></div>

    <div class="input">
        <?php echo $form->labelEx($model1,'repeat_passwd')?>
        <?php echo $form->passwordField($model1,'repeat_passwd', array('disabled'=>!Yii::app()->user->isGuest, 'class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'repeat_passwd'); ?></div>

    <div class="input">
        <?php echo $form->labelEx($model1,'email')?>
        <?php echo $form->textField($model1,'email', array('class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'email'); ?></div>
    
    <div class="input">
        <?php echo  $form->labelEx($model1,'lastname'); ?>
        <?php echo $form->textField($model1,'lastname', array('class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'lastname'); ?></div>


    <div class="input">
        <?php echo $form->labelEx($model1,'firstname')?>
        <?php echo $form->textField($model1,'firstname', array('class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'firstname'); ?></div>

    <div class="input">
        <?php echo $form->labelEx($model1,'secondname')?>
        <?php  echo $form->textField($model1,'secondname', array('class'=>'big')); ?>
    </div>
    <div class="error"><?php echo $form->error($model1,'secondname'); ?></div>

    <div class="input">
        <?php echo $form->labelEx($model1,'birthday')?>

            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name'=>'RegisterForm[birthday]',
                'language'=>'ru',
                 'model'=>$model1,
                'attribute'=>'birthday',
                // additional javascript options for the date picker plugin
                'options'=>array(
                    'showAnim'=>'fold',
                    'dateFormat'=>'dd.mm.yy',
                    'yearRange'=>'-70:+0',
                    'changeYear'=>'true',
                    'changeMonth'=>'true',
                ),
                'htmlOptions'=>array(
                    /*'style'=>'height:20px;',*/
                    'class'=>'big'
                ),
            ));?>

    </div>
    <div class="error"><?php echo $form->error($model1,'birthday'); ?></div>

    <!--<div class="input">-->
        <?php echo $form->labelEx($model1,'sex')?>

            <?php
                echo $form->radioButtonList($model1,'sex',array('1'=>'Мужской','2'=>'Женский'),
                        array('separator'=>'','class'=>'checkbox'));
            ?>

    <!--</div>-->
    <div class="error"><?php echo $form->error($model1,'sex'); ?></div>

    <br>
    
    <div class="input">
            <?php echo $form->checkBox($model1,'agreement', array('class'=>'checkbox')); ?>
            <?php echo $form->label($model1,'agreement'); ?>

    </div>
    <div class="error"><?php echo $form->error($model1,'agreement'); ?></div>


    <br>
    <div class="input">
        <label> </label>
        <?php echo CHtml::submitButton('Сохранить', array('class'=>'button')); ?>
        <?php echo CHtml::submitButton('Сбросить', array('class'=>'button')); ?>
    </div>
    
    <?php

        if ($model1->oldabit || $model1->just_activated) $notice = true;
        else $notice = false;
        $href = 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->createUrl("site/login");

        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id'=>'warning',
            'options'=>array(
                'title'=>'Внимание!',
                'autoOpen'=>$notice,
				'close'=>"js:function() {location.href='".$href."'}",
            ),
        ));

        $msg = '';
        if ($model1->oldabit) $msg .= 'Убедитесь, что Вы правильно ввели дату рождения (возраст превышает 50 лет). ';
        if ($model1->just_activated) $msg .= '<p>Благодарим Вас за регистрацию на порталеа.<br>На указанный Вами адрес электронной почты выслано письмо с кодом подтверждения.
         Для завершения регистрации необходимо пройти по ссылке.</p>';
        echo $msg;

        $this->endWidget('zii.widgets.jui.CJuiDialog');

        $this->endWidget();
    ?>

</div>
