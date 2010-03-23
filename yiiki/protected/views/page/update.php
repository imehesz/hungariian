<?php
$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<h1>Update Page <?php echo $model->id; ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link('List Page',array('index')); ?></li>
	<li><?php echo CHtml::link('Create Page',array('create')); ?></li>
	<li><?php echo CHtml::link('View Page',array('view','id'=>$model->id)); ?></li>
	<li><?php echo CHtml::link('Manage Page',array('admin')); ?></li>
</ul><!-- actions -->

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>