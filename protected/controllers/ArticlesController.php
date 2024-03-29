<?php

class ArticlesController extends DController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

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
				'actions'=>array('list','show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'admin','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','ToIndex'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadArticles($_GET['id'])));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new Articles;
		if(isset($_POST['Articles']))
		{
                        $model->attributes=$_POST['Articles'];
                        if($model->validate()){
                           //这里用事务来处理，要是不用事务则把保存text的工作放到articles模型的afterSave事件中去处理
                           $transaction=Yii::app()->getDB()->beginTransaction();
                           $artText= new ArticlesText();
                            try{
                                $model->save();
                                $artText->articlesId= $model->id;
                                $artText->content= $model->content;
                                $artText->save();
                                $transaction->commit();
                                $this->redirect(array('show','id'=>$model->id,'username'=>Yii::app()->user->name));
                            }catch(Exception $e){
                                $transaction->rollBack();
                                throw $e;
                            }
                        }
				
		}
		$this->render('create',array('model'=>$model,
                                             'artCate'=>$this->loadArticlesCategory(),
                                             'gArtCate'=>$this->loadGlobalArticlesCategory(),
                              ));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadArticles($_GET['id']);
                $artText= ArticlesText::model()->findByPk(intval($_GET['id']));
                $model->content= $artText->content;
                if ($model->usersId != Yii::app()->user->id)
                    throw NEW CHttpException(404,'没有权限！');
		if (isset($_POST['Articles']))
		{
                    $model->attributes=$_POST['Articles'];
					$model->gacStatus=0;//防止用户通过之后修改内容为不允许推荐到首页去的
                    if($model->validate()){
                       $transaction=Yii::app()->getDB()->beginTransaction();
                        try{
                            $model->save();
                            $artText->articlesId= $model->id;
                            $artText->content= $model->content;
                            $artText->save();
                            $transaction->commit();
                            $this->redirect(array('show','id'=>$model->id,'username'=>Yii::app()->user->name));
                        }catch(Exception $e){
                            $transaction->rollBack();
                            throw $e;
                        }
                    }
		}
		$this->render('update',array('model'=>$model,
                                             'artCate'=>$this->loadArticlesCategory(),
                                             'gArtCate'=>$this->loadGlobalArticlesCategory(),
                                         ));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadArticles()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
                $this->redirect(array('blog/articles','username'=>Yii::app()->user->name));
                /**
                        $criteria=new CDbCriteria;
                        if (!Yii::app()->user->id)
                            $this->redirect(Yii::app()->user->loginUrl);
                        $criteria->addCondition('usersId='.Yii::app()->user->id);

                        $pages=new CPagination(Articles::model()->count($criteria));
                        $pages->pageSize=self::PAGE_SIZE;
                        $pages->applyLimit($criteria);

                        $models=Articles::model()->findAll($criteria);

                        $this->render('list',array(
                                'models'=>$models,
                                'pages'=>$pages,
                        ));
                /**/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;
                $criteria->order= 't.id DESC';
                $criteria->addCondition('t.usersId='.Yii::app()->user->id);

		$pages=new CPagination(Articles::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('Articles');
		$sort->applyOrder($criteria);

		$models=Articles::model()->with('artCate','gArtCate')->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

/**
 *首页打分类文章的管理
 */
	public function actionToIndex()
	{
		if(Yii::app()->getRequest()->isPostRequest)
		{
			//更新审核状态
			$criteria= new CDBcriteria;
			$criteria->addInCondition('id', $_POST['checked']);
			Articles::model()->updateAll(array('gacStatus'=>intval($_POST['gacStatus'])), $criteria);
			$this->refresh();
		}else{
			$criteria= new CDBcriteria;
			$criteria->condition='status=1 AND globalArticlesCategoriesId>1 AND gacStatus=:gs';
			$criteria->with=array('gArtCate');
			if (isset($_GET['gacStatus']))
				$criteria->params=array(':gs'=>$_GET['gacStatus']);
			else
				$criteria->params=array(':gs'=>0);
				
			$dataProvider=new CActiveDataProvider('Articles', array(
				'criteria'=>$criteria,
				'sort'=>array('defaultOrder'=>'t.id DESC'),
			));
		
			$this->render('toindex',array(
				'dataProvider'=>$dataProvider,
			));
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadArticles($id=null)
	{
                if($this->_model===null)
                {
                        if ($id!==null || $_GET['id'])
                            $this->_model=Articles::model()->findByPk($id? $id: $_GET['id'],'usersId=:uid AND blogsId=:bid',array(':uid'=>Yii::app()->user->id,':bid'=>Yii::app()->user->blogId));
                        if($this->_model===null)
                            throw new CHttpException(404,'The requested page does not exist.');
                }
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadArticles($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}

        public function loadArticlesCategory (){
            $artCate= ArticlesCategories::model()->findAll('usersId=:uid',array(':uid'=>Yii::app()->user->id));
            if ($artCate==null)
                $this->redirect(array('articlesCategories/create','username'=>Yii::app()->user->name));
                //throw new CHttpException(404, '没有个人分类，请先创建分类！');
            return $artCate;
        }

        public function loadGlobalArticlesCategory (){
            return GlobalArticlesCategories::model()->findAll();
        }

}
