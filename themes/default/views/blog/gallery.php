<div id="webMain">
    	<div id="webLeftmain">
    	  <div id="webPhoto">
       	    <div id="pBiaoti"><?php echo CHtml::link('&gt;&gt;我的相册', array('galleryAlbums','username'=>$this->_user->username), array('class'=>'dTitle')); ?>
                    <span class="r"><?php if(Yii::app()->user->getState('isOwner')){echo CHtml::linkButton('删除照片',array('submit'=>array('gallery/delete','id'=>$gallery->id,'username'=>Yii::app()->user->name),'confirm'=>'确定删除?'));} ?></span>
            </div>
                <div id="xiangceDetails" align="center">
                      <?php echo CHtml::image($gallery->getGalleryUrl(), $gallery->title); ?><br />
                      <?php echo CHtml::encode($gallery->title); ?>
                      <br />
                  <?php echo CHtml::link('下载', array($gallery->getGalleryUrl())); ?> (<?php echo round($gallery->fileSize/1024,2); ?> KB) |   上传时间：<?php echo date('Y-m-d H:i:s',$gallery->createDate) ; ?><br />
                  <?php echo CHtml::link('返回相册',array('galleries','gaid'=>$gallery->galleryAlbumsId,'username'=>$this->_user->username)); ?>
                </div>
    	  </div>
        </div>
        <?php echo $this->renderPartial('sidebar_gallery') ?>
        <div class="clr"></div>
</div>