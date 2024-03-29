<?php


class PageController extends Controller
{

	const PAGE_SIZE=25;

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function init()
	{
		Yii::import('application.vendors.*');
	}

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

     /**
      * Displays a particular model.
      */
     public function actionView()
     {
         $title = Yii::app()->request->getParam( 'title', NULL );

         $model = Page::model()->findByAttributes( array('title' => $title), array('order'=>'revision DESC') );

         if( ! $model )
         {
             throw new CHttpException(404,'Sajnos a kert oldal nem letezik.');
         }

         $this->render('view',array(
             'model'=>$model,
         ));
     }


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Page;
		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			if($model->save())
				$this->redirect(array('view','title'=>$model->title));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		// $model=$this->loadModel();
		$title = Yii::app()->request->getParam( 'title' );
        $model = Page::model()->findByAttributes( array('title' => $title), array('order'=>'revision DESC') );
		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			if($model->save())
				$this->redirect(array('view','title'=>$model->title));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
/*	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
*/

	/**
	 *
	 */
	public function actionDelete()
	{
		if( Yii::app()->request->isPostRequest )
		{
			$id=Yii::app()->request->getParam( 'id' );
			if( $id )
			{
				$model = Page::model()->findByPk( $id );
				if( $model )
				{
					Page::model()->deleteAllByAttributes( array( 'title' => $model->title )  );
					$this->redirect(array('index'));
				}
			}
		}
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $criteria = new CDbCriteria();
        $criteria->group = 'title';
        $criteria->order = 'created DESC';

		$dataProvider=new CActiveDataProvider('Page', array(
			'pagination'=> array( 'pageSize' => self::PAGE_SIZE ),
            'criteria' => $criteria,
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$dataProvider=new CActiveDataProvider('Page', array(
			'pagination'=>array(
				'pageSize'=>self::PAGE_SIZE,
			),
		));

		$this->render('admin',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Page::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
