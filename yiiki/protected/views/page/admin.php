<?php
$this->breadcrumbs=array(
	'Pages'=>array('index'),
	'Manage',
);
?>
<h1>Manage Pages</h1>

<ul class="actions">
	<li><?php echo CHtml::link('List Page',array('index')); ?></li>
	<li><?php echo CHtml::link('Create Page',array('create')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'body',
		'created',
		'id',
		'revision',
		'title',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
