
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm'); ?>
    <div class="row">
        <?php echo $form->label($model, 'Логин');?>
        <?php echo $form->textField($model, 'login');?>
        <?php echo $form->error($model,'login', array('class'=>'error')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'Почта');?>
        <?php echo $form->textField($model, 'email');?>
        <?php echo $form->error($model,'email', array('class'=>'error')); ?>
    </div>
    <p>На указанную почту будет отправлено письмо с новым паролем</p>
    <div class="row submit">
        <?php echo CHtml::submitButton('Восстановить');?>
    </div>

    <?php $this->endWidget(); ?>



</div><!-- form -->