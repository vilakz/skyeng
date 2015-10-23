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
<h1>Teachers april learners</h1>

<h3>Teachers:</h3>
<?
$this->widget('zii.widgets.grid.CGridView', 
	array(
		'id' => 'id_teachers_april' ,
    	'dataProvider'=>$dataProvider,
		'columns'=>array(
			'id' , 'name' , 'gender' , 'phone' ,
		),
    )
);
?>
