<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
    public function init()
    {
            $this->layout = false;
    }
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

    $model=new SearchForm;
    if(isset($_POST['SearchForm']))
    {
        // collects user input data
        $model->attributes=$_POST['SearchForm'];
        if($model->search_type=="file")
        $model->inputForm=CUploadedFile::getInstance($model,'inputForm');
		//print_r($model->inputForm);

        // validates user input and redirect to previous page if validated

        if($model->validate())
        {

        	$filename=rand(0,1000000*5);
        	$path=Yii::app()->params['files'];
        	$fullpath=$path.'/'.$filename;
        	$document="";
        	if($model->search_type=="text")
        	{
        		$document=$model->inputForm;
        		$document=mb_convert_encoding($document , 'UTF-8', 'UTF-8');
        	}
        	else if($model->search_type=="file")
        	{
        		$model->inputForm->saveAs($fullpath);
        		$document=$this->read_doc($path,$filename);
        		$document=mb_convert_encoding($document , 'UTF-8', 'UTF-8');	
        	}
			$post = [
			    'wt' => 'json',
			    'q' => urlencode($document),
			    'mlt'=>'true',
			    'mlt.fl'=>'manu,cat',
			    'mlt.mindf'=>'1',
			    'fl'=>'id,score,title,description,dc_creator',
			    'mlt.interestingTerms'=>'details',
			    'mlt.boost'=>'true',
			    'wt'=>'json'
			];

			$ch = curl_init('http://localhost:8983/solr/gettingstarted_shard1_replica1/select');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$result = curl_exec($ch);
			curl_close($ch);

			$search_type=$model->search_type;
			$value=$model->inputForm;
        	$model=new SearchForm;
        	$this->render('index',array('document'=>strtolower($document),'SearchForm'=>$model,'result'=>$result,'search_type'=>$search_type,'value'=>$value ));
        	Yii::app()->end();
            //$this->redirect(Yii::app()->user->returnUrl);
        }
    }
    // displays the login form
    $this->render('index',array('SearchForm'=>$model));
	}

	private function read_doc($path,$filename) {
		$locale = 'tr_TR.utf-8';
		setlocale(LC_ALL, $locale);
		putenv('LC_ALL='.$locale);
		shell_exec('catdoc -x -a '.$path.'/'.$filename.'>'.$path.'/'.$filename.'.txt');
		$outtext=file_get_contents($path.'/'.$filename.'.txt');
		$outtext=mb_convert_encoding($outtext, "UTF-8", "UTF-8");
		$outtext = preg_replace("/[^A-Za-z0-9?!şıöüğçİŞÖĞÜÇ\s]/","",strtolower($outtext));
	    return str_replace(["\n","\r"]," ",strtolower($outtext));
	}
	public function just_read_doc($path,$file) {
		$locale = 'tr_TR.utf-8';
		setlocale(LC_ALL, $locale);
		putenv('LC_ALL='.$locale);
		$remotePath=Yii::app()->params['files'].'/'.$file.'.txt';
		/*$remotePath=str_replace(' ', '\\ ',$remotePath);
		$path=str_replace(' ','\\ ',$path);
		$file=str_replace(' ','\\ ' , $file);*/
		$result=shell_exec('catdoc -x -a '.$path.'>'.$remotePath);
		error_log("RESULT->".$result.'catdoc -x -a '.$path.'>'.Yii::app()->params['files'].'/'.$file.'.txt');
		$outtext=file_get_contents(Yii::app()->params['files'].'/'.$file.'.txt');
		$outtext=mb_convert_encoding($outtext, "UTF-8", "UTF-8");
		$outtext = preg_replace("/[^A-Za-z0-9?!şıöüğçİŞÖĞÜÇ\s]/","",$outtext);
		$outtext=mb_convert_encoding($outtext , 'UTF-8', 'UTF-8');
	    return str_replace(["\n","\r"]," ",strtolower($outtext));
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}