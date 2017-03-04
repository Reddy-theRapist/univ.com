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
		MailServerClients($people);
		HolidayBirthdays($people);

		echo "<form style=\"margin:10px;\" action=\"?php echo htmlspecialchars($_SERVER[PHP_SELF]); ?>\" method=\"GET\">
				<div class=\"form-group\">
					<input style=\"margin:5px;\" type=\"text\" name=\"regionSearch\" value=\"Код региона\" />
					<input style=\"margin:5px;\" type=\"submit\" name=\"SubmitRegionSearch\" value=\"Найти людей в регионе\" />
				</div>
			<hr/>
		</form>";
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
			$fields = explode(",",fgets($handle));
			if (count($fields)>1)
			{
//				if ($i>0)
//                    $temp.=PHP_EOL;
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

				$temp.=$P->Info();
				$people[$i++]=$P;
			}
		}
		fclose($handle);

		file_put_contents("csvs/newone.txt",$temp,LOCK_EX);
		// file_put_contents('csvs/newone.txt',$P->__toString(),LOCK_EX);
		echo "<hr/>";
		echo 'Ошибок в email: '.$EQ_Email."<br/>";
		echo 'Ошибок в поле: '.$EQ_Sex."<br/>";
		echo 'Ошибок в телефоне: '.$EQ_PhoneNumber."<br/>";
		echo 'Ошибок в почтовом адресе: '.$EQ_MailingAddress."<br/>";

