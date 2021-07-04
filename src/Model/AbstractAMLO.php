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
        'alpha2CountryId'   => 'https://www.omg.org/spec/LCC/Countries/ISO3166-1-CountryCodes/',
	];

 
   
    protected function getUriFromCountryID( $alpha2CountryId, $tag ) : String
    {
        assert( preg_match('/^[A-Z]{2}$/', $alpha2CountryId ) && $tag );
        
        return $this->getUri(md5(strtoupper($alpha2CountryId.$tag)));
    }

    
    protected function addCountryId($curiType, $alpha2CountryId, $taxID, $subjectUri=null) 
    {
        $alpha2CountryId=strtoupper($alpha2CountryId);
        $taxID=strtoupper($taxID);
        
        $uri = $this->getUriFromCountryID( $alpha2CountryId, $taxID );
        
        $idUri= $uri .'_i';
        if( is_null($subjectUri)) { $subjectUri=$uri ;}
        
        $this->addFragment("<$idUri> a %s ;" , $curiType, false);
        $this->addFragment(  'lcc-lr:hasTag "%s" ;', $taxID);
        $this->addFragment(  'lcc-lr:isMemberOf alpha2CountryId:%s ;', $alpha2CountryId, false);
        $this->addFragment(  'lcc-lr:identifies <%s> .', $subjectUri, false);
        
        return $this;
    }
    
    
    protected function addTaxID($alpha2CountryId, $taxID, $subjectUri=null)
    {   
        return $this->addCountryId('fibo-fnd-pty-pty:TaxIdentifier', $alpha2CountryId, $taxID, $subjectUri);
    }
    
    
    protected function addVatID($alpha2CountryId, $vatID, $subjectUri=null)
    {
        return $this->addCountryId('fibo-be-le-fbo:ValueAddedTaxIdentificationNumber', $alpha2CountryId, $vatID, $subjectUri);
    }
    

}