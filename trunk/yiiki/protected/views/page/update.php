<?php
$this->breadcrumbs=array(
	'Yiiki lista'=>array('index'),
	$model->title=>array('view','title'=>$model->title),
	'Update',
);
?>

<h1><?php echo $model->title; ?> oldal frissitese</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
