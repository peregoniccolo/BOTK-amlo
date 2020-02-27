<?php
namespace AMLO\Model;

/*
 * Some helpers for AMLO ontology
 */
abstract class AbstractAMLO extends \BOTK\Model\AbstractModel
{
    
    protected static $DEFAULT_OPTIONS = [
        'ndg-registry-uri' => [ 
            'default'=> 'urn:resource:undefined-ndg-registry',
            'filter'=>FILTER_CALLBACK, 
            'options'=>'\BOTK\Filters::FILTER_VALIDATE_URI', 
            'flags'=> FILTER_REQUIRE_SCALAR
        ],
    ];
    
	protected static $VOCABULARY  = [
	   'fibo-fnd-dt-oc'    	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/DatesAndTimes/Occurrences/',
	   'fibo-fnd-pas-pas'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/ProductsAndServices/ProductsAndServices/',
	   'fibo-fnd-arr-rt'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Arrangements/Ratings/',
	   'fibo-fnd-arr-rep'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Arrangements/Reporting/',
	   'fibo-fnd-arr-doc'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Arrangements/Documents/',
	   'fibo-fnd-arr-asmt'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Arrangements/Assessments/',
	   'fibo-fnd-arr-id'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Arrangements/IdentifiersAndIndices/',
	   'fibo-fnd-acc-cur'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Accounting/CurrencyAmount/',
	   'fibo-fnd-qt-qtu'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Quantities/QuantitiesAndUnits/',
	   'fibo-fnd-acc-4217'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Accounting/ISO4217-CurrencyCodes/',
	   'fibo-fnd-rel-rel'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Relations/Relations/',
	   'fibo-fnd-aap-agt'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/AgentsAndPeople/Agents/',
	   'fibo-fnd-aap-ppl'   =>  'https://spec.edmcouncil.org/fibo/ontology/FND/AgentsAndPeople/People/',
	   'fibo-fnd-pty-pty'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Parties/Parties/',
	   'fibo-be-oac-exec'	=>  'https://spec.edmcouncil.org/fibo/ontology/BE/OwnershipAndControl/Executives/',
	   'fibo-fbc-pas-caa'	=>  'https://spec.edmcouncil.org/fibo/ontology/FBC/ProductsAndServices/ClientsAndAccounts/',
	   'amlo' =>  'http://w3id.org/amlo/core#',
	];
	
	/**
	 * adds a FIBO idenfifier
	 *     $idenfiedURI, $registryURI, $idURI must be URIs
	 *     $id is  valid RDF PATH identifier
	 *     $type is a CURI using available vocabulary prefixes
	 */
	protected function addIdentifier($idenfiedURI, $type, $id, $registryURI=null, $idURI=null)
	{
	    assert( !empty($type) && !empty($id) && !empty($idenfiedURI) );
	    
	    $subjectIdURI = $idURI?:$this->getURI($id, '_id') ;
	    $this->addFragment("<$subjectIdURI> fibo-fnd-rel-rel:hasTag \"%s\" ;", $id, false);
	    $this->addFragment(" fibo-fnd-aap-agt:identifies <%s> ;", $idenfiedURI, false );
	    $this->addFragment(" fibo-fnd-rel-rel:isDefinedIn <%s> ;", $registryURI ,false );
	    $this->addFragment(" a %s .", $type ,false);
	    
	    return $this;
	}
	
	
	/**
	 * adds a party in role
	 *     $subjectURI, $partyURI and $relURI must be URIs
	 *     $role is a CURI using available vocabulary prefixes
	 */
	protected function addPartyInRole($subjectURI, $partyURI, $role, $relURI=null)
	{
	    assert( !empty($subjectURI) && !empty($partyURI) && !empty($role) );
	    $relURI = $relURI?:$idURI?:$this->getURI(null, '_party-in-role');
	    
	    $this->rdf .= "<$subjectURI> fibo-fnd-pty-pty:hasPartyInRole <$relURI> . <$relURI> a $role ; fibo-fnd-rel-rel:hasIdentity <$partyURI>  .";
	    $this->tripleCount += 3;
	    
	    return $this;
	}
}