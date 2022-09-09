<?php
use PHPUnit\Framework\TestCase;

class DummyModel extends AMLO\Model\AbstractAMLO
{
	public function asTurtleFragment() { return $this->rdf;}	
	public function addTaxIDProxy($p1,$p2,$p3){ return $this->addTaxID($p1,$p2,$p3);}
	public function addVatIDProxy($p1,$p2,$p3){ return $this->addVatID($p1,$p2,$p3);}
	public function addRiskAnnotationProxy($p1,$p2,$p3,$p4){ return $this->addRiskAnnotation($p1,$p2,$p3,$p4);}
	public function addRiskRatingProxy($p1,$p2,$p3,$p4,$p5){ return $this->addRiskRating($p1,$p2,$p3,$p4,$p5);}
}

class AbstractModelTest extends TestCase
{
    
    public function testAddTaxID()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addTaxIDProxy('it', 'FGNNRC63S06F205A', 'urn:test:agent');

	    $idUri='urn:taxid:it:fgnnrc63s06f205a';

	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	        "<$idUri> a Parties:TaxIdentifier ;" .
	        'LanguageRepresentation:hasTag "FGNNRC63S06F205A" ;' .
	        'LanguageRepresentation:isMemberOf <urn:amlo:schema:taxid:it> ;' .
	        'LanguageRepresentation:identifies <urn:test:agent> .' ;

	   	$this->assertEquals($expected,(string) $obj);
    }
	
	
	public function testAddVatIDwithSubject()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addVatIDProxy('It', '11717750969', 'urn:test:organization');
	    
	    $idUri='urn:vatid:it:11717750969';  
	    
	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	   	    "<$idUri> a FormalBusinessOrganizations:ValueAddedTaxIdentificationNumber ;" .
	   	    'LanguageRepresentation:hasTag "11717750969" ;' .
	   	    'LanguageRepresentation:isMemberOf <urn:amlo:schema:vatid:it> ;' .
	   	    'LanguageRepresentation:identifies <urn:test:organization> .';
	    
	    $this->assertEquals($expected,(string) $obj);
	}
	
	
	public function testRiskAnnotation()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addRiskAnnotationProxy('MyRisk', 'urn:x', null, "test");
	    
	    $idUri='urn:resource:' . md5('MyRisk'.'urn:x'.null);
	    
	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	   	    "<$idUri> a amlo:MyRisk ;" .
			'amlo:motivatedBy "test" ;'.
	   	    'amlo:hasTarget <urn:x> .' ;
	    
	    $this->assertEquals($expected,(string) $obj);
	}
	
	
	public function testRiskRating()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addRiskRatingProxy('MyRisk', 'urn:x', 0.1234, 'urn:y', null);
	    
	    $idUri='urn:resource:' . md5('MyRisk'.'urn:x'.'urn:y');
	    
	    $expected = $obj->getTurtleHeader('urn:resource:') . "\n" .
	   	    "<$idUri> a amlo:MyRisk ;" .
	   	    'amlo:hasRiskEstimator "0.12"^^xsd:decimal ;' .
	   	    'amlo:hasBody <urn:y> ;' .
	   	    'amlo:hasTarget <urn:x> .' ;
	    
	    $this->assertEquals($expected,(string) $obj);
	}

}