//		$temp=implode("\n",$people);
//		file_put_contents('csvs/newone.txt',$temp,LOCK_EX);
	}

	function AnalyzeData(&$people)
	{
//		echo '47-му чуваку '.$people[47]->GetCurrentAge("YYYY") or die("nihuya ne ponyal");
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
								 "SHIT"=>-1, "FUCK"=>0,
								 "max age"=>0, "EG_ID"=>0
							 );
		 if (count($people)>0)
		 	{
                $Statistics["SHIT"]=$people[0]->GetCurrentAge("DD");
                $Statistics["FUCK"]=$people[0]->ID;
			}

			foreach ($people as $motherfucker)
		 {
		 	  //preparing categories based on gender
			  if ($motherfucker->Sex==1)
	 		  {
				    $Statistics["men count"]++;
					$Statistics["men's AVG height"]+=$motherfucker->Height;
		  		 	$Statistics["men's AVG weight"]+=$motherfucker->Weight;
		  		 	$Statistics["men's AVG age"]+=$motherfucker->GetCurrentAge("DD");
			  }
			  else
			  {
			  	   $Statistics["women count"]++;
					$Statistics["women's AVG height"]+=$motherfucker->Height;
		  		 	$Statistics["women's AVG weight"]+=$motherfucker->Weight;
		  		 	$Statistics["women's AVG age"]+=$motherfucker->GetCurrentAge("DD");
			  }
			  //preparing max/min age statistics
			  if ($motherfucker->GetCurrentAge("DD")>$Statistics["max age"])
		  		{
					$Statistics["max age"]=$motherfucker->GetCurrentAge("DD");
					$Statistics["EG_ID"]=$motherfucker->ID;
				}
			  if ($motherfucker->GetCurrentAge("DD")<$Statistics["SHIT"]&&$motherfucker->GetCurrentAge("DD")>0)
			  {
				  $Statistics["SHIT"]=$motherfucker->GetCurrentAge("DD");
				  $Statistics["FUCK"]=$motherfucker->ID;
			  }
		 }

		 $Statistics["men's AVG height"]=round($Statistics["men's AVG height"]/$Statistics["men count"]);
		 $Statistics["men's AVG weight"]=round($Statistics["men's AVG weight"]/$Statistics["men count"]);
		 $Statistics["men's AVG age"]=round($Statistics["men's AVG age"]/365/$Statistics["men count"]);

		 $Statistics["women's AVG height"]=round($Statistics["women's AVG height"]/$Statistics["women count"]);
		 $Statistics["women's AVG weight"]=round($Statistics["women's AVG weight"]/$Statistics["women count"]);
		 $Statistics["women's AVG age"]=round($Statistics["women's AVG age"]/365/$Statistics["women count"]);

        foreach ($people as $motherfucker)
		 {
			  if ($motherfucker->Sex==1)
	 		  {
				  	if ($motherfucker->Height<$Statistics["men's AVG height"])
				   		$Statistics["men w/ height<AVG"]++;
					elseif ($motherfucker->Height==$Statistics["men's AVG height"])
						$Statistics["men w/ height=AVG"]++;
					else $Statistics["men w/ height>AVG"]++;

					if ($motherfucker->Weight<$Statistics["men's AVG weight"])
				   	$Statistics["men w/ weight<AVG"]++;
					elseif ($motherfucker->Weight==$Statistics["men's AVG weight"])
						$Statistics["men w/ weight=AVG"]++;
					else $Statistics["men w/ weight>AVG"]++;

					if ($motherfucker->GetCurrentAge("YYYY")<$Statistics["men's AVG age"])
				   	$Statistics["men w/ age<AVG"]++;
					elseif ($motherfucker->GetCurrentAge("YYYY")==$Statistics["men's AVG age"])
						$Statistics["men w/ age=AVG"]++;
					else $Statistics["men w/ age>AVG"]++;
			  }
			  else
			  {
				  if ($motherfucker->Height<$Statistics["women's AVG height"])
					  $Statistics["women w/ height<AVG"]++;
				  elseif ($motherfucker->Height==$Statistics["women's AVG height"])
					  $Statistics["women w/ height=AVG"]++;
				  else $Statistics["women w/ height>AVG"]++;

				  if ($motherfucker->Weight<$Statistics["women's AVG weight"])
					  $Statistics["women w/ weight<AVG"]++;
				  elseif ($motherfucker->Weight==$Statistics["women's AVG weight"])
					  $Statistics["women w/ weight=AVG"]++;
				  else $Statistics["women w/ weight>AVG"]++;

				  if ($motherfucker->GetCurrentAge("YYYY")<$Statistics["women's AVG age"])
					  $Statistics["women w/ age<AVG"]++;
				  elseif ($motherfucker->GetCurrentAge("YYYY")==$Statistics["women's AVG age"])
					  $Statistics["women w/ age=AVG"]++;
				  else $Statistics["women w/ age>AVG"]++;
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

		 $Little=null;
		 $Big=null;
		 $x=false; $y=false;
		 foreach ($people as $motherfucker)
		 {
			 if ($motherfucker->ID==$Statistics["FUCK"])
			 {$Little=$motherfucker; $x=true;}
		    if ($motherfucker->ID==$Statistics["EG_ID"])
			{$Big=$motherfucker;$y=true;}
		    if ($x&&$y)
		    	break;
		 }
		 echo 'Данные о самом старом человеке:<br/>';
		 echo $Big->Name." ".$Big->MiddleName." ".$Big->LastName
		 ."; Топтает эту землю с ".$Big->Birthday."; ".$Big->PhoneNumber."; ".$Big->MailingAddress." - ".$Big->CountryCode;
		 echo '<br/>Данные о самом молодом человеке:<br/>';
		 echo $Little->Name." ".$Little->MiddleName." ".$Little->LastName
		 ."; Топтает эту землю с ".$Little->Birthday."; ".$Little->PhoneNumber."; ".$Little->MailingAddress." - ".$Little->CountryCode;
//ini_set('max_execution_time', 30);
	}

	function MailServerClients(&$people)
	{
		$mailServers=array();
        foreach ($people as $P)
        	if (array_key_exists(substr($P->Email,strpos($P->Email,"@")+1),$mailServers))
                $mailServers[substr($P->Email,strpos($P->Email,"@")+1)]++;
			else $mailServers[substr($P->Email,strpos($P->Email,"@")+1)]=1;

			echo "<br/><br><b>Информация о почтовых серверах:</b><hr/>";
        foreach ($mailServers as $key => $value)
        	echo "<b>".$value."</b> пользуются <b>".$key."</b><br>";
	}

	function HolidayBirthdays(&$people)
	{
		$holidays=array("01.01"=>"","07.01"=>"","14.02"=>"","23.02"=>"","08.03"=>"","01.05"=>"","31.12"=>"");
        foreach ($people as $p)
        {
        	if (preg_match("/01\.01/",$p->Birthday))
        		$holidays["01.01"].=$p->FullName().";\r\n";
        	elseif(preg_match("/07\.01/",$p->Birthday))
                $holidays["07.01"].=$p->FullName().";\r\n";
            elseif(preg_match("/14\.02/",$p->Birthday))
                $holidays["14.02"].=$p->FullName().";\r\n";
            elseif(preg_match("/23\.02/",$p->Birthday))
                $holidays["23.02"].=$p->FullName().";\r\n";
            elseif(preg_match("/08\.03/",$p->Birthday))
                $holidays["08.03"].=$p->FullName().";\r\n";
            elseif(preg_match("/01\.05/",$p->Birthday))
                $holidays["01.05"].=$p->FullName().";\r\n";
            elseif(preg_match("/31\.12/",$p->Birthday))
                $holidays["31.12"].=$p->FullName().";\r\n";
		}
        echo "<hr/><b>Родившиеся 01.01:</b> <br/>".$holidays["01.01"];
        echo "<hr/><b>Родившиеся 07.01: </b><br/>".$holidays["07.01"];
        echo "<hr/><b>Родившиеся 14.02: </b><br/>".$holidays["14.02"];
        echo "<hr/><b>Родившиеся 23.02: </b><br/>".$holidays["23.02"];
        echo "<hr/><b>Родившиеся 08.03: </b><br/>".$holidays["08.03"];
        echo "<hr/><b>Родившиеся 01.05: </b><br/>".$holidays["01.05"];
        echo "<hr/><b>Родившиеся 31.12: </b><br/>".$holidays["31.12"];
	}

	function DisplayPeopleFromRegion(&$region)
	{
        $Homies= array();
		GetPeopleFromRegion($Homies,$region);
		usort($Homies, function($a, $b){
			return strcmp($a->LastName, $b->LastName);
        })

			?>

		<table class='table-bordered'>
			<thead><tr><th>ФИО</th><th>Пол</th><th>Город</th><th>Регион</th><th>Email</th><th>Телефонама</th><th>Возраст</th>
				<th>Должность</th><th>Компания</th><th>Вес</th><th>Рост</th>" ."<th>Адрес</th></tr></thead>
			<tbody>
			<?php foreach($Homies as $P): ?>
				<?php if($P->Sex): ?>
					<tr style="background-color: #3f76ff"><?=$P->ToTableRow()?></tr>
				<?php endif ?>
			<?php endforeach ?>
			</tbody>
		</table>

	<?php}

	function GetPeopleFromRegion(&$people,&$region)
	{
		$i=0;
		$handle=fopen("csvs/newone.txt","r");
		while (!feof($handle))
		{
			$fields = explode(";",fgets($handle));
			if (count($fields)>1)
			{
				if ($fields[6]==$region)
				{
					$P = new Person($fields[0],$fields[1],$fields[2],$fields[3],$fields[4],$fields[5],
									$fields[6],$fields[7],$fields[8],$fields[9],$fields[10],$fields[11],
									$fields[12],$fields[13],$fields[14],$fields[15],$fields[16]);
					$people[$i++]=$P;
				}
			}
		}
		fclose($handle);
	}
 ?>
