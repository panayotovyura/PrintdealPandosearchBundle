# PrintdealPandosearchBundle [![build status](https://travis-ci.org/printdeal/PrintdealPandosearchBundle.png?branch=master)](https://travis-ci.org/printdeal/PrintdealPandosearchBundle) #

## About ##

This bundle integrates [Enrise](https://enrise.com) search into your Symfony application.

## Prerequisite ##

Minimal php version is 7

## Installation ##

Add the `printdeal/pandosearch-bundle` package to your `require` section in the `composer.json` file.

``` bash
$ composer require printdeal/pandosearch-bundle 1.0.0
```

Add the PrintdealPandosearchBundle to your application's kernel:

``` php
<?php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Printdeal\PandosearchBundle\PrintdealPandosearchBundle(),
        // ...
    );
    ...
}
```

## Usage ##

Configure the `printdeal_pandosearch` in your `config.yml`:

``` yaml
printdeal_pandosearch:
    company_name: 'company.com'
```

also you can add default parameters for search request:
``` yaml
printdeal_pandosearch:
    company_name: 'company.com'
    query_settings:
        track: false
        full: true
        nocorrect: true
        notiming: true
```

for more convenience - optional parameter with a custom entity for deserialization can be used:

``` yaml
printdeal_pandosearch:
    deserialization_parameters:
            search_response_entity: Printdeal\PandosearchBundle\Entity\Search\CustomResponse
            suggestion_response_entity: Printdeal\PandosearchBundle\Entity\Suggestion\CustomResponse
```

In controller you can use your search:

 ``` php
 <?php
 // get search results
 $searchCriteria = new SearchCriteria();
 $searchCriteria->setQuery('searchString');
 $this->get('printdeal_pandosearch')->search($searchCriteria);
 
 // get search suggestions
 $suggestCriteria = new SuggestCriteria();
 $suggestCriteria->setQuery('searchString');
 $this->get('printdeal_pandosearch')->suggest($suggestCriteria);
 ```
