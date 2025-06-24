<?php
namespace AMLO\Model;

/*
 * Some helpers for AMLO ontology
 */
abstract class AbstractAMLO extends \BOTK\Model\AbstractModel
{

    protected static $VOCABULARY = [
        'amlo' => 'http://w3id.org/amlo/core#',
        'FormalBusinessOrganizations' => 'https://spec.edmcouncil.org/fibo/ontology/BE/LegalEntities/FormalBusinessOrganizations/',
        'Parties' => 'https://spec.edmcouncil.org/fibo/ontology/FND/Parties/Parties/',
        'LanguageRepresentation' => 'https://www.omg.org/spec/LCC/Languages/LanguageRepresentation/',
    ];


    protected function getUriFromCountryID($schemaID, $alpha2CountryId, $tag)
    {
        return $tag
            ? sprintf(
                'urn:%s:%s:%s',
                $schemaID,
                strtolower($alpha2CountryId),
                \BOTK\Filters::FILTER_SANITIZE_ID($tag)
            )
            : null;
    }


    protected function addCountryID($schemaID, $alpha2CountryId, $tag, $subjectUri)
    {
        assert(preg_match('/^[A-Za-z]{2}$/', $alpha2CountryId) && preg_match('/^(taxid|vatid)$/', $schemaID));

        if ($uri = $this->getUriFromCountryID($schemaID, $alpha2CountryId, $tag)) {
            switch ($schemaID) {
                case 'taxid':
                    $fiboType = 'Parties:TaxIdentifier';
                    ;
                    break;

                case 'vatid':
                    $fiboType = 'FormalBusinessOrganizations:ValueAddedTaxIdentificationNumber';
                    ;
                    break;
            }
            $this->addFragment("<%s> a $fiboType ;", $uri, false);
            $this->addFragment('LanguageRepresentation:hasTag "%s" ;', strtoupper($tag));
            $this->addFragment("LanguageRepresentation:isMemberOf <urn:amlo:schema:$schemaID:%s> ;", strtolower($alpha2CountryId), false);
            $this->addFragment('LanguageRepresentation:identifies <%s> .', $subjectUri, false);
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


    protected function getRiskAnnotationUri($riskCuriType, $targetUri, $bodyUri = null)
    {
        return $this->getUri(md5($riskCuriType . $targetUri . $bodyUri));
    }
    protected function rebuildBodyUri($uri)
    {
        // Parse the URL into components

        $parts = parse_url($uri);

        // If there's no query, return the original URL
        if (empty($parts['query'])) {
            return $uri;
        }

        // Parse existing query parameters into an array
        parse_str($parts['query'], $query);

        // Build new query string
        $newQuery = http_build_query($query);

        // Rebuild the URL
        $newUrl =
            ($parts['scheme'] ?? 'https') . '://' .
            ($parts['host'] ?? '') .
            (isset($parts['port']) ? ':' . $parts['port'] : '') .
            ($parts['path'] ?? '') .
            '?' . $newQuery;

        return $newUrl;
    }

    protected function checkSingleElementArrayUri($uri): string
    {
        // an uri could be a single element array because of FILTER_FORCE_ARRAY

        if (is_string($uri)) {
            // uri is already a string
            return $uri;
        }

        assert(is_array($uri) && count($uri) === 1, '$uri must be an array with exactly one element');
        return $uri[0];
    }

    protected function addRiskAnnotation($amloRiskType, $targetUri, $bodyUri = null, $motivatedBy = null)
    {
        # amlo: targetUri of an annotation is exactly one
        $targetUri = $this->checkSingleElementArrayUri($targetUri); # check if its only one

        # amlo: bodyUri (motivation, which is theoretically deprecated) is max 1, could be null
        if (!is_null($bodyUri)) {
            $bodyUri = $this->rebuildBodyUri($this->checkSingleElementArrayUri($bodyUri));
        }

        $uri = $this->getRiskAnnotationUri($amloRiskType, $targetUri, $bodyUri);
        $this->addFragment("<%s> a amlo:$amloRiskType ;", $uri, false);
        $this->addFragment('amlo:hasBody <%s> ;', $bodyUri, false);
        $this->addFragment('amlo:motivatedBy "%s" ;', $motivatedBy);
        $this->addFragment('amlo:hasTarget <%s> .', $targetUri, false);

        return $uri;
    }


    protected function addRiskRating($amloRiskType, $targetUri, float $riskEstimator, $bodyUri = null, $motivatedBy = null)
    {
        # amlo: targetUri of an annotation is exactly one
        $targetUri = $this->checkSingleElementArrayUri($targetUri); # check if its only one

        # amlo: bodyUri (motivation, which is theoretically deprecated) is max 1, could be null
        if (!is_null($bodyUri)) {
            $bodyUri = $this->rebuildBodyUri($this->checkSingleElementArrayUri($bodyUri));
        }

        $uri = $this->getRiskAnnotationUri($amloRiskType, $targetUri, $bodyUri);

        $this->addFragment("<%s> a amlo:$amloRiskType ;", $uri, false);
        $this->addFragment('amlo:hasRiskEstimator "%.2f"^^xsd:decimal ;', $riskEstimator, false);
        $this->addFragment('amlo:hasBody <%s> ;', $bodyUri, false);
        $this->addFragment('amlo:motivatedBy "%s" ;', $motivatedBy);
        $this->addFragment('amlo:hasTarget <%s> .', $targetUri, false);

        return $uri;
    }
}