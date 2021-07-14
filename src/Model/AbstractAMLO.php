<?php
namespace AMLO\Model;

/*
 * Some helpers for AMLO ontology
 */
abstract class AbstractAMLO extends \BOTK\Model\AbstractModel
{
    
    protected static $VOCABULARY  = [
        'amlo'              => 'http://w3id.org/amlo/core#',
        'fibo-be-le-fbo'    => 'https://spec.edmcouncil.org/fibo/ontology/BE/LegalEntities/FormalBusinessOrganizations/',
        'fibo-fnd-pty-pty'	=> 'https://spec.edmcouncil.org/fibo/ontology/FND/Parties/Parties/',
        'lcc-lr'            => 'https://www.omg.org/spec/LCC/Languages/LanguageRepresentation/',
	];

    
    protected function getCountryID($schemaID, $alpha2CountryId, $tag)
    {   
        
        return $tag?('urn:hash::md5:'. md5(strtoupper($schemaID.$alpha2CountryId.$tag))):null ;  
    }
    
    
    protected function addCountryID($schemaID, $alpha2CountryId, $tag, $subjectUri)
    {
        assert( preg_match('/^[A-Za-z]{2}$/', $alpha2CountryId ) &&  preg_match('/^(taxid|vatid)$/', $schemaID ) );
        
        if($uri = $this->getCountryID($schemaID, $alpha2CountryId, $tag)) {
            switch ($schemaID) {
                case 'taxid':
                    $fiboType='fibo-fnd-pty-pty:TaxIdentifier'; 
                    ;
                    break;
                
                case 'vatid':
                    $fiboType='fibo-be-le-fbo:ValueAddedTaxIdentificationNumber';
                    ;
                    break;
            }
            $this->addFragment("<%s> a $fiboType ;" , $uri, false);
            $this->addFragment(  'lcc-lr:hasTag "%s" ;', strtoupper($tag) );
            $this->addFragment(  "lcc-lr:isMemberOf <urn:amlo:schema:$schemaID:%s> ;", strtolower($alpha2CountryId), false);
            $this->addFragment(  'lcc-lr:identifies <%s> .', $subjectUri, false);
        }
        
        return $uri;
    }
    
    
    protected function addTaxID($alpha2CountryId, $taxID, $subjectUri)
    {   
        return $this->addCountryID('taxid', $alpha2CountryId, $taxID, $subjectUri);
    }
    
    
    protected function addVatID($alpha2CountryId, $vatID, $subjectUri)
    {    
        return $this->addCountryID('vatid', $alpha2CountryId, $vatID, $subjectUri);
    }
    

}