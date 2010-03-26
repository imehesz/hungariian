<?php

class PageTest extends WebTestCase
{
	public $fixtures=array(
		'pages'=>'Page',
	);

	public function testShow()
	{
		$this->open('?r=page/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=page/create');
	}

	public function testUpdate()
	{
		$this->open('?r=page/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=page/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=page/index');
	}

	public function testAdmin()
	{
		$this->open('?r=page/admin');
	}
}
