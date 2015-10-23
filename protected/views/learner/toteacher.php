<?php
?>
<h3>Learners:</h3>
<?
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'id_toteacher' ,
    'dataProvider'=>$dataProvider,
	'ajaxUrl'=>Yii::app()->createUrl( 'learner/toteacher' ),
	'ajaxUpdate'=>'id_toteacher',
	'columns'=>array(
		'id' ,
		array(
 			'header' => "Study with this teacher",
            'class' => 'CCheckBoxColumn',
            'selectableRows' => 50,
            'checked' => "( ( \$data['tp'] ) ? true : false)",//php code in single quotes
			'checkBoxHtmlOptions' => array( 'onclick' => 'return CheckClickToTeacher(this);' , 'data-teacherid' => $teacher_id ) ,
		) , 
		'name' , 
		'email' , 
		array (
			'name' => 'birthdate' ,
			'value' => "(\$data['birthdate'])" , 
		) , 
		'level' , 
    ),
));
?>
