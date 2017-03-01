<?php
class user
{
	public $id;
	public $name;
	public $birthday;
	public $picture;
	public $groups;
	
	public function __construct()
	{
		$this->groups = array();
	}
}
