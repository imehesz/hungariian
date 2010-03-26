<?php
$this->breadcrumbs=array(
	'Yiiki lista'=>array('index'),
	$model->title,
);
?>
<h1><?php echo strtoupper($model->title); ?></h1>

<div>
	<?php echo CHtml::link('Oldal Frissitese',array('update','title'=>$model->title)); ?>
	<?php echo CHtml::linkButton('Oldal Torlese',array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Biztos, hogy toroljem?')); ?>
</div>

<div style="margin-top:20px;background-color:#efefef;padding:10px;">
	<?php $wiki = new creole();echo $wiki -> parse( $model->body ); ?>
</div>

<?php echo CHtml::link( 'Help', 'http://www.wikicreole.org/attach/CheatSheet/creole_cheat_sheet.png')?>
