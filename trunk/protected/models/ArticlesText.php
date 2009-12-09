<?php

class ArticlesText extends CActiveRecord
{
	/**
	 * The followings are the available columns in table '{{ArticlesText}}':
	 * @var integer $articlesId
	 * @var string $content
	 * @var string $noHtmlContent
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
		return '{{ArticlesText}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('articlesId', 'required'),
			array('articlesId', 'numerical', 'integerOnly'=>true),
			array('articlesId, content', 'safe'),
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
                    'articles'=>array(self::BELONGS_TO,'articles','articelsId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'articlesId' => '文章ID',
			'content' => '内容',
			'noHtmlContent' => '纯文本内容',
		);
	}
        /**
         *  保存前设置noHtmlContent内容并清除不需要的格式字符
         * @return <type>
         */
        protected function beforeSave(){
            $this->noHtmlContent= strip_tags($this->content);
            $this->noHtmlContent= preg_replace("/^\r\n|\r\n$|[\f\t\v]+|[ ]*&nbsp;/",'',$this->noHtmlContent);
            $this->noHtmlContent= preg_replace("/[\r\n]{4,}/","\n",$this->noHtmlContent);
            return true;
        }
}