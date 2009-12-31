<?php

class BlogController extends DController
{
	const PAGE_SIZE=10;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
		array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('index','articles','article','galleryalbums','gallery','guestbook','addFriend','addSms'),
				'users'=>array('*'),
		),
		array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update'),
				'users'=>array('@'),
		),
		array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','admin','delete'),
				'users'=>array('admin'),
		),
		array('deny',  // deny all users
				'users'=>array('*'),
		),
		);
	}
        /**
         *  博客首页
         */
        public function actionIndex(){
                //下面是为文章分页
                $acriteria= new CDbCriteria();
                $acriteria->condition= 'blogsId=:bid AND status=1';
                $acriteria->params= array(':bid'=>$this->_blog->id);
                $acriteria->order= 'createDate DESC';
                $pages= new CPagination(Articles::model()->count($acriteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($acriteria);
                
                $articles= Articles::model()->findAll($acriteria);
                $galleries= Gallery::model()->findAll('blogsId=:bid AND status=1 ORDER BY id DESC LIMIT 6', array(':bid'=>$this->_blog->id));
                if ($this->_user['userType']==2){
                        $gongying= Articles::model()->findAll('blogsId=:bid AND globalArticlesCategoriesId=2 AND status=1 ORDER BY id DESC LIMIT 5', array(':bid'=>$this->_blog->id));
                        $gongying= Articles::model()->findAll('blogsId=:bid AND globalArticlesCategoriesId=3 AND status=1 ORDER BY id DESC LIMIT 5', array(':bid'=>$this->_blog->id));
                }else{
                        $gongying= array();
                        $qiqgou  = array();
                }
                $this->render('index', array(
                                             'articles'=>$articles,
                                             'pages'=>$pages,
                                             'galleries'=>$galleries,
                                             'gongying'=>$gongying,
                                             'qiqgou'=>$qiugou,
                                        ));
        }
        /**
         *  文章列表页
         */
        public function actionArticles(){
                //下面是为文章分页
                $acriteria= new CDbCriteria();
                $acriteria->condition= 'blogsId=:bid AND status=1';
                $acriteria->params= array(':bid'=>$this->_blog->id);
                $acriteria->order= 'createDate DESC';
                $pages= new CPagination(Articles::model()->count($acriteria));
		$pages->pageSize=self::PAGE_SIZE * 2;//这里可以根据用户博客设置来决定显示多少
		$pages->applyLimit($acriteria);

                $articles= Articles::model()->findAll($acriteria);
                $this->render('articles', array(
                                             'articles'=>$articles,
                                             'pages'=>$pages,
                                        ));
        }
        /**
         *  文章页
         */
        public function actionArticle(){
                //添加评论
                $comment= new ArticlesComments;
                if ($_POST['ArticlesComments']){
                        $comment= new ArticlesComments;
                        $comment->attributes= $_POST['ArticlesComments'];
                        $comment->blogsId= $this->_blog->id;
                        $comment->status= 1;//这里可以根据文章或blog的设置来设置；
                        if ($_POST['isLogin']==1){
                                $comment->usersId=Yii::app()->user->id;
                                $comment->userName=Yii::app()->user->name;
                        }
                        if ($comment->save()){
                                Articles::model()->updateCounters(array('countComments'=>1), 'id=:aid', array(':aid'=>$comment->articlesId));
                                Yii::app()->user->setFlash('addcommment','添加评论成功！');
                                $this->redirect(array('article','aid'=>$comment->articlesId,'username'=>$this->_user->username,'#'=>$comment->id));
                        }
                }
                //显示文章
                $aid= intval($_GET['aid']);
                if (!$aid)
                        throw new CHttpException (404,'参数错误！文章id无效');
                $article= Articles::model()->with('artText','comments','comments.user')->findByPk($aid, '{{articles}}.usersId=:uid AND {{articles}}.status=1', array(':uid'=>$this->_user->id));
                if ($article==null)
                        throw new CHttpException(404);
                if (Yii::app()->user->getState('viewArt'.$aid)!=1){
                        Articles::model()->updateCounters(array('countReads'=>1), 'id=:aid', array(':aid'=>$aid));
                }
                Yii::app()->user->setState('viewArt'.$aid,1);
                $this->render('article', array('article'=>$article,
                                               'commentModel'=>$comment,
                                        ));
        }

        /**
         *  相册列表页
         */
        public function actionGalleryAlbums(){

                $this->render('GalleryAlbums', array());
        }
        /**
         *  相片页
         */
        public function actionGallery(){

                $this->render('Gallery', array());
        }
        /**
         *  留言板列表页
         */
        public function actionGuestbook(){
                $this->render('Guestbook', array());
        }
        /**
         * 添加好友
         */
         public function actionAddFriend(){
                $uid= $_GET['uid'];
                if (Yii::app()->user->id==$uid)
                        Yii::app()->DRedirect->redirect(Yii::app()->getRequest()->getUrlReferrer(),'自己和自己这么熟了还要加好友？系统不允许哦^_^');
                if (Yii::app()->user->isGuest){
                        Yii::app()->user->returnUrl=$this->createUrl('addfriend',array('uid'=>$uid));
                        Yii::app()->DRedirect->redirect(array('site/login'),'需要登录才能添加好友！',3,false);
                        Yii::app()->user->returnUrl=$this->createUrl('site/index');
                }else{
                        if(Friends::model()->exists('userId=:uid AND friendId=:fid', array(':uid'=>Yii::app()->user->id,':fid'=>$uid))){
                                Yii::app()->DRedirect->redirect(Yii::app()->getRequest()->getUrlReferrer(),'已经在你的好友列表了！不需要重复添加^_^');
                        }else{
                                $friend= new friends;
                                $friend->userId   = Yii::app()->user->id;
                                $friend->friendId = $uid;
                                $friend->status  =1;
                                $friend->save();
                                Yii::app()->DRedirect->redirect(Yii::app()->getRequest()->getUrlReferrer(),'添加成功！');
                        }
                }
         }
        /**
         * 添加悄悄话
         */
         public function actionAddSms(){

                 $this->render('addSms', array());
         }
}