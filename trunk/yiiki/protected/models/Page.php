<?php

class Page extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'pages':
	 * @var string $body
	 * @var integer $created
	 * @var integer $id
	 * @var integer $revision
	 * @var string $title
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created, revision', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>125),
              array('title', 'required', 'message'=>'Cimke nem lehet ures!'),
              array('title', 'unique'),
              array('title',
                      'match',
                      'pattern'=>'/^[A-Za-z0-9_]+$/',
                      'message' => 'Csak szamokat, betuket es `_` jelet hasznalhatsz! Hehe' ),
			array('body', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'body' => 'Body',
			'created' => 'Created',
			'id' => 'Id',
			'revision' => 'Revision',
			'title' => 'Title',
		);
	}

      /**
       *
       */
      public function beforeSave()
      {
         // and setting the created date ...
         $this->created=time();

         return parent::beforeSave();
      }

     /**
      *
      * @return <type>
      */
     public function save( $validate = true )
     {
         if( $this->isNewRecord )
         {
             // we increase the revision number ...
             $this->revision = $this->revision+1;
             return parent::save( $validate );
         }
         else
         {
             // by setting `save` to false, it will skip the validation,
             // so we can save the page with the same title
             // also, update is not really an update because every single change
             // will be a "new" page, so we can keep history ...
             $newpage = new Page();
             $newpage->attributes = $this->attributes;
             $newpage->save(false);
             return true;
         }
     }
}