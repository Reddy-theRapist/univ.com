<?php
	require 'person.inc';

	$people = array();

	function ParseThatDB()
	{
		echo "<p>".basename($_FILES["FUCKTHIS"]["name"]) ."</p><hr/>";

		if ($_FILES["FUCKTHIS"]["size"] > 20*1024*1024)
		{
			echo "Да ну нахуй, очень много\n";
			return;
		}

		$databaseFile ="uploads/".basename($_FILES["FUCKTHIS"]["name"]);

		if (move_uploaded_file($_FILES["FUCKTHIS"]["tmp_name"], $databaseFile))
 			echo "Вызов принят.\n";
	   else {echo "Да ну нахуй, сегодня не мой день.\n"; return;}

		FixErrors($databaseFile, $people);
		AnalyzeData($people);
	}

	function FixErrors(&$databaseFile, &$people)
	{
		$i=0;
		$handle = fopen($databaseFile,"r") or die('это неправильные пчелы');
		// $persons="";
		$EQ_Sex=0; $EQ_Email=0; $EQ_PhoneNumber=0;
		$EQ_MailingAddress=0;
		$temp="";

		file_put_contents("csvs/newone.txt",'',LOCK_EX);
		while (!feof($handle))
		{
			if ($i>0)
					$temp.="\r\n";
			$fields = explode(",",fgets($handle));
			$P = new Person($fields[0],$fields[1],$fields[2],$fields[3],$fields[4],$fields[5],
								 $fields[6],$fields[7],$fields[8],$fields[9],$fields[10],$fields[11],
								 $fields[12],$fields[13],$fields[14],$fields[15],$fields[16]);

			$EQ_Email+=$P->CheckFixEmail();
			$EQ_Sex+=$P->CheckFixSex();
			$EQ_PhoneNumber+=$P->CheckFixPhoneNumber();
			$EQ_MailingAddress+=$P->CheckFixMailingAddress();

			$P->FixID();
			$P->FixPhoneNumber();
			$P->FixBirthday();
			$P->FixWeight();
			$P->FixMailingAddress();

			$temp.=$P->__toString();
			$people[$i++]=$P;
		}
		fclose($handle);

		file_put_contents("csvs/newone.txt",$temp,LOCK_EX|FILE_APPEND);
		// file_put_contents('csvs/newone.txt',$P->__toString(),LOCK_EX);
		echo "<hr/>";
		echo 'Ошибок в email: '.$EQ_Email."<br/>";
		echo 'Ошибок в поле: '.$EQ_Sex."<br/>";
		echo 'Ошибок в телефоне: '.$EQ_PhoneNumber."<br/>";
		echo 'Ошибок в почтовом адресе: '.$EQ_MailingAddress."<br/>";

		$temp=implode("\n",$people);
		file_put_contents('csvs/newone.txt',$temp,LOCK_EX);
	}

	function AnalyzeData(&$people)
	{
		echo '47-му чуваку '.$people[47]->GetCurrentAge("YYYY") or die("nihuya ne ponyal");
//		ini_set('max_execution_time', 900);
		 echo '<hr/>Статистика:';
		 $Statistics=array("men count"=>0, "men's AVG height"=>0, "men's AVG weight"=>0, "men's AVG age"=>0,
	  							 "women count"=>0, "women's AVG height"=>0, "women's AVG weight"=>0, "women's AVG age"=>0,
								 "men w/ age>AVG"=>0,"men w/ weight>AVG"=>0,"men w/ height>AVG"=>0,
								 "men w/ age=AVG"=>0,"men w/ weight=AVG"=>0,"men w/ height=AVG"=>0,
								 "men w/ age<AVG"=>0,"men w/ weight<AVG"=>0,"men w/ height<AVG"=>0,
								 "women w/ age>AVG"=>0,"women w/ weight>AVG"=>0,"women w/ height>AVG"=>0,
								 "women w/ age=AVG"=>0,"women w/ weight=AVG"=>0,"women w/ height=AVG"=>0,
								 "women w/ age<AVG"=>0,"women w/ weight<AVG"=>0,"women w/ height<AVG"=>0,
								 "lowest age"=>-1, "YG_ID"=>0,
								 "max age"=>0, "EG_ID"=>0
							 );
		 if (count($people)>0)
		 	{
				$Statistics["lowest age"]=$people[0]->GetCurrentAge("DD");
				$Statistics["YG_ID"]=$people[0]->ID;
			}

			foreach ($people as $motherfucker)
//		 for ($i=0; $i <count($people); $i++)
		 {
		 	  //preparing categories based on gender
			  if ($people[$i]->Sex==1)
	 		  {
				    $Statistics["men count"]++;
					$Statistics["men's AVG height"]+=$people[$i]->Height;
		  		 	$Statistics["men's AVG weight"]+=$people[$i]->Weight;
		  		 	$Statistics["men's AVG age"]+=$people[$i]->GetCurrentAge("YYYY");
			  }
			  else
			  {
			  	   $Statistics["women count"]++;
					$Statistics["women's AVG height"]+=$people[$i]->Height;
		  		 	$Statistics["women's AVG weight"]+=$people[$i]->Weight;
		  		 	$Statistics["women's AVG age"]+=$people[$i]->GetCurrentAge("YYYY");
			  }
			  //preparing max/min age statistics
			  if ($people[$i]->GetCurrentAge("DD")>$Statistics["max age"])
		  		{
					$Statistics["max age"]=$people[$i]->GetCurrentAge("DD");
					$Statistics["EG_ID"]=$people[$i]->ID;
				}
			  if ($people[$i]->GetCurrentAge("DD")<$Statistics["lowest age"])
			  {
				  $Statistics["lowest age"]=$people[$i]->GetCurrentAge("DD");
				  $Statistics["YG_ID"]=$people[$i]->ID;
			  }
		 }

		 $Statistics["men's AVG height"]/=$Statistics["men count"];
		 $Statistics["men's AVG weight"]/=$Statistics["men count"];
		 $Statistics["men's AVG age"]/=$Statistics["men count"];

		 $Statistics["women's AVG height"]/=$Statistics["women count"];
		 $Statistics["women's AVG weight"]/=$Statistics["women count"];
		 $Statistics["women's AVG age"]/=$Statistics["women count"];

		 for ($i=0; $i <count($people); $i++)
		 {
			  if ($people[$i]->Sex==1)
	 		  {
				  	if ($people[$i]->Height<$Statistics["men's AVG height"])
				   	$Statistics["men w/ height<AVG"]++;
					else if ($people[$i]->Height==$Statistics["men's AVG height"])
						$Statistics["men w/ height=AVG"]++;
					else $Statistics["men w/ height>AVG"]++;

					if ($people[$i]->Weight<$Statistics["men's AVG weight"])
				   	$Statistics["men w/ weight<AVG"]++;
					elseif ($people[$i]->Weight==$Statistics["men's AVG weight"])
						$Statistics["men w/ weight=AVG"]++;
					else $Statistics["men w/ weight<AVG"]++;

					if ($people[$i]->GetCurrentAge("YY")<$Statistics["men's AVG age"])
				   	$Statistics["men w/ age<AVG"]++;
					elseif ($people[$i]->GetCurrentAge("DD")==$Statistics["men's AVG age"])
						$Statistics["men w/ age=AVG"]++;
					else $Statistics["men w/ age<AVG"]++;
			  }
			  else
			  {
				  if ($people[$i]->Height<$Statistics["women's AVG height"])
					  $Statistics["women w/ height<AVG"]++;
				  else if ($people[$i]->Height==$Statistics["women's AVG height"])
					  $Statistics["women w/ height=AVG"]++;
				  else $Statistics["women w/ height>AVG"]++;

				  if ($people[$i]->Weight<$Statistics["women's AVG weight"])
					  $Statistics["women w/ weight<AVG"]++;
				  else if ($people[$i]->Weight==$Statistics["women's AVG weight"])
					  $Statistics["women w/ weight=AVG"]++;
				  else $Statistics["women w/ weight<AVG"]++;

				  if ($people[$i]->GetCurrentAge("DD")<$Statistics["women's AVG age"])
					  $Statistics["women w/ age<AVG"]++;
				  else if ($people[$i]->GetCurrentAge("DD")==$Statistics["women's AVG age"])
					  $Statistics["women w/ age=AVG"]++;
				  else $Statistics["women w/ age<AVG"]++;
			  }
		 }



		 echo "<hr/>";
		 echo 'Количество мужчин:'.$Statistics["men count"]."<br/>";
		 echo 'Количество женщин:'.$Statistics["women count"]."<br/>";
		 echo 'Средний рост мужчин:'.$Statistics["men's AVG height"]."<br/>";
		 echo 'Средний вес мужчин:'.$Statistics["men's AVG weight"]."<br/>";
		 echo 'Средний возраст мужчин:'.$Statistics["men's AVG age"]."<br/>";
		 echo 'Средний рост женщин:'.$Statistics["women's AVG height"]."<br/>";
		 echo 'Средний вес женщин:'.$Statistics["women's AVG weight"]."<br/>";
		 echo 'Средний возраст женщин :'.$Statistics["women's AVG age"]."<br/>";
		 echo 'Количество мужчин с возрастом ВС:'.$Statistics["men w/ age>AVG"]."<br/>";
		 echo 'Количество мужчин с весом ВС:'.$Statistics["men w/ weight>AVG"]."<br/>";
		 echo 'Количество мужчин с ростом ВС:'.$Statistics["men w/ height>AVG"]."<br/>";
		 echo 'Количество мужчин с возрастом С:'.$Statistics["men w/ age=AVG"]."<br/>";
		 echo 'Количество мужчин с весом С:'.$Statistics["men w/ weight=AVG"]."<br/>";
		 echo 'Количество мужчин с ростом С:'.$Statistics["men w/ height=AVG"]."<br/>";
		 echo 'Количество мужчин с возрастом НС:'.$Statistics["men w/ age<AVG"]."<br/>";
		 echo 'Количество мужчин с весом НС:'.$Statistics["men w/ weight<AVG"]."<br/>";
		 echo 'Количество мужчин с ростом НС:'.$Statistics["men w/ height<AVG"]."<br/>";
		 echo 'Количество женщин с возрастом ВС:'.$Statistics["women w/ age>AVG"]."<br/>";
		 echo 'Количество женщин с весом ВС:'.$Statistics["women w/ weight>AVG"]."<br/>";
		 echo 'Количество женщин с ростом ВС:'.$Statistics["women w/ height>AVG"]."<br/>";
		 echo 'Количество женщин с возрастом С:'.$Statistics["women w/ age=AVG"]."<br/>";
		 echo 'Количество женщин с весом С:'.$Statistics["women w/ weight=AVG"]."<br/>";
		 echo 'Количество женщин с ростом С:'.$Statistics["women w/ height=AVG"]."<br/>";
		 echo 'Количество женщин с возрастом НС:'.$Statistics["women w/ age<AVG"]."<br/>";
		 echo 'Количество женщин с весом НС:'.$Statistics["women w/ weight<AVG"]."<br/>";
		 echo 'Количество женщин с ростом НС:'.$Statistics["women w/ height<AVG"]."<br/>";
		 echo '<hr/>';
		 $THOSE_GUYS=array("YOUNGEST"=>0,"ELDEST"=>0);
		 for ($i=0; $i < count($people); $i++)
		 {
			 if ($people[$i]->ID==$Statistics["YG_ID"])
		 			$THOSE_GUYS["YOUNGEST"]=$people[$i];
		    if ($people[$i]->ID==$Statistics["EG_ID"])
			 		$THOSE_GUYS["ELDEST"]=$people[$i];
		 }
		 echo 'Данные о самом старом человеке:<br/>';
		 echo $THOSE_GUYS["ELDEST"]->Name." ".$THOSE_GUYS["ELDEST"]->MiddleName." ".$THOSE_GUYS["ELDEST"]->LastName
		 ."; Топтает эту землю с ".$THOSE_GUYS["ELDEST"]->Birthday."; ".$THOSE_GUYS["ELDEST"]->PhoneNumber."; ".$THOSE_GUYS["ELDEST"]->MailingAddress." - ".$THOSE_GUYS["ELDEST"]->CountryCode;
		 echo '<br/>Данные о самом молодом человеке:<br/>';
		 echo $THOSE_GUYS["YOUNGEST"]->Name." ".$THOSE_GUYS["YOUNGEST"]->MiddleName." ".$THOSE_GUYS["YOUNGEST"]->LastName
		 ."; Топтает эту землю с ".$THOSE_GUYS["ELDEST"]->Birthday."; ".$THOSE_GUYS["YOUNGEST"]->PhoneNumber."; ".$THOSE_GUYS["YOUNGEST"]->MailingAddress." - ".$THOSE_GUYS["YOUNGEST"]->CountryCode;

//ini_set('max_execution_time', 30);
	}
 ?>
