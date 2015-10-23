<?php
/* @var $this LearnerController */
/* @var $model Learner */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'learner-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'birthdate'); ?>
		<?php //echo $form->textField($model,'birthdate'); ?>
 <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                //'name'=>'date',
                'model'=> $model,
                //'attribute'=>'birthdate',
                'name' => 'Learner[birthdate]' ,
                //'language'=>Yii::app()->language=='es' ? 'es' : null,
                'language'=> '',
                //'value'=> date( 'd/m/Y' , $model->birthdate ),
                'value'=> $model->ConvertDateForPage( $model->birthdate ),
            	'options'=>array(
                    'changeDay'=>'true', 
            		'changeMonth'=>'true', 
                    'changeYear'=>'true',   
                    'yearRange' => '-99:+2',        
                    'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                    'showOn'=>'button', // 'focus', 'button', 'both'
                    'dateFormat'=> 'dd/mm/yy',
            		//'defaultDate' => date( 'd/m/Y' , $model->birthdate ),
            		//'defaultDate' => date( 'd/m/Y' ),
            		//'value'=> $model->birthdate,
                    'theme'=>'redmond',
                    'buttonText'=>Yii::t('ui','Select form calendar'), 
                    //'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.gif', 
                    //'buttonImageOnly'=>true,
                ),
                'htmlOptions'=>array(
                    'style'=>'vertical-align:top',
                    'class'=>'span2',
                ),  
            ));?>		
		<?php echo $form->error($model,'birthdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?=$form->radioButtonList($model,'level', $model->GetLevelsForForm() ); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->