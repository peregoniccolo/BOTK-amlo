<?php
use PHPUnit\Framework\TestCase;

class DummyModel extends AMLO\Model\AbstractAMLO
{
	public function asTurtleFragment() { return $this->rdf;}	
	public function addIdentifierProxy($p1,$p2,$p3,$p4,$p5) { return $this->addIdentifier($p1,$p2,$p3,$p4,$p5);}
	public function addPartyInRoleProxy($p1,$p2,$p3,$p4) { return $this->addPartyInRole($p1,$p2,$p3,$p4);}
}

class AbstractModelTest extends TestCase
{
    
	public function testAddIdentifier()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addIdentifierProxy('urn:resource:identified', 'fibo:type', 'id', null, null);

	    $expected = $obj->getTurtleHeader() . "\n" .
            '<urn:resource:id_id> fibo-fnd-rel-rel:hasTag "id";' .
            'fibo-fnd-aap-agt:identifies <urn:resource:identified>;' .
            'a fibo:type .';
	    
	   	$this->assertEquals($expected,(string) $obj);
	}
	
	
	public function testPartyInRole()
	{
	    $obj = DummyModel::fromArray(array());
	    $obj->addPartyInRoleProxy('urn:resource:subject', 'urn:resource:identity', 'fibo:role', 'urn:resource:test');
	    
	    $expected = $obj->getTurtleHeader() . "\n" .
    	    '<urn:resource:subject> fibo-fnd-pty-pty:hasPartyInRole <urn:resource:test>.' .
    	    '<urn:resource:test> a fibo:role ;fibo-fnd-rel-rel:hasIdentity <urn:resource:identity>.' ;
	    
	    $this->assertEquals($expected,(string) $obj);
	}

}

