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
    
    
    protected function getRiskAnnotationUri($riskCuriType, $targetUri, $bodyUri=null) {
        return $this->getUri(md5($riskCuriType.$targetUri.$bodyUri));
    }
    
    
    protected function addRiskAnnotation($amloRiskType, $targetUri, $bodyUri=null) {
        $uri= $this->getRiskAnnotationUri($amloRiskType, $targetUri,$bodyUri);
        $this->addFragment("<%s> a amlo:$amloRiskType ;" , $uri, false);
        $this->addFragment(  'amlo:hasBody <%s> .', $bodyUri, false);
        $this->addFragment(  'amlo:hasTarget <%s> .', $targetUri, false);
        
        return $uri;
    }
    
    protected function addRiskRating($amloRiskType, $targetUri, float $riskEstimator, $bodyUri=null) {
        
        $uri= $this->getRiskAnnotationUri($amloRiskType, $targetUri,$bodyUri);
        $this->addFragment("<%s> a amlo:$amloRiskType ;" , $uri, false);
        $this->addFragment(  'amlo:hasRiskEstimator "%.2f"^^xsd:decimal ;', $riskEstimator, false);
        $this->addFragment(  'amlo:hasBody <%s> ;', $bodyUri, false);
        $this->addFragment(  'amlo:hasTarget <%s> .', $targetUri, false);
        
        return $uri;     
    }
    

}