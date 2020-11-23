<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/user';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('index','Profile'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('allow', 
				'actions'=>array('register'),
				'users'=>array('?'),
			),
			array('allow', 
				'actions'=>array('change'),
				'users'=>array('@'),
			),
			array('deny', 
				'actions'=>array('register'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionProfile($id)
	{
		$model = User::model()->with("comments.user.avatar")->findByPk($id);


		$comment=$this->newComment($model);
		$avatar = $this->addAvatar($model);
		$t=$this->render('view',array(
			'model'=>$model,
			'comment'=>$comment,
			'avatar'=>$avatar,
			'comments'=>$model->comments,
		));
	}

	protected function newComment($user) {
		$comment = new Comments;
		if (isset($_POST["Comments"])) {
			$comment->attributes=$_POST["Comments"];
			$comment->for_user_id=$user->id;
			$comment->user_id=Yii::app()->user->id;
			$comment->date=date("d.m.Y H:m:s");
			if ($comment->save()) {
				$this->refresh();
			}
		}
		return $comment;
	}

	protected function addAvatar($user) {
		if ($user->avatar) {
			$avatar=$user->avatar;
		}
		else {
			$avatar = new Avatars;
		}
		if (isset($_POST["Avatars"])) {
			$avatar->attributes=$_POST["Avatars"];
			$file = CUploadedFile::getInstance($avatar,'path');
			$avatar->path=Yii::app()->user->id."_".$file;
			$avatar->user_id=Yii::app()->user->id;
			if($avatar->save()){
				$file->saveAs(Yii::getPathOfAlias('webroot').'/upload/avatars/'.$avatar->path);
				Yii::app()->user->setState("avatar",$avatar->path);
				$this->refresh();
			}
		}
		return $avatar;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionRegister()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->password=CPasswordHelper::hashPassword($model->password);
			if($model->save())
				$this->redirect(array('site/login'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$id = Yii::app()->user->id;
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionChange()
	{
			$id = Yii::app()->user->id;
			$model=$this->loadModel($id);

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(Yii::app()->request->isAjaxRequest)
			{
				
				$model->attributes=$_POST['user'];
				if($model->save()) 
					echo "ok";
				Yii::app()->end();
			}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}