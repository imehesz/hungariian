<?php

class UrlTest extends CDbTestCase
{
	public $fixtures=array(
		'urls'=>'Url',
	);

	/*
    public function testCreate()
	{

	}
    */

    /*
    public function testTrue()
    {
        $this->assertTrue( false );
    }

    public function testTrue2()
    {
        $this->assertTrue( true );
    }
    */

    public function testCountAll()
    {
        $urls = sizeof(Url::model()->findAll());
        $this->assertEquals( 2, $urls );
    }

    public function testActionView()
    {
        $url = Url::model()->findByAttributes( array( 'id' => 2 ) );

        $this->assertTrue( $url instanceof Url );
        $this->assertEquals( $url->url, 'http://weblabor.hu' );
    }

    public function testUrlRequired()
    {
        $this->assertTrue( Url::model()->isAttributeRequired( 'url' ) );
    }

    public function testSlugRequired()
    {
        $this->assertTrue( Url::model()->isAttributeRequired( 'shortened' ) );
    }

/*  public function testCreateSlug()
    {
        $slug = new Url();
        $this->assertTrue( $slug->createSlug() );
        $this->assertTrue($slug->save() );
        echo $slug->id;
    }
*/

    public function testCreateSlug()
    {
        $url = new Url();
        $url->setAttributes( array( 'url' => 'http://www.weblabor.hu' ) );
        $url->save();
        $this->assertEquals( 'AC', $url->shortened );
    }
}
