<?php

namespace App\Ogniter\Api\Remote\Base\Task\Request;

/**
 * Class UrlRequest
 * @package App\Ogniter\Api\Remote\Base\Task\Request
 */
abstract class UrlRequest {

    /**
     * @var
     */
    protected $_url;

    /**
     * @var string
     */
    protected $_method = 'GET';

    /**
     * @param $url
     */
    public function setUrl($url){
        $this->_url = $url;
    }

    /*
     * Public interface
     * Define in an abstract or parent class */
    /**
     * @param $url
     * @return string
     * @throws \Exception
     */
    protected function getContents($url){
        if(empty($url)){
            throw new \Exception("You must define an URL");
        }

        $opts = array(
            'http' => array(
                'method'=>$this->_method,
                'header'=>"Content-Type: text/html; charset=utf-8"
            )
        );

        $opts['http']['header'] .= implode("\r\n",$this->buildExtraHeaders())."\r\n";

        $context = stream_context_create($opts);
        $result = @file_get_contents($url,false,$context);
        return $result;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function import(){

        if(empty($this->_url)){
            throw new \Exception("Define a remote source first" );
        }

        $contents = $this->getContents($this->_url);
        if($contents){
            $resources = $this->parseResults($contents);
            if($resources===NULL){
                throw new \Exception("Could not load data from source: ".htmlspecialchars($this->_url).". Invalid XML response");
            }
            return $resources;
        } else {
            throw new \Exception("Could not load data from source: ".htmlspecialchars($this->_url) );
        }
    }

    /**
     * Override this to implement authentication, for example
     *
     * @return array
     */
    protected function buildExtraHeaders()
    {
        return array();
    }

    /*  */
    /**
     * Function that will do something with the results!
     *
     * @param $results
     * @return mixed
     */
    abstract protected function parseResults($results);
}