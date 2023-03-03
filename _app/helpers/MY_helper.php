<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('getWebService'))
{
    function getWebService()
    {
		$portRandom = rand(9080,9082);
		$url = 'http://';
		$url .= IP_WS;
		$url .= ':';
		$url .= $portRandom;
		$url .= '/';
		
        return $url;
    }   
}

if ( ! function_exists('RandPortWS'))
{
    function RandPortWS()
    {
		return rand(9594,9599);
    }   
}