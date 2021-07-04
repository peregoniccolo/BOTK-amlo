<?php
use PHPUnit\Framework\TestCase;

class DummyModel extends AMLO\Model\AbstractAMLO
{
	public function asTurtleFragment() { return $this->rdf;}	
	public function addTaxIDProxy($p1,$p2,$p3){ return $this->addTaxID($p1,$p2,$p3);}
	public function addVatIDProxy($p1,$p2,$p3){ return $this->addVatID($p1,$p2,$p3);}
}

class AbstractModelTest extends TestCase
{
    
    public function testAddTaxID()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addTaxIDProxy('it', 'fgnnrc63S06F205A', 'urn:test:agent');
	    
	    $subjectUri = 'urn:resource:'. md5('ITFGNNRC63S06F205A');
	    $idUri=$subjectUri.'_i';

	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	        "<$idUri> a fibo-fnd-pty-pty:TaxIdentifier ;" .
	        'lcc-lr:hasTag "FGNNRC63S06F205A" ;' .
	        'lcc-lr:isMemberOf alpha2CountryId:IT ;' .
	        'lcc-lr:identifies <urn:test:agent> .' ;

	   	$this->assertEquals($expected,(string) $obj);
    }
	
	
	public function testAddVatIDwithSubject()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addVatIDProxy('It', '11717750969', 'urn:test:organization');
	    
	    $subjectUri = 'urn:resource:'. md5('IT11717750969');
	    $idUri=$subjectUri.'_i';
	    
	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	   	    "<$idUri> a fibo-be-le-fbo:ValueAddedTaxIdentificationNumber ;" .
	   	    "lcc-lr:hasTag \"11717750969\" ;" .
	   	    "lcc-lr:isMemberOf alpha2CountryId:IT ;" .
	   	    "lcc-lr:identifies <urn:test:organization> .";
	    
	    $this->assertEquals($expected,(string) $obj);
	}

}

