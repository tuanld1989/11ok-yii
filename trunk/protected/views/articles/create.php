<h2>写新文章</h2>

<div class="actionBar">[<?php echo CHtml::link('文章列表',array('list')); ?>]
[<?php echo CHtml::link('管理文章',array('admin')); ?>]</div>
<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
        'artCate'=>$artCate,
        'gArtCate'=>$gArtCate,
	'update'=>false,
)); ?>