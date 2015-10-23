<?php
/* @var $this LearnerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Learners',
);

$this->menu=array(
	array('label'=>'Create Learner', 'url'=>array('create')),
	array('label'=>'Manage Learner', 'url'=>array('admin')),
);
?>

<h1>Learners</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
