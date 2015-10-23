<?php

/**
 * This is the model class for table "learner".
 *
 * The followings are the available columns in table 'learner':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $birthdate
 * @property string $level
 * @property string $tp
 */
class Learner extends CActiveRecord
{
	public $tp;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'learner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, email, birthdate, level', 'required'),
			array('name, email', 'length', 'max'=>100),
 			array('name', 'unique', 'className' => 'Learner', 'attributeName' => 'name', 'message'=>'This Name is already in use'),			
			array('email', 'email'),
			array('level', 'length', 'max'=>2),
			array('level', 'match', 'pattern'=>'/^(A1|A2|B1|B2|C1|C2)$/ui', 'message'=>'Common Reference Levels could only contain A1, A2, B1, B2, C1, C2'),
			array('birthdate','date','format'=>'dd/MM/yyyy'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, email, birthdate, level', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'teachersB' => array(self::HAS_MANY, 'lt', 'learner_id'),
			'teachers' => array(self::HAS_MANY, 'teacher', 'teacher_id', 'through' => 'teachersB'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'email' => 'Email',
			'birthdate' => 'Birthdate',
			'level' => 'Level',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('level',$this->level,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Learner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function GetLevelsForForm()
	{
		return array( 
			'A1' => 'A1 ,Basic user,Breakthrough or beginner' ,
			'A2' => 'A2 ,Basic user,Way stage or elementary' ,
			'B1' => 'B1, Independent user,Threshold or intermediate' ,
			'B2' => 'B2, Independent user,Vantage or upper intermediate' ,
			'C1' => 'C1, Proficient user,Effective operational proficiency or advanced' ,
			'C2' => 'C2, Proficient user,Mastery or proficiency' ,
		);
	}
	public function beforeSave()
	{
		if ( preg_match( '/(\d{2})\/(\d{2})\/(\d{4})/' , $this->birthdate , $match_date ) )
		{
			$this->birthdate = "{$match_date[3]}-{$match_date[2]}-{$match_date[1]}";
		}
		return parent::beforeSave();
	}
	public function ConvertDateForPage( $date )
	{
		$ret = $date;
		if ( preg_match( '/(\d{4})-(\d{2})-(\d{2})/' , $date , $match_date ) )
		{
			$ret = "{$match_date[3]}/{$match_date[2]}/{$match_date[1]}";
		}
		return $ret;
	}
	public function searchWithTeacher($id)
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('birthdate',$this->birthdate,true);
		$criteria->compare('level',$this->level,true);

		$criteria->join = 'LEFT JOIN l_t as lt ON lt.learner_id = t.id AND lt.teacher_id ='.$id;
		$criteria->select = array( 'lt.teacher_id as tp' , 't.*');
				
		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                    'pageSize'=>20,
					'route'=>'learner/toteacher' ,
			),
			
            'sort'=>array(
					'route'=>'learner/toteacher' ,
                    'defaultOrder'=>array(
                            'id'=>CSort::SORT_ASC,
							),
                    'attributes'=>array(
                            'id'=>array(
                                    'asc'=>'id',
                                    'desc'=>'id DESC',
							),
                            '*',
					),
			),
		));
	}
	public function getTeacherPresent()
	{
		Yii::log("getTeacherPresent ithis=[".print_r( $this , true )."]" , "warning");
	}
	public function ProcessLink( $teacher , $learner , $to )
	{
		// проверить что есть
		$sql = "SELECT COUNT(*) FROM l_t WHERE teacher_id=$teacher AND learner_id=$learner";
		$res = Yii::app()->db->createCommand( $sql )->queryScalar();
		Yii::log("ProcessLink teacher=[$teacher],learner=[$learner],to=[$to],res=[$res]","warning");
		if ( 'set' == $to )
		{
			if ( false !== $res && 0 == $res )
			{
				// добавить если нет
				$arr_insert = array( 'teacher_id' => $teacher , 'learner_id' => $learner );
				Yii::app()->db->createCommand()->insert( 'l_t' , $arr_insert );
			}
		} else if ( 'unset' == $to ) {
			// удалить
			if ( $res )
			{
				$sql = "DELETE FROM l_t WHERE teacher_id=$teacher AND learner_id=$learner LIMIT 1";
				Yii::app()->db->createCommand( $sql )->execute();
			}
		}
		return 'ok';
	}
}
