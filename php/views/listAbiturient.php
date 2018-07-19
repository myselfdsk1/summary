<?php $this->pageTitle=Yii::app()->name; ?>
<div class="form">
<h1>Список всех пользователей</h1>
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'users-grid',
    'dataProvider' => $users->search(),
    'filter' => $users,
    'columns' => array(
        array(
            'header'=>'Фото',
			'type'=>'image',
			'value'=> 'Helpers::getUserPreviewPhoto($data->userid)',
			'headerHtmlOptions'=>array('width'=>'60px'),
		),
        array('name'=>'lastname','header'=>'Фамилия'),
        array('name'=>'firstname','header'=>'Имя'),
        array('name'=>'secondname', 'header' =>'Отчество'),
        array('name'=>'birthday', 'header' => 'Дата рождения', 'filter'=>false),
        array('class'=>'CButtonColumn',
               'template' => '{view}{anketa}{addToGal}',
                'buttons'=>array(
                        'addToGal' => array(
                                'label'=>'В Галактику', // text label of the button
                                'url'=>"CHtml::normalizeUrl(array('inGal', 'aid'=>\$data->userid))",
                                'imageUrl'=>'images/gal.png',
                                'visible'=>'($data->user->activated == 10)',
								'options'=>array('target'=>'_blank'),

                        ),
                        'view' => array(
                            'label' => 'Просмотр',
                            'url' => "CHtml::normalizeUrl(array('viewAbiturient', 'aid'=>\$data->userid))",
							'options'=>array('target'=>'_blank'),
                        ),
						'anketa' => array(
                            'label'=>'Анкета', // text label of the button
                            'url'=>'"*******&__format=pdf&userid=".$data->userid',
                            'imageUrl'=>'images/Anketa3.png',
							'options'=>array('target'=>'_blank'),
                        ),
						
                ),),

    ),
));
?>
</div>
