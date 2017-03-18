<?php
class Person
{

	 public $ID=-1; public $Name; public $MiddleName;
	 public $LastName; public $Sex; public $City;
	 public $Region; public $Email; public $PhoneNumber;
	 public $Birthday;public $Job;public $Company;
	 public $Weight; public $Height;public $MailingAddress;
	 public $Postcode; public $Country;

	function __construct(&$id=null, &$name, &$middleName, &$lastName,
									&$sex, &$city, &$region, &$email,
									&$phoneNumber, &$birthday, &$job,
									&$company, &$weight, &$height, &$mailingAddress,
									&$postcode, &$countryCode)
	{
		$args = func_get_args();
		$props = get_object_vars($this);

		$i = 0;
        foreach ($props as $key => $item)
			$this->$key = $args[$i++];

	}

	function CheckFixEmail()
	{
		if(preg_match("/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z]+$/",$this->Email))
			return 0;
		else
		{
            if (preg_match("/@/",$this->Email)==false)
                $this->Email="UNDEFINED";
			else $this->Email=preg_replace("/[^@0-9a-zA-Z._-]|@+$|^@+|(?<=@)@{1,}|@(?=[^@]+@+)|(?<=\.)\.{1}|\.(?=[^\.]+\.+)/","",$this->Email);
			return 1;
		}

    }

	function CheckFixSex()
	{
		if (preg_match("/\b(fe)?male\b/",$this->Sex)==1)
		{
			if (preg_match("/\bfe/", $this->Sex)==1)
				$this->Sex=0;
			else $this->Sex=1;

			return 0;
		}
		else
		{
			$this->Sex="";
			return 1;
		}
	}

	function CheckFixPhoneNumber()
	{
		$x=preg_replace("/[0-9]|-\d/","",$this->PhoneNumber);
		if ($x!="")
			{
				$this->PhoneNumber=preg_replace("/[^-0-9]/","",$this->PhoneNumber);
				return 1;
			}
		else return 0;
	}

	function CheckFixMailingAddress()
	{
		if(preg_match("/^\d+\s+\D+$/", $this->MailingAddress)==0)
		{
			$this->MailingAddress=preg_replace("/^\D+|\d+$/","",$this->MailingAddress);
			$this->MailingAddress=preg_replace("/(^\d+)([^\s])/",'$1'." ",$this->MailingAddress);
			$this->MailingAddress=preg_replace("/(?<=\D|\s)\d+/","",$this->MailingAddress);
			return 1;
		}
		else return 0;
	}

	function FixID()
	{$this->ID=preg_replace("/(\d+)/e",'sprintf("%06d",$1)',$this->ID);}

	function FixPhoneNumber()
	{
		$this->PhoneNumber=preg_replace("/-/","",$this->PhoneNumber);
		switch(strlen($this->PhoneNumber))
		{
			case 8:
				$this->PhoneNumber=substr($this->PhoneNumber,0,1)."-".substr($this->PhoneNumber,1,3)."-".substr($this->PhoneNumber,4,4);
				break;
			case 9:
				$this->PhoneNumber=substr($this->PhoneNumber,0,2)."-".substr($this->PhoneNumber,2,3)."-".substr($this->PhoneNumber,5,4);
				break;
			case 10:
				$this->PhoneNumber=substr($this->PhoneNumber,0,3)."-".substr($this->PhoneNumber,3,3)."-".substr($this->PhoneNumber,6 ,4);
				break;
		}

	}

	function FixBirthday()
	{$this->Birthday=preg_replace("/(\d+)\/(\d+)\/(\d+)/e",'sprintf("%02d.%02d.%04d",$2,$1,$3)',$this->Birthday);}

	function FixWeight()
	{$this->Weight=preg_replace("/(\d+\.\d+)/e",'round($1)',$this->Weight);}

	function FixMailingAddress()
	{$this->MailingAddress=preg_replace("/(^\d+)\s+([^0-9]+)/","$2, $1",$this->MailingAddress);}

	function GetCurrentAge($UNIT)
	{
		$x=0;
		switch($UNIT)
		{
			case "YYYY":
			$x = date_diff(date_create($this->Birthday),date_create('now'))->y;
				break;
			case "MM":
                $x= date_diff(date_create($this->Birthday),date_create('now'))->m;
				break;
			case "DD":
                $x= date_diff(date_create($this->Birthday),date_create('now'))->days;
				break;
		}
		return $x;
	}

//	public function __toString()
//	{
//       return $this->ID.";".$this->Name.";".$this->MiddleName.";".$this->LastName.";".$this->Sex.";".$this->City
//		 		  .";".$this->Region.";".$this->Email.";".$this->PhoneNumber.";".$this->Birthday.";".$this->Job
//				  .";".$this->Company.";".$this->Weight.";".$this->Height.";".$this->MailingAddress.";".$this->Postcode.";".$this->CountryCode;
//	}
	public function Info()
	{
        return $this->ID.";".$this->Name.";".$this->MiddleName.";".$this->LastName.";".$this->Sex.";".$this->City
            .";".$this->Region.";".$this->Email.";".$this->PhoneNumber.";".$this->Birthday.";".$this->Job
            .";".$this->Company.";".$this->Weight.";".$this->Height.";".$this->MailingAddress.";".$this->Postcode.";".$this->CountryCode;
	}

	public function  FullName()
	{return $this->Name." ".$this->MiddleName." ".$this->LastName;}
    function ToTableRow()
	{
        return "<td>".$this->FullName()."</td><td>".$this->Sex."</td><td>".$this->City."</td><td>".
            $this->Region."</td><td>".$this->Email."</td><td>".$this->PhoneNumber."</td><td>".$this->GetCurrentAge("YYYY")."</td><td>".$this->Job."</td><td>".
            $this->Company."</td><td>".$this->Weight."</td><td>".$this->Height."</td><td>".$this->Postcode." ".$this->MailingAddress." ".$this->CountryCode."</td>";
    }
}
?>
