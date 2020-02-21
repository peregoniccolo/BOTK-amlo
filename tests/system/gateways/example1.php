#!/usr/bin/env php
<?php
require_once __DIR__.'/../../../vendor/autoload.php';


class TEST extends \AMLO\Model\AbstractAMLO implements \BOTK\ModelInterface
{
    protected static $DEFAULT_OPTIONS = [
        'activityId' => BOTK\Filters::LITERAL ,
        'subjectId' => BOTK\Filters::LITERAL ,
        'subjectName' => BOTK\Filters::LITERAL ,
        'subjectNDG' => BOTK\Filters::LITERAL ,
        'subjectCountry' => BOTK\Filters::LITERAL ,
        'subjectGender' => BOTK\Filters::LITERAL ,
        'subjectDateOfBirth' => BOTK\Filters::LITERAL ,
        'subjectBornInMunicipality' => BOTK\Filters::LITERAL ,
        'unexpectedActivityPeriod' => BOTK\Filters::LITERAL ,
        'ignore' => BOTK\Filters::LITERAL ,
        'unexpectedActivityStatus' => BOTK\Filters::LITERAL ,
        'unexpectedActivityStatusDescription'  => BOTK\Filters::LITERAL ,
        'evaluatorId' => BOTK\Filters::LITERAL ,
        'unexpectedActivityNotes' => BOTK\Filters::LITERAL ,
        'accountableBranchCode' => BOTK\Filters::LITERAL ,
        'created' => BOTK\Filters::LITERAL ,
        'unexpectedActivityRiskProfile' => BOTK\Filters::LITERAL ,
    ];
    
    public function asTurtleFragment()
    {
        if(is_null($this->rdf)) {
            
            /********************************
             * Individuals URIs
             ********************************/
            // unexpected activity
            $activityURI = $this->getURI($this->data['activityId'], '-activity');
            
            // autonomous agent
            $subjectURI = $this->getURI($this->data['subjectId'], '-agent' ) ;
            
            //Bank branch office
            $branchURI = $this->getURI($this->data['accountableBranchCode'], '-branch' );
            
            //Unexpected Activity Report
            $reportURI = $this->getURI($this->data['activityId'], '-report' );
            
            
            
            /********************************
             * Facts
             ********************************/
            # E' stata identificata una operatività inattesa di cui è responsabile un soggetto (agente)
            $this->addFragment('<%s> a amlo:UnexpectedActivity .', $activityURI ,false);
            $this->addPartyInRole($activityURI, $subjectURI, 'amlo:Accountable');
            
            # Il soggetto è identificato nel registro dei clienti della banca
            
            $this->addIdentifier( $subjectURI, 'fibo-fnd-pas-pas:ClientIdentifier', $this->data['subjectId'],  $this->data['ndg-registry-uri'] );
            $this->addFragment('<%s> a fibo-fnd-aap-ppl:Person ;', $subjectURI ,false);
            $this->addFragment(' fibo-fnd-aap-agt:hasName "%s" ;', $this->data['subjectName']);
            $this->addFragment(' fibo-fnd-aap-ppl:hasGender "%s" ;', $this->data['subjectGender']);
            $this->addFragment(' fibo-fnd-aap-ppl:hasDateOfBirth "%s" ;', $this->data['subjectDateOfBirth']);
            $this->addFragment(' fibo-fnd-aap-ppl:hasPlaceOfBirth "%s" ;', $this->data['subjectBornInMunicipality']);
            $this->rdf .= '.';
            
            # l'operatività inattesa è stata segnalata alla dipendenza incaricata delle indagini
            $this->addFragment('<%s> a amlo:ActivityReport ;', $reportURI ,false);
            $this->addFragment(' amlo:isReportedOnDate "%s"^^xsd:dateTime  ;',$this->data['created'] ,false);
            $this->addFragment(' fibo-fnd-arr-rep:isReportedTo <%s>  ;',$branchURI ,false);
            $this->addFragment(' fibo-fnd-arr-rep:reportsOn <%s>  .',$activityURI ,false);
        }
        
        return $this->rdf;
    }
}


$options = [
    'factsProfile' => [
        'model'			=> 'TEST',
        'modelOptions'	=> [
            'base' => [ 'default'=> 'http://data.example.org/resource/' ]
        ],
        'datamapper'	=> function($rawdata){
            $data = array();
            $data['activityId'] = $rawdata[0];
            $data['subjectId'] = $rawdata[1];
            $data['subjectName'] = $rawdata[2];
            $data['subjectNDG'] = $rawdata[3];
            $data['subjectCountry'] = $rawdata[4];
            $data['subjectGender'] = $rawdata[5];
            $data['subjectDateOfBirth'] = $rawdata[6];
            $data['subjectBornInMunicipality'] = $rawdata[7];
            $data['unexpectedActivityPeriod'] = $rawdata[8];
            
            $data['unexpectedActivityStatus'] = $rawdata[10];
            $data['unexpectedActivityStatusDescription'] = $rawdata[11];
            $data['evaluatorId'] = $rawdata[12];
            $data['unexpectedActivityNotes'] = $rawdata[13];
            $data['accountableBranchCode'] = $rawdata[14];
            $data['created'] = $rawdata[15];
            $data['unexpectedActivityRiskProfile'] = $rawdata[16];
            return $data;
        },
        'rawdataSanitizer' => function( $rawdata){
            return (
                $rawdata[0] &&
                $rawdata[1] &&
                $rawdata[12] &&
                ($rawdata[9]=='N')
                )?$rawdata:false;
        },
    ],
    'skippFirstLine'	=> true,
    'fieldDelimiter' => ','
];

BOTK\SimpleCsvGateway::factory($options)->run();