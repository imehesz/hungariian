<?php
$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->title,
);
?>
<h1>View Page #<?php echo $model->id; ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link('List Page',array('index')); ?></li>
	<li><?php echo CHtml::link('Create Page',array('create')); ?></li>
	<li><?php echo CHtml::link('Update Page',array('update','id'=>$model->id)); ?></li>
	<li><?php echo CHtml::linkButton('Delete Page',array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure to delete this item?')); ?></li>
	<li><?php echo CHtml::link('Manage Page',array('admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'body',
		'created',
		'id',
		'revision',
		'title',
	),
)); ?>
