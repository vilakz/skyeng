<?php
/* @var $this TeacherController */
/* @var $model Teacher */

$this->breadcrumbs=array(
	'Teachers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Teacher', 'url'=>array('index')),
	array('label'=>'Create Teacher', 'url'=>array('create')),
);
?>
<h1>Teachers by learners</h1>

<h3>Teachers:</h3>
<?
$this->widget('zii.widgets.grid.CGridView', 
	array(
		'id' => 'id_teacherswithlearners' ,
    	'dataProvider'=>$dataProvider,
		'columns'=>array(
			'id' , 'name' , 'gender' , 'phone' ,
			array (
				'name' => 'count learners' ,
				'value' => "(\$data['count_learners'])" ,
			) , 
		),
    )
);
?>
