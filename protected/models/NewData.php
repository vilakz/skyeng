<?php

class NewData {
	/**
	 * Truncate and generate new data
	 */
	static public function CreateNewData()
	{
		// очистка всех таблиц
		Yii::app()->db->createCommand( "TRUNCATE TABLE l_t" )->execute();
		Yii::app()->db->createCommand( "TRUNCATE TABLE teacher" )->execute();
		Yii::app()->db->createCommand( "TRUNCATE TABLE learner" )->execute();
		
		// наполнение teacher 10k
		// name gender phone
		$i = 0;
		$j = 0;
		do {
			++$j;
			if ( $j > 1000000 )
			{
				break;
			}
			$name = static::GenerateName();
			// проверить что такого имени нет
			$sql = "SELECT COUNT(*) FROM teacher WHERE name='$name'";
			$res = Yii::app()->db->createCommand( $sql )->queryScalar();
			if ( false !== $res && 0 == $res )
			{
				$gender_t = mt_rand( 0 , 1 );
				$gender = ($gender_t ? 'male' : 'female');
				
				$phone = mt_rand( 100000000 , 9000000000 );
				$arr_insert = array();
				$arr_insert['name'] = $name;
				$arr_insert['gender'] = $gender;
				$arr_insert['phone'] = $phone;
				$row_aff = Yii::app()->db->createCommand()->insert( 'teacher' , $arr_insert );

			} else {
				continue;
			}
			
			++$i;
		} while( $i < 10000 );
		
		// наполнение learner 100k
		// name email birthdate level
		$i = 0;
		$j = 0;
		do {
			++$j;
			if ( $j > 5000000 )
			{
				break;
			}
			$name = static::GenerateName();
			// проверить что такого имени нет
			$sql = "SELECT COUNT(*) FROM learner WHERE name='$name'";
			$res = Yii::app()->db->createCommand( $sql )->queryScalar();
			if ( false !== $res && 0 == $res )
			{
				$email = static::GenerateWord( mt_rand( 3, 7 ) ).'@'.static::GenerateWord( mt_rand( 3, 7 ) ).'.'.static::GenerateWord( mt_rand( 3, 7 ) );
				$birthdate = mt_rand( 1920 , date("Y") - 4 ).'-'.sprintf( "%1$02d" , mt_rand( 1 , 12 ) ).'-'.sprintf( "%1$02d" , mt_rand( 1 , 28 ) );
				$arr_level = array( 'A1' ,'A2' ,'B1' ,'B2' ,'C1' ,'C2' );
				$level = $arr_level[ mt_rand( 0 , count( $arr_level ) - 1 ) ];
				
				$arr_insert = array();
				$arr_insert['name'] = $name;
				$arr_insert['email'] = $email;
				$arr_insert['birthdate'] = $birthdate;
				$arr_insert['level'] = $level;
				$row_aff = Yii::app()->db->createCommand()->insert( 'learner' , $arr_insert );

			} else {
				continue;
			}
			
			++$i;
		} while( $i < 100000 );
		
	}
	static public function GenerateName()
	{
		$ret = '';
		$f = mt_rand( 3, 7 );
		$ret .= ucfirst( static::GenerateWord( $f ) );
		
		$s = mt_rand( 3, 7 );
		$ret .= ' '.ucfirst( static::GenerateWord( $s ) );
		
		return $ret;
	}
	static public function GenerateWord( $length )
	{
		$ret = "";
		for ($i = 0; $i < $length; ++$i )
		{
			$ret .= chr(mt_rand(97, 122)); // 97 - это a, а 122 - это z
		}
		return $ret;
	}
}