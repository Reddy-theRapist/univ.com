<?php

Class Model_Article Extends Model_Base {
	
	public $id;
	// далее описать все остальные поля сущности
	
	public function fieldsTable(){
		return array(
			'id' => 'Id',
			// здесь тоже лучше не забыть все описать
		);
	}
	
}