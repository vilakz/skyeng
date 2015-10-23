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
<h1>Add learners to teachers</h1>

<h3>Teachers:</h3>
<?
$this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'id_addlearner' ,
    'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id' , 'name' , 'gender' , 'phone' ,
        array(            // display a column with "view", "update" and "delete" buttons
            'class'=>'CButtonColumn',
        	'template'             => '{learners}',
        	'buttons' => array(
        		'learners' => array(
        			//'label' => "Learners" ,
        			'url' => 'Yii::app()->createUrl( \'learner/toteacher\' , array( \'teacher_id\' => $data->id ) )',
        			'click' => "js:function(__event) {
        				__event.preventDefault(); // disable default action
        				console.log( __event );
$.ajax({
            'type': 'POST',
            'url' : $(this).attr('href'),
            success: function (data) {
                $('#dlg_learners').html(data);
                $('#dlg_learners').dialog( 'open' );
            },
            dataType: 'html'
        });
        				
        			}
        			" ,
        		),
        	),
        ),
    ),
));

?>

<?php $this->widget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'dlg_learners',
    'options'=>array(
        'title'=>'Add learners to teacher',
        'width'=>700,
        'height'=>400,
        'autoOpen'=>false,
        'resizable'=>true,
        'modal'=>true,
        'closeOnEscape' => true,     
    ),

)); ?>

<script type="text/javascript">
/*<![CDATA[*/
	function CheckClickToTeacher( obj )
	{
		//console.log( obj );
		// вытащить teacher learner checked
		// отправить ajax запрос на изменение св€зи
		var teacher_id = $(obj).data('teacherid');
		var learner_id = $(obj).val();
		var to = 'unset';
		if ( $(obj).is(':checked') )
		{
			to = 'set';
		}
		//console.log( teacher_id + ' | ' + learner_id + ' | ' + to  );
		$.ajax({
			url: "<?=Yii::app()->createUrl('learner/processlink')?>" ,
			method: "POST",
			data: { teacher: teacher_id , learner: learner_id , to: to } ,
		}).success( function(data) {
			//console.log( data );
			} )
		;
		return true;
	}
/*]]>*/
</script>           
