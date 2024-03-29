<div id="webMain">
    	<div id="webLeftmain">
                <div id="webDiary">
                <div class="FloatLeft"><?php echo CHtml::link('&gt;&gt;我的文章', array('articles','username'=>$this->_user->username), array('class'=>'dTitle')); ?></div>
                <div class="FloatRight diarypage"><?php if(Yii::app()->user->getState('isOwner')){echo CHtml::link('写新文章',array('articles/create','username'=>Yii::app()->user->name));} ?>
                <?php if(Yii::app()->user->getState('isOwner')){echo CHtml::link('管理文章',array('articles/admin','username'=>Yii::app()->user->name));} ?>
                <?php if(Yii::app()->user->getState('isOwner')){echo CHtml::link('管理文章分类',array('articlesCategories/admin','username'=>Yii::app()->user->name));} ?>
		</div>
                <div class="clr"></div>
                <?php foreach ($articles as $key=>$article): ?>
                <div id="dNeirong">
                        <div id="dBiaoti">
                                <span class="FloatLeft"><?php echo CHtml::link($article->title, array('article','aid'=>$article->id,'username'=>$this->_user->username)); ?></span>
                                <span class="FloatRight"><?php echo CHtml::link('查看全文', array('article','aid'=>$article->id,'username'=>$this->_user->username)); ?>|
                                        评论(<?php echo CHtml::encode($article->countComments); ?>)|<span>发表日期：<?php echo date('Y-m-d', $article->createDate); ?></span></span>
                        <div class="clr"></div>
                        </div>
                        <div id="dzhengwen">
                                <?php echo CHtml::encode($article->summary); ?>
                        </div>
                </div>
                <?php endforeach ?>
                <?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
                </div>
        </div>
        <?php echo $this->renderPartial('sidebar_article') ?>
        <div class="clr"></div>
</div>