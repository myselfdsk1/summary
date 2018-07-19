

<?php

$table_questions = array(
    array(
        'text'=>'F.A.Q.',
        'expanded'=>true,
        'children'=>array(
            'text'=>NULL,
            'children'=>array(
                'text'=>NULL,
                'children'=>array(
                    'text'=>NULL
                )
            )
        )
    ));
for($i=0; $i < count($fulltable); $i++)
{

    $table_questions[0]['children'][$i]['text'] = "<img src=\"/images/question.png\">".$fulltable[$i]['question'];
    $table_questions[0]['children'][$i]['expanded'] = true;
    $table_questions[0]['children'][$i]['children'][0]['text'] = "<div class=\"green\"><img src=\"/images/answer.png\">".$fulltable[$i]['answer'];
    $table_questions[0]['children'][$i]['children'][0]['expanded'] = true;
   // $table_questions[0]['children'][$i]['children'][0]['children'][0]['text'] = $fulltable[$i]['date'];


}
$this->widget('CTreeView', array(
    'data' => $table_questions,
    'cssFile'=>'/css/ctreeview.css'
   // 'htmlOptions'=>array('class'=>'my-class'),
    ));



?>







<!--$this->pageTitle=Yii::app()->name;-->
<!--$this->widget('zii.widgets.grid.CGridView', array(-->
<!--//'id'=>'ItemGrid',-->
<!--// 'filter'=>$fulltable,-->
<!--'dataProvider'=>$fulltable,-->
<!--'columns'=> array(-->
<!--array(-->
<!--'type'=>'raw',-->
<!--'value'=>'"<img src=\"/images/question.png\" width=32 height=32>"',-->
<!--        ),-->
<!--    array(-->
<!--        'name'=>'Вопрос',-->
<!--        'value'=>'$data->question'-->
<!--    ),-->
<!--    array(-->
<!--        'type'=>'raw',-->
<!--        'value'=>'"<img src=\"/images/answer.png\">"',-->
<!--    ),-->
<!--    array(-->
<!--        'name'=>'Ответ',-->
<!--        'value'=>'$data->answer'-->
<!--    ),-->
<!--    array(-->
<!--        'name'=>'Дата',-->
<!--        'value'=>'$data->date'-->
<!--    )-->
<!--    )-->
<!--    ));-->





<!--<table align="center">
<?/*foreach($fulltable as $string):*/?>
       <tr>
           <td> <img src="/images/question.png" height="32" WIDTH="32" </td>
           <td><?php /*echo $string['question']*/?></td>
       </tr>
       <tr>
           <td> <img src="/images/answer.png" height="32" WIDTH="32"  </td>
           <td><?php /*echo $string['answer']*/?></td>
           <td><?php /*echo $string['date']*/?></td>
       </tr>
<?php /*endforeach*/?>
</table>-->
