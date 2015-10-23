<?php
/* @var $this LearnerController */
/* @var $model Learner */

$this->breadcrumbs=array(
	'Learners'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Learner', 'url'=>array('index')),
	array('label'=>'Manage Learner', 'url'=>array('admin')),
);
?>

<h1>Create Learner</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>