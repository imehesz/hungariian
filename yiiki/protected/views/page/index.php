<?php
$this->breadcrumbs=array(
	'Yiiki listaja',
);
?>

<h1>Yiiki Lista</h1>

<ul class="actions">
	<li><?php echo CHtml::link('Uj Yiiki oldal',array('create')); ?></li>
	<li><?php echo CHtml::link('Admin',array('admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
