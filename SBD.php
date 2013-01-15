<?php

class SBD 
{
	protected $filepath;
	
	public function __construct($filepath)
    {
		$this->filepath = $filepath;
	}
	
	public function detect()
	{
		return array(
			'test1',
			'test2'
		);
	}
	
}