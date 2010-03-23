<?php
$this->breadcrumbs=array(
	'Pages',
);
?>

<h1>List Page</h1>

<ul class="actions">
	<li><?php echo CHtml::link('Create Page',array('create')); ?></li>
	<li><?php echo CHtml::link('Manage Page',array('admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
