<?php
use PHPUnit\Framework\TestCase;

final class FiltersTest extends TestCase
{

    /**
     * @dataProvider classIds
     */	
	public function testAsClassFilter($data, $expectedData)
	{
	    $this->assertEquals($expectedData, AMLO\Filters::FILTER_SANITIZE_AS_CLASS_NAME($data));
	}
	public function classIds()
    {
        return array(
            array( 'Primo',	    'Primo'), 
    		array( 'primo',	    'Primo'), 
    		array( 'Primo-e-secondo',		'PrimoESecondo'), 
            array( 'primo-secondo-terzo ',	'PrimoSecondoTerzo'),
            array( 'Società di capitali',	'SocietàDiCapitali'), 
		);
	}
	
	
	/**
	 * @dataProvider propIds
	 */
	public function testAsPropertyFilter($data, $expectedData)
	{
	    $this->assertEquals($expectedData, AMLO\Filters::FILTER_SANITIZE_AS_PROPERTY_NAME($data));
	}
	public function propIds()
	{
	    return array(
	        array( 'Primo',	    'primo'),
	        array( 'primo',	    'primo'),
	        array( 'Primo e secondo',		'primoESecondo'),
	        array( 'primo-secondo-terzo ',	'primoSecondoTerzo'),
	    );
	}
	
 
}
