<?php

/**
 * This is the model class for table "urls".
 *
 * The followings are the available columns in table 'urls':
 * @property integer $created
 * @property integer $id
 * @property string $shortened
 * @property string $url
 */
class Url extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Url the static model class
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
		return 'urls';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array( 'url,shortened', 'required' ),
			array('created', 'numerical', 'integerOnly'=>true),
			array('shortened', 'length', 'max'=>50),
			array('url', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('created, id, shortened, url', 'safe', 'on'=>'search'),
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
			'created' => 'Created',
			'id' => 'Id',
			'shortened' => 'Shortened',
			'url' => 'Url',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('created',$this->created);

		$criteria->compare('id',$this->id);

		$criteria->compare('shortened',$this->shortened,true);

		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider('Url', array(
			'criteria'=>$criteria,
		));
	}
}
