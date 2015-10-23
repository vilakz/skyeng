<?php

/**
 * This is the model class for table "teacher".
 *
 * The followings are the available columns in table 'teacher':
 * @property string $id
 * @property string $name
 * @property string $gender
 * @property string $phone
 */
class Teacher extends CActiveRecord
{
	/**
	 * For 4 view out
	 * @var integer
	 */
	public $count_learners;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teacher';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, phone', 'required'),
			array('name, phone', 'length', 'max'=>100),
 			array('name', 'unique', 'className' => 'Teacher', 'attributeName' => 'name', 'message'=>'This Name is already in use'),			
			array('phone', 'match', 'pattern'=>'/^[0-9\(\)\-\ ]+$/u', 'message'=>'Telephone number could only contain "() 0-9 -" symbols'),
			array('gender', 'length', 'max'=>7),
			array('gender', 'match', 'pattern'=>'/^(male|female)$/ui', 'message'=>'Gender could only contain male or female'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, gender, phone', 'safe', 'on'=>'search'),
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
			'learnersB' => array(self::HAS_MANY, 'lt', 'teacher_id'),
			'learners' => array(self::HAS_MANY, 'learner', 'learner_id', 'through' => 'learnersB'),
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
			'gender' => 'Gender',
			'phone' => 'Phone',
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
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('phone',$this->phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Teacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function searchWithLearners()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('phone',$this->phone,true);
		
		
		$criteria->join = 'left join l_t as lt ON lt.teacher_id = t.id';
		$criteria->condition = 'lt.teacher_id IS NOT NULL';
		$criteria->group = 'lt.teacher_id';
		$criteria->select = array( 'COUNT( lt.teacher_id) as count_learners' , 't.*');
				
		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                    'pageSize'=>25,
					//'route'=>'learner/toteacher' ,
			),
			
            'sort'=>array(
					//'route'=>'learner/toteacher' ,
                    'attributes'=>array(
                            '*',
					),
			),
		));
		
	}
	public function searchApril()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('phone',$this->phone,true);
/*		
select * from teacher where id IN ( select complex_res.id from 
( SELECT t.id as id ,  sum(case when MONTH( l.birthdate ) = 4 then 1 else 0 end) as apr , sum(case when MONTH( l.birthdate ) != 4 then 1 else 0 end)  as aprx FROM `teacher` t
left join l_t as lt ON lt.teacher_id = t.id
inner join learner as l ON l.id = lt.learner_id
group by t.id
having apr > 0 AND aprx = 0 ) as complex_res
)	
	
*/
		$criteria->condition = 
'id IN ( select complex_res.id from 
( SELECT t.id as id ,  sum(case when MONTH( l.birthdate ) = 4 then 1 else 0 end) as apr , sum(case when MONTH( l.birthdate ) != 4 then 1 else 0 end)  as aprx FROM `teacher` t
left join l_t as lt ON lt.teacher_id = t.id
inner join learner as l ON l.id = lt.learner_id
group by t.id
having apr > 0 AND aprx = 0 ) as complex_res
)	';
		$criteria->select = "*";
				
		return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                    'pageSize'=>25,
					//'route'=>'learner/toteacher' ,
			),
			
            'sort'=>array(
					//'route'=>'learner/toteacher' ,
                    'attributes'=>array(
                            '*',
					),
			),
		));
		
	}
	public function searchCross()
	{
		$ret = array();
			$sql = 
			"select 
				CONCAT_WS( '_' , lt.teacher_id , lt2.teacher_id ) as pair,
				sum( CASE WHEN lt.learner_id = lt2.learner_id THEN 1  ELSE 0 END ) AS sum_equal 
			from l_t as lt
			inner join l_t as lt2 ON lt.teacher_id < lt2.teacher_id AND lt.teacher_id != lt2.teacher_id
			group by pair
			order by sum_equal DESC
			limit 1 ";
			
			$res = Yii::app()->db->createCommand( $sql )->queryRow();
			if ( $res )
			{
				list( $teacher_id1 , $teacher_id2 ) = explode( '_' , $res['pair'] );
				
				$sql = 
				"SELECT * 
				FROM learner
				WHERE id
				IN (
					SELECT learner_id
					FROM l_t
					WHERE l_t.teacher_id
					IN ( $teacher_id1, $teacher_id2 ) 
					GROUP BY learner_id
					HAVING count( learner_id ) =2
				)
				";  
				$ret['learners'] = $sql;
				$ret['teachers'] = "SELECT * FROM teacher WHERE id IN ( $teacher_id1, $teacher_id2 )";
				$ret['count'] = $res['sum_equal'];
				
				/*
				$res_learners = Yii::app()->db->createCommand( $sql )->queryAll();
				$res_teachers = Yii::app()->db->createCommand( "SELECT * FROM teacher WHERE id IN ( $teacher_id1, $teacher_id2 )" )->queryAll();
				$ret['learners'] = $res_learners;
				$ret['teachers'] = $res_teachers;
				*/
			}
		return $ret;
	}
}
