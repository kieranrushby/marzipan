<?php defined('MRZPN_EXECUTE') or die('Access Denied.');



class autoroute{

  function __construct(){

    /*
    *
    * GET THE ROUTED URI
    *
    *
    */
    $uri = substr($_SERVER['REQUEST_URI'], strlen(WEBROOT));
    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
    $uri = '/' . trim($uri, '/');





    /*
    *
    *	SPLIT THE URI INTO PARTS
    *
    */
    $uri_parts = array();
    $uri_parts = explode('/', $uri);

    for($i=0;$i<=count($uri_parts);$i++){
      if(empty($uri_parts[$i]) || $uri_parts[$i] == 'index.php'){
        unset($uri_parts[$i]);
      }
    }

    $uri_parts = array_values($uri_parts);




    /*
    *
    *	SETUP THE ARGS ARRAY
    *
    */
    $args =[];





    /*
    *   THE PATH TO LOOKUP AND WHAT WILL BE THE ACTUAL INCLUDING PATH
    */
    $path = ROOT.'\\www\\';
    $inc_path;




    /**
    *
    * Check for a auth.txt
    *
    */
    if(!file_exists(ROOT.DS.'site'.DS.'config'.DS.'auth.txt')){


      // Setup the directories if they don't exist
      if(!file_exists('site/')){
        mkdir('site/');
      }

      if(!file_exists('site/config')){
        mkdir('site/config');
      }

      if(!file_exists('site/content')){
        mkdir('site/content');
      }

      if(!file_exists('site/files')){
        mkdir('site/files');
      }

      if(!file_exists('site/files/_tmp')){
        mkdir('site/files/_tmp');
      }

      // show the install page
      include_once(ROOT.DS.'core'.DS.'install.php');

    } elseif(count($uri_parts) == 0){

      //$inc_path = $path.'index.php';
      include_once($path.'index.php');

    } elseif($uri_parts[0] == 'login'){

      include_once(ROOT.DS.'core'.DS.'login.php');

    } else {


      /*
      *
      *	LOOP BACKWARDS THROUGH THE URL PARTS AND CHECK IF THE PHP PATH EXISTS
      *
      */
      for($i=count($uri_parts)-1;$i>=0;$i--){


      	/*
      	*	Loop through the parts before this one and create a full path to check
      	*/
      	for($j=0;$j<=$i;$j++){
          if(empty($uri_parts[$j])){
            continue;
          }
      		$path .= $uri_parts[$j].DS;
      	}





      	/*
      	*	Trim the last slash off the end and add the .php
      	*/
      	$path = rtrim($path, DS).'.php';




      	/*
      	*	Check if the file exists
      	*/
      	if(file_exists($path)){




      		/*
      		*
      		*	INCLUDE THE PATH AND REVERSE THE ARGS
      		*
      		*/

      		$args = array_reverse($args);
          $inc_path = $path;
      		break;




      	} else {



      		/*
      		*	Add this to args
      		*/
      		$args[] = $uri_parts[$i];



      	}

      }


      /**
      * GO FETCH THE PAGE
      *
      */
      if(!empty($inc_path) && file_exists($inc_path)){
        include_once($inc_path);
      } else{
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        readfile(ROOT.DS.'core'.DS.'404.php');
        exit;
      }

    }




  }

}
