#!/usr/bin/env php
<?php
require_once __DIR__.'/../../../vendor/autoload.php';


class TEST extends \AMLO\Model\AbstractAMLO implements \BOTK\ModelInterface
{
    const STRING = ['filter'=> FILTER_DEFAULT, 'flags'=> FILTER_REQUIRE_SCALAR];
    
    protected static $DEFAULT_OPTIONS = [,
        'ndg-registry-uri' => [
            'default'=> 'urn:resource:undefined-ndg-registry',
            'filter'=>FILTER_CALLBACK,
            'options'=>'\BOTK\Filters::FILTER_VALIDATE_URI',
            'flags'=> FILTER_REQUIRE_SCALAR
        ],
        'activityId' => self::STRING ,
        'subjectId' => self::STRING ,
        'subjectName' => self::STRING ,
        'subjectNDG' => self::STRING ,
        'subjectCountry' => self::STRING ,
        'subjectGender' => self::STRING ,
        'subjectDateOfBirth' => self::STRING ,
        'subjectBornInMunicipality' => self::STRING ,
        'unexpectedActivityPeriod' => self::STRING ,
        'ignore' => self::STRING ,
        'unexpectedActivityStatus' => self::STRING ,
        'unexpectedActivityStatusDescription'  => self::STRING ,
        'evaluatorId' => self::STRING ,
        'unexpectedActivityNotes' => self::STRING ,
        'accountableBranchCode' => self::STRING ,
        'created' => self::STRING ,
        'unexpectedActivityRiskProfile' => self::STRING ,
    ];
    
    public function asTurtleFragment()
    {
        if(is_null($this->rdf)) {
            
            /********************************
             * Individuals URIs
             ********************************/
            // unexpected activity URI
            $activityURI = $this->getURI($this->data['activityId'], '-activity');
            
            // autonomous agent  URI
            $subjectURI = $this->getURI($this->data['subjectId'], '-agent' ) ;
            
            // bank branch office  URI
            $branchURI = $this->getURI($this->data['accountableBranchCode'], '-branch' );
            
            // unexpected Activity Report  URI
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