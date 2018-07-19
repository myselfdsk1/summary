<div class="form">
<?php

$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('http://code.jquery.com/jquery-1.4.2.min.js');
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/education.js',CClientScript::POS_END
);

if ($user->activated == 11){
    $disabled = true;
    $disabled_str = 'disabled="true"';
}
else {
    $disabled = false;
    $disabled_str = '';
}

if ($user->activated == 11) echo '<div class="error">Заявление подано, и данные не могут быть изменены</div>';

$errors = $model->getErrors();

if (Yii::app()->user->getFlash('saved_exam')) {
    echo '<p class="succes_save">Ваши данные о ЕГЭ успешно сохранены.</p><br>';

} else {
    if (count($errors) > 0) {
        echo '<p class="unsaved">Введенные данные не сохранены.
        Следуйте указаниям сообщений об ошибках рядом с полями для ввода данных на этой странице.</p><br>';
        //Yii::app()->user->setFlash('saved', "Введенные данные не сохранены.<br>Следуйте указаниям сообщений об ошибках рядом с полями для ввода данных на этой странице.");
    }
}

if (count(Helpers::examIsFilled(Yii::app()->user->getId())) == 0){
    $url = SiteController::createUrl('gototsu/fillPoll');
    echo '<p class="succes_save">Все необходимые данные на этой странице заполнены, теперь Вы можете <a href='.$url.'>заполнить анкету</a></p><br>';
}
else {
    echo '<p class="unsaved">Не все данные заполнены.
        Для подачи заявления Вам необходимо заполнить все обязательные поля (отмечены *)</p><br>';
}

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<br>
<div class="input">
    <label>Документ</label>&nbsp;&nbsp;&nbsp;
    <input class="big" type="text" disabled value="Свидетельство о результатах ЕГЭ" />
</div>
<!--<div class="input">

    <?php //echo $form->labelEx($model,'USE_doc_ser');?>&nbsp;<font color="red">*</font>
    <?php //echo $form->textField($model,'USE_doc_ser', array('class'=>'big', 'disabled'=>$disabled));?>
</div>
<div class="error"><?php //echo $form->error($model,'USE_doc_ser'); ?>
</div>-->

<div class="input">

    <?php echo $form->labelEx($model,'USE_doc_num');
    if (Helpers::isExamRequired(Yii::app()->user->getId())) echo '&nbsp;<font color="red">*</font>';
    echo $form->textField($model,'USE_doc_num', array('class'=>'big', 'disabled'=>$disabled));?>
</div>
<div class="error">    <?php echo $form->error($model,'USE_doc_num'); ?>
</div>
<div class="input">
    <?php echo $form->labelEx($model,'USE_doc_year');
    if (Helpers::isExamRequired(Yii::app()->user->getId())) echo  '&nbsp;<font color="red">*</font>';
    echo $form->textField($model,'USE_doc_year', array('class'=>'big', 'disabled'=>$disabled));?>
</div>
<div class="error"><?php echo $form->error($model,'USE_doc_year'); ?>
</div>


<h2>Результаты экзаменов</h2>
<br>
<div id="exams">
    <?php

        foreach($model->users_exam as $uo){
            echo '<div id="exam_'.$uo['id'].'">';

            echo '<div class="input"><label>Дисциплина</label>&nbsp;&nbsp;'.$form->dropDownList($model, 'discipline', $model->discipline,
                                       array('name'=>'exam_discipline_'.$uo['id'],'id'=>'exam_discipline_'.$uo['id'],
                                       'options' => array($uo['discipline']=>array('selected'=>true)),
                                       'class'=>'big', 'disabled'=>$disabled)).'</div>';

            echo '<div class="input"><label>Балл</label>&nbsp;&nbsp;'.$form->textField($model, 'mark',
                                              array('name'=>'exam_mark_'.$uo['id'],'id'=>'exam_mark_'.$uo['id'], 'value'=>$uo['mark'],'class'=>'big', 'disabled'=>$disabled)).'</div>';
            if (!$disabled)
                echo '<div class="input"><a href="#" onclick="delExam('.$uo['id'].')">Удалить сведения об этой  оценке</a></div>';
            echo '</div>';
        }
    ?>
    <div id="text_exam" style="display:none">


        <div class="input"><label>Дисциплина</label>
            <?php echo $form->dropDownList($model, 'discipline', $model->discipline,
                                       array('name'=>'text_discipline','id'=>'text_discipline','class'=>'big', 'disabled'=>$disabled));?></div>

        <div class="input"><label>Балл</label>
            <?php echo  $form->textField($model, 'mark', array('name'=>'text_mark','id'=>'text_mark','class'=>'big', 'disabled'=>$disabled));?></div>
        <? if (!$disabled){
            echo '<div class="input"><a href="javascript:void(0)" id="del_exam">Удалить сведения об этой оценке</a></div>';
           } ?>
    </div>
</div>
<? if (!$disabled) {
    echo '<div class="input"> <a href="javascript:void(0)" onClick="addExam();">Добавить новую оценку</a></div><br>';
} ?>



<div class="input" >
    <label> </label>
    <?php echo CHtml::submitButton('Сохранить', array('class'=>'button', 'disabled'=>$disabled)); ?>
    <?php echo CHtml::submitButton('Сбросить', array('class'=>'button', 'disabled'=>$disabled)); ?>
    <!-- <input type="reset" name="reset" value="Сбросить" class="half" <? //echo $disabled_str?>/> -->
</div>

<?php $this->endWidget(); ?>
</div>
