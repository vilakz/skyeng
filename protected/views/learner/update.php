<?php
/* @var $this LearnerController */
/* @var $model Learner */

$this->breadcrumbs=array(
	'Learners'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Learner', 'url'=>array('index')),
	array('label'=>'Create Learner', 'url'=>array('create')),
	array('label'=>'View Learner', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Learner', 'url'=>array('admin')),
);
?>

<h1>Update Learner <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>