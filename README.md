# BOTK\AMLO
[![Build Status](https://img.shields.io/travis/linkeddatacenter/BOTK-core.svg?style=flat-square)](http://travis-ci.org/linkeddatacenter/BOTK-core)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/linkeddatacenter/BOTK-core.svg?style=flat-square)](https://scrutinizer-ci.com/g/linkeddatacenter/BOTK-core)
[![Latest Version](https://img.shields.io/packagist/v/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![Total Downloads](https://img.shields.io/packagist/dt/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)
[![License](https://img.shields.io/packagist/l/botk/core.svg?style=flat-square)](https://packagist.org/packages/botk/core)

Super lightweight classes for developing gateways for [AMLO core ontology](https://gitlab.com/mopso/amlo/-/tree/master/core).


## Installation

The package is available on [Packagist](https://packagist.org/packages/botk/amlo).
You can install it using [Composer](http://getcomposer.org):

```
composer require botk/amlo
```

## Overview

This package provides some php libraries to transform  raw data into AMLO linked data.

The goal of the libraries is to simplify the conversion of raw data (e.g. .csv or  xml file) about *unexpected activities*, *transfer*, and *risk ratings* according AMLO core ontology.

This package extends the [BOTK core library](https://github.com/linkeddatacenter/BOTK-core) and it is compatible with [LinkedData.Center SDaaS plans](http://linkeddata.center/home/sdaas)



## Libraries usage

```php
<?php
require_once __DIR__.'/../vendor/autoload.php';

$options = [
	'factsProfile' => [
		'model'			=> 'Gianos3D',
		'modelOptions'	=> [
			'base' => [ 'default'=> 'http://demo.mopso.net/resource/' ]
		],
		'datamapper'	=> function($rawdata){
			$data = array();
			$data['id'] = $rawdata[0];
			$data['subjectId'] = $rawdata[1];
			$data['subjectName'] = $rawdata[2];
			$data['subjectNDGe'] = $rawdata[3];
			$data['subjectCountry'] = $rawdata[4];
			$data['subjectSex'] = $rawdata[5];
			$data['subjectBirthDate'] = $rawdata[6];
			$data['subjectBornInMunicipality'] = $rawdata[7];
			$data['unexpectedActivityPeriod'] = $rawdata[8];
			
			$data['unexpectedActivityStatus'] = $rawdata[10];
			$data['unexpectedActivityStatusDescription'] = $rawdata[11];
			$data['evauatorId'] = $rawdata[12];
			$data['unexpectedActivityNotes'] = $rawdata[13];
			$data['accountableBranchCode'] = $rawdata[14];
			$data['created'] = $rawdata[15];			
			$data['unexpectedActivityRiskProfile'] = $rawdata[16];
			return $data;
		},
		'rawdataSanitizer' => function( $rawdata){
			return ((count($rawdata)==16) && $rawdata[9]!='N')) ?$rawdata:false;
		},	
	],
	'skippFirstLine'	=> false,
	'fieldDelimiter' => ','
];

BOTK\SimpleCsvGateway::factory($options)->run();
```


## Contributing to this project

See [Contributing guidelines](CONTRIBUTING.md)

## License

Copyright © 2020 by [LinkedData.Center](http://LinkedData.Center/)®

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
