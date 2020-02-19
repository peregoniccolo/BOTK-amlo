<?php
use PHPUnit\Framework\TestCase;


class Gianos3DTest extends TestCase
{	

	public function testConstructorWithCustomOptions()
	{
		$thing = AMLO\Model\Gianos3D::fromArray([], [
			'base'	=> ['default'	=> 'urn:a:'],
			'lang'	=> ['default'	=> 'en'],
		]);
		$options = $thing->getOptions();
		$this->assertEquals(
			array(
			'default'	=> 'urn:a:',
			'filter'    => FILTER_CALLBACK,
            'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
        	'flags'  	=> FILTER_REQUIRE_SCALAR,
           ),
			$options['base']
		);
		$this->assertEquals(array('default'    => 'en'),$options['lang']);
	}
	



    public function testGetDefaultOptions()
    {	
    	$expectedOptions =  array (
    	    'uri'				=> array(
    	        'filter'    => FILTER_CALLBACK,
    	        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
    	        'flags'  	=> FILTER_REQUIRE_SCALAR,
    	    ),
    	    'base'				=> array(
    	        'default'	=> 'urn:local:',
    	        'filter'    => FILTER_CALLBACK,
    	        'options' 	=> '\BOTK\Filters::FILTER_VALIDATE_URI',
    	        'flags'  	=> FILTER_REQUIRE_SCALAR,
    	    ),
    	    'id'				=> array(
    	        'filter'    => FILTER_CALLBACK,
    	        'options' 	=> '\BOTK\Filters::FILTER_SANITIZE_ID',
    	        'flags'  	=> FILTER_REQUIRE_SCALAR,
    	    ),
    	    
    	    'subjectId'	=> [
    	        'filter'    => FILTER_DEFAULT,
    	        'flags'  	=> FILTER_REQUIRE_SCALAR,
    	    ],

		);

	$thing = AMLO\Model\Gianos3D::fromArray([]);
	$this->assertEquals($expectedOptions, $thing->getOptions());
}


	public function testAsStdObject()
	{
		$data = new \stdClass;
		$data->uri = 'urn:test:a';
		
		$expectedData = clone($data);
		$expectedData->base = 'urn:local:';
		
		$dummyObj = AMLO\Model\Gianos3D::fromStdObject($data);
		
		$this->assertEquals($expectedData, $dummyObj->asStdObject());
	}

}

