<?php
namespace AMLO\Model;

class Gianos3D extends \BOTK\Model\AbstractModel implements \BOTK\ModelInterface
{
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
	   'fibo-fnd-pty-pty'	=>  'https://spec.edmcouncil.org/fibo/ontology/FND/Parties/Parties/',
	   'fibo-be-oac-exec'	=>  'https://spec.edmcouncil.org/fibo/ontology/BE/OwnershipAndControl/Executives/',
	   'fibo-fbc-pas-caa'	=>  'https://spec.edmcouncil.org/fibo/ontology/FBC/ProductsAndServices/ClientsAndAccounts/',
	   'amlo'	            =>  'http://w3id.org/amlo/core#',			
	];
		
	protected static $DEFAULT_OPTIONS = [	    
	    'subjectId'	=> [
	        'filter'    => FILTER_DEFAULT,
	        'flags'  	=> FILTER_REQUIRE_SCALAR,
	    ],
	];
	

	
	public function asTurtleFragment()
	{
		if(is_null($this->rdf)) {
			$uri = $this->getUri();
		}

		return $this->rdf;
	}
}