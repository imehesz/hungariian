<?php

class UrlTest extends WebTestCase
{
	public $fixtures=array(
		'urls'=>'Url',
	);

	public function testShow()
	{
		$this->open('?r=url/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=url/create');
	}

	public function testUpdate()
	{
		$this->open('?r=url/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=url/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=url/index');
	}

	public function testAdmin()
	{
		$this->open('?r=url/admin');
	}
}
