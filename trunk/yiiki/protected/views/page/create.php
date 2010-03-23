<?php
$this->breadcrumbs=array(
	'Pages'=>array('index'),
	'Create',
);
?>
<h1>Create Page</h1>

<ul class="actions">
	<li><?php echo CHtml::link('List Page',array('index')); ?></li>
	<li><?php echo CHtml::link('Manage Page',array('admin')); ?></li>
</ul><!-- actions -->

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>