<?php defined('MRZPN_EXECUTE') or die('Access Denied.');

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Authentication\Adapter\Digest as AuthAdapter;


  class page{

    /**
     * is logged in
     *
     * @var Who knows
     */
    private $user = false;
    private $pageSettings = false;
    private $globalSettings = false;
    private $navigationSettings = false;



    function __construct(){
        $this->_loadPageSettings();
        $this->_loadGlobalSettings();
        $this->_loadNavigationSettings();
    }



    /**
    * Check for an action request and fire the relating function
    *
    */
    public function actions(){

      if(isset($_GET['action'])){

        switch($_GET['action']){
          case 'login':
            $this->_login();
            break;
          case 'logout':
            $this->_logout();
            break;
          case 'get-core-menu':
            $this->_getCoreMenu();
            break;
          case 'page-settings':
            $this->_pageSettings();
            break;
          case 'global-settings':
            $this->_globalSettings();
            break;
          case 'navigation-settings':
            $this->_navigationSettings();
            break;
          case 'save':
            $this->_save();
            break;
          case 'img-upload':
            $this->_imgUpload();
            break;
          case 'img-save':
            $this->_imgSave();
            break;
          case 'img-rotate':
            $this->_imgRotate();
            break;
        }

      }

    }



    /**
    * Create a content area relational to the page URI
    *
    */
    public function contentBlock($name, array $options = []){

      $uri = $this->_getContentURI();

      // [dir][page][content-name]
      $id = $uri.'/'.$name;

      $tag = 'div';
      $path = ROOT.DS.'site'.DS.'content'.DS.$uri.DS.$name.'.html';

      echo "<$tag ";
        if($this->hasLogin()) echo "data-editable data-name='$id'";
      echo ">";
        if(file_exists($path)){
          include $path;
        }
      echo "</$tag>";

    }


    /**
    * Return the path to where the page's content is stored
    *
    */
    private function _getContentURI(){
      $uri = substr($_SERVER['REQUEST_URI'], strlen(WEBROOT));
      if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
      $uri = str_replace('index.php', '', $uri);
      $uri = trim($uri, '/');

      if($uri == ''){
        $uri = 'index';
      }

      return $uri;
    }



    /**
    * Return the core menu
    *
    */
    private function _getCoreMenu(){

      if($this->hasLogin()){
        $settings = array_merge((array) $this->pageSettings, (array) $this->globalSettings);
        $params = array();
        foreach($settings as $key => $value){
          $params["[!$key]"] = $value;
        }

        $nav = '';

        if(isset($this->navigationSettings)){

          foreach((array) $this->navigationSettings as $key => $value){
            $nav .= "<li>";
            $nav .= "<span>$key</span>";
            $nav .= "<span>$value</span>";
            $nav .= "<input type='text' class='mrzpn-navigation-text' value='$key'/>";
            $nav .= "<input type='text' class='mrzpn-navigation-link' value='$value'/>";
            $nav .= "<input type='hidden' name='$key' value='$value' />";
            $nav .= "<a href='JavaScript:void(0);' class='mrzpn-menu-navigation-remove'></a>";
            $nav .= "</li>";
          }

        }


        $params["[!main_nav]"] = $nav;

        echo strtr(file_get_contents(ROOT.DS.'core'.DS.'settings.php'), $params);
        exit;
      }

    }



    /**
    * Return the settings for the individual page being viewed
    *
    */
    private function _loadPageSettings(){

        $uri = $this->_getContentURI();
        $settings = ROOT.DS.'site'.DS.'content'.DS.$uri.DS.'settings.json';

        if(file_exists($settings)){
          $this->pageSettings = json_decode(file_get_contents($settings));
        }

    }



    /**
    * Get the title for the page
    *
    */
    public function title(){

      echo ISSET($this->pageSettings->page_title)?$this->pageSettings->page_title:'';

    }


    /**
    * Get the script for the page
    *
    */
    public function page_script(){

      echo ISSET($this->pageSettings->page_script)?$this->pageSettings->page_script:'';

    }




    /**
    * Return the site wide settings
    *
    */
    private function _loadGlobalSettings(){

        $settings = ROOT.DS.'site'.DS.'content'.DS.'global_settings.json';

        if(file_exists($settings)){
          $this->globalSettings = json_decode(file_get_contents($settings));
        }

    }




    /**
    * Return the site wide navigation
    *
    */
    private function _loadNavigationSettings(){

        $settings = ROOT.DS.'site'.DS.'content'.DS.'navigation.json';

        if(file_exists($settings)){
          $this->navigationSettings = json_decode(file_get_contents($settings));
        }

    }



    /**
    * Get the name for the website
    *
    */
    public function website_name(){

      echo ISSET($this->globalSettings->website_name)?$this->globalSettings->website_name:'';

    }


    /**
    * Get the script for the whole site
    *
    */
    public function global_script(){

      echo ISSET($this->globalSettings->global_script)?$this->globalSettings->global_script:'';

    }


    /**
    * Get the navigation for the whole site
    *
    */
    public function main_navigation(){

      echo "<ul>";

      if(isset($this->navigationSettings)){

        foreach((array) $this->navigationSettings as $key => $value){
          echo "<li>";
            echo "<a href='$value'>$key</a>";
          echo "</li>";
        }

      }

      echo "</ul>";

    }




    /**
    * Pass in the username and password and authenticate
    *
    */
    private function _login(){

      // It will always be called via ajax so we will respond as such
      $result = new stdClass();
      $response = 400;

      if($_SERVER['REQUEST_METHOD'] == 'POST'){

        try {


          $username = $_POST['username'];
          $password = $_POST['password'];

          $path     = ROOT.'/site/config/auth.txt';
          $adapter = new AuthAdapter($path,
                                     'MRZPN',
                                     $username,
                                     $password);

          //$result = $adapter->authenticate($username, $password);

          $auth = new AuthenticationService();
          $result = $auth->authenticate($adapter);


          if(!$result->isValid()){
            $result->error = "Incorrect username and password combination!";
            /*
            foreach ($result->getMessages() as $message) {
                $result->error = "$message\n";
            }
            */
          } else {
                $response = 200;
                $result->url = WEBROOT;
          }



        } catch (Exception $e) {
          $result->error = $e->getMessage();
        }

      }

      // Return the response
      http_response_code($response);
      echo json_encode($result);
      exit;

    }



    /**
    * Check for a login session
    *
    */
    public function hasLogin(){

      $auth = new AuthenticationService();

      if ($auth->hasIdentity()) {
        return true;
      } else {
        return false;
      }

    }


    /**
    * Check if is logged in and logout
    *
    */
    private function _logout(){
      $auth = new AuthenticationService();

      if ($auth->hasIdentity()) {
          // Identity exists; get it
          $auth->clearIdentity();
          $this->user = false;

          header('Location: '.WEBROOT ) ;
      }
    }






    /*
    *
    * ////////////////////////////
    *	ACTIONS
    * ////////////////////////////
    *
    */


    /**
    * Uses POST data to save the page content
    *
    */
    private function _save(){

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          foreach ($_POST as $name => $value) {
              $config = HTMLPurifier_Config::createDefault();
              $purifier = new HTMLPurifier($config);
              $clean_html = $purifier->purify($value);
              file_put_contents(ROOT.'/site/content/'.$name.'.html', $clean_html);
          }

        }

      }


    }



    private function _pageSettings(){

      // It will always be called via ajax so we will respond as such
      $result = new stdClass();
      $response = 400;

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){


          $uri = $this->_getContentURI();

          foreach ($_POST as $name => $value) {
              $config = HTMLPurifier_Config::createDefault();
              $purifier = new HTMLPurifier($config);
              $clean_html = $purifier->purify($value);
          }

          $settings = new stdClass();
          $settings->page_title = isset($_POST['mrzpn-menu-page-title'])?$_POST['mrzpn-menu-page-title']:'';
          $settings->page_script = isset($_POST['mrzpn-menu-page-script'])?$_POST['mrzpn-menu-page-script']:'';

          if(file_put_contents(ROOT.DS.'site'.DS.'content'.DS.$uri.DS.'settings.json', json_encode($settings))){
            $response = 200;
          }

        }

      }


      // Return the response
      http_response_code($response);
      echo json_encode($result);
      exit;


    }



    private function _globalSettings(){

      // It will always be called via ajax so we will respond as such
      $result = new stdClass();
      $response = 400;

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          foreach ($_POST as $name => $value) {
              $config = HTMLPurifier_Config::createDefault();
              $purifier = new HTMLPurifier($config);
              $clean_html = $purifier->purify($value);
          }

          $settings = new stdClass();
          $settings->website_name = isset($_POST['mrzpn-menu-website-name'])?$_POST['mrzpn-menu-website-name']:'';
          $settings->global_script = isset($_POST['mrzpn-menu-global-script'])?$_POST['mrzpn-menu-global-script']:'';

          if(file_put_contents(ROOT.DS.'site'.DS.'content'.DS.'global_settings.json', json_encode($settings))){
            $response = 200;
          }

        }

      }


      // Return the response
      http_response_code($response);
      echo json_encode($result);
      exit;


    }



    private function _navigationSettings(){

      // It will always be called via ajax so we will respond as such
      $result = new stdClass();
      $response = 400;

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          $navigation = array();

          foreach ($_POST as $name => $value) {
            $navigation[$name] = $value;
          }

          if(file_put_contents(ROOT.DS.'site'.DS.'content'.DS.'navigation.json', json_encode($navigation))){
            $response = 200;
          }

        }

      }


      // Return the response
      http_response_code($response);
      echo json_encode($result);
      exit;


    }



    /**
    * Uploads a tmp image to the _tmp directory ready for inserting into a page
    *
    */
    private function _imgUpload(){



      // It will always be called via ajax so we will respond as such
      $result = new stdClass();
      $response = 400;

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          // Mime types of images allowed for upload
          $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];



          // Something that will return the mime type
          $img = getimagesize($_FILES['image']['tmp_name']);



          // Get the extension
          $extension = pathinfo($_FILES['image']['tmp_name'],PATHINFO_EXTENSION);



          // Where is the image currently
          $sourcePath = $_FILES['image']['tmp_name'];



          // Potentially do a lookup on the mime type and set the extension ourselves
          $tmp_name = uniqid().'.'.$extension;

          // Move the image to a _tmp directory which will move again when saved
          $targetPath = ROOT.'/site/files/_tmp/'.$tmp_name;

          // This will be the path returned so the image can be displayed in the modal editor
          $webPath = WEBROOT.'/site/files/_tmp/'.$tmp_name;

          // Validate the mime type against the allowed array
          if(!in_array($img['mime'], $allowed_types)) {
              echo "Only PNG, JPEG or GIF images are allowed!";
              exit;
          } elseif(filesize($sourcePath) > 1000000) {
              echo "You cannot upload an image larger that 1mb";
              exit;
          }

          // Move the file to the _tmp directory for editing
          // return the weburl
          if(move_uploaded_file($sourcePath,$targetPath)){
            $result->url = $webPath;
            $response = 200;
          }

        }

      }
      // Return the response
      http_response_code($response);
      echo json_encode($result);
      exit;


    }



    /**
    * Moves a tmp image into the file system so it can be inerted into the page
    *
    */
    private function _imgSave(){


      $result = new stdClass();
      $response = 400;

      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          $url = strtok($_POST['url'], '?');

          $sourcePath = ROOT.'/site/files/_tmp/'.basename($url);
          $img = getimagesize($sourcePath);
          $new_name = uniqid();

          switch($img['mime']){
              case 'image/png':
                $new_name .= '.png';
                break;
              case 'image/jpeg':
                $new_name .= '.jpg';
                break;
              case 'image/gif':
                $new_name .= '.gif';
                break;
              default:
                echo "Only PNG, JPEG or GIF images are allowed!";
                exit;
            }

          $targetPath = ROOT.'/site/files/'.$new_name;
          $webPath = WEBROOT.'/site/files/'.$new_name;

          if(rename($sourcePath, $targetPath)){
            $result->url = $webPath;
            $result->size = [$img[0],$img[1]];
            $result->alt = 'an image';
            $response = 200;
          } else {
            $response = 400;
          }

        }
      }

      http_response_code($response);
      echo json_encode($result);
      exit;


    }


    /**
    * Rotates an image and returns it
    *
    */
    private function _imgRotate(){

      $result = new stdClass();
      $response = 400;


      if($this->hasLogin()){

        /*
      	*
      	*
      	*	Check the method used is POST
      	*
      	*
      	*/
      	if($_SERVER['REQUEST_METHOD'] == 'POST'){

          $url = strtok($_POST['url'], '?');

          $sourcePath = ROOT.'/site/files/_tmp/'.basename($url);
          $webPath = WEBROOT.'/site/files/_tmp/'.basename($url);
          $degrees = $_POST['direction']=='CCW'?90:-90;
          $info = getimagesize($sourcePath);

          switch($info['mime']){
            case 'image/png':
              $img = imagecreatefrompng($sourcePath);
              $rotate = imagerotate($img, $degrees, 0);
              imagesavealpha($rotate, true);
              imagepng($rotate, $sourcePath);
              break;
            case 'image/jpeg':
              $img = imagecreatefromjpeg($sourcePath);
              $rotate = imagerotate($img, $degrees, 0);
              imagejpeg($rotate, $sourcePath);
              break;
            case 'image/gif':
              $img = imagecreatefromgif($sourcePath);
              $rotate = imagerotate($img, $degrees, 0);
              imagegif($rotate, $sourcePath);
              break;
            default:
              $result->error = "Only PNG, JPEG or GIF images are allowed!";
              $response = 400;
              exit;
          }

          if(isset($img))imagedestroy($img);
          if(isset($rotate))imagedestroy($rotate);

          $info = getimagesize($sourcePath);
          $result->url = $webPath;
          $result->size = [$info[0],$info[1]];
          $response = 200;
        } else {
          $response = 400;
        }
      }

      http_response_code($response);
      echo json_encode($result);
      exit;

    }





  }



 ?>
