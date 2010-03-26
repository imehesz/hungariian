<div class="form">

<?php echo CHtml::beginForm(); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo CHtml::errorSummary($model); ?>

	<?php if( $model->isNewRecord ) : ?>
		<div class="row">
			<?php echo CHtml::activeLabelEx($model,'title'); ?>
			<?php echo CHtml::activeTextField($model,'title',array('size'=>60,'maxlength'=>125)); ?>
			<?php echo CHtml::error($model,'title'); ?>
		</div>
	<?php endif; ?>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'body'); ?>
		<?php echo CHtml::activeTextArea($model,'body',array('rows'=>15, 'cols'=>75)); ?>
		<?php echo CHtml::error($model,'body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->
