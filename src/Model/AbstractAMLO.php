<?php
namespace AMLO\Model;

/*
 * Some helpers for AMLO ontology
 */
abstract class AbstractAMLO extends \BOTK\Model\AbstractModel
{
    
    protected static $VOCABULARY  = [
        'amlo' => 'http://w3id.org/amlo/core#',
        'FormalBusinessOrganizations' => 'https://spec.edmcouncil.org/fibo/ontology/BE/LegalEntities/FormalBusinessOrganizations/',
        'Parties' => 'https://spec.edmcouncil.org/fibo/ontology/FND/Parties/Parties/',
        'LanguageRepresentation' => 'https://www.omg.org/spec/LCC/Languages/LanguageRepresentation/',
	];

    
    protected function getUriFromCountryID($schemaID, $alpha2CountryId, $tag)
    {         
        return $tag
            ?sprintf('urn:%s:%s:%s',
                $schemaID, 
                strtolower($alpha2CountryId), 
                \BOTK\Filters::FILTER_SANITIZE_ID($tag))
            :null ;  
    }
    
    
    protected function addCountryID($schemaID, $alpha2CountryId, $tag, $subjectUri)
    {
        assert( preg_match('/^[A-Za-z]{2}$/', $alpha2CountryId ) &&  preg_match('/^(taxid|vatid)$/', $schemaID ) );
        
        if($uri = $this->getUriFromCountryID($schemaID, $alpha2CountryId, $tag)) {
            switch ($schemaID) {
                case 'taxid':
                    $fiboType='Parties:TaxIdentifier'; 
                    ;
                    break;
                
                case 'vatid':
                    $fiboType='FormalBusinessOrganizations:ValueAddedTaxIdentificationNumber';
                    ;
                    break;
            }
            $this->addFragment("<%s> a $fiboType ;" , $uri, false);
            $this->addFragment(  'LanguageRepresentation:hasTag "%s" ;', strtoupper($tag) );
            $this->addFragment(  "LanguageRepresentation:isMemberOf <urn:amlo:schema:$schemaID:%s> ;", strtolower($alpha2CountryId), false);
            $this->addFragment(  'LanguageRepresentation:identifies <%s> .', $subjectUri, false);
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
    
    
    protected function getRiskAnnotationUri($riskCuriType, $targetUri, $bodyUri=null) {
        return $this->getUri(md5($riskCuriType.$targetUri.$bodyUri));
    }
    
    
    protected function addRiskAnnotation($amloRiskType, $targetUri, $bodyUri=null, $motivatedBy=null) {
        $uri= $this->getRiskAnnotationUri($amloRiskType, $targetUri, $bodyUri);
        $this->addFragment("<%s> a amlo:$amloRiskType ;" , $uri, false);
        $this->addFragment(  'amlo:hasBody <%s> ;', $bodyUri, false);
        $this->addFragment(  'amlo:motivatedby "%s" ;', $motivatedBy);
        $this->addFragment(  'amlo:hasTarget <%s> .', $targetUri, false);
        
        return $uri;
    }
    
    protected function addRiskRating($amloRiskType, $targetUri, float $riskEstimator, $bodyUri=null, $motivatedBy=null) {
        
        $uri= $this->getRiskAnnotationUri($amloRiskType, $targetUri,$bodyUri);
        $this->addFragment("<%s> a amlo:$amloRiskType ;" , $uri, false);
        $this->addFragment(  'amlo:hasRiskEstimator "%.2f"^^xsd:decimal ;', $riskEstimator, false);
        $this->addFragment(  'amlo:hasBody <%s> ;', $bodyUri, false);
        $this->addFragment(  'amlo:motivatedby "%s" ;', $motivatedBy);
        $this->addFragment(  'amlo:hasTarget <%s> .', $targetUri, false);
        
        return $uri;     
    }
    

}