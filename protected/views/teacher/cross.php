<?php
//var_dump( $result );
?>
<h1>Two teachers with max cross count learners</h1>

<?php 

if ( isset( $result['teachers'] ) )
{
		echo "<h4>Max cross learners : <strong>{$result['count']}</strong></h4>";
		$SqldataProvider = new CSqlDataProvider( $result['teachers'] , array(
    		'keyField'=>'id',
    		//'totalItemCount'=> count( $result['teachers'] ),
    		'pagination'=>array(
        		'pageSize'=>100,
    		),
		));
		echo "<h3>Teachers:</h3>";
		$this->widget('zii.widgets.grid.CGridView', array(
    		'dataProvider'=>$SqldataProvider,
    		'enablePagination' => true,
	));

		$SqldataProvider = new CSqlDataProvider( $result['learners'] , array(
    		'keyField'=>'id',
    		//'totalItemCount'=> count( $result['teachers'] ),
    		'pagination'=>array(
        		'pageSize'=>100,
    		),
		));
		echo "<h3>Learners:</h3>";
		$this->widget('zii.widgets.grid.CGridView', array(
    		'dataProvider'=>$SqldataProvider,
    		'enablePagination' => true,
	));
	

} else {
	echo "<h4>Nothing found</h4>";
}
?>