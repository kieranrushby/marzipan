<?php defined('MRZPN_EXECUTE') or die('Access Denied.');

/**
 *
 */
class InstallMarzipan
{

  function __construct()
  {

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])){


      if(!file_exists('site/')){
        mkdir('site');
      }
      if(!file_exists('site/config/')){
        mkdir('site/config');
      }
      if(!file_exists('site/content/')){
        mkdir('site/content');
      }
      if(!file_exists('site/files/')){
        mkdir('site/files');
      }
      if(!file_exists('site/files/tmp/')){
        mkdir('site/files/tmp');
      }

      $str = $_POST['username'].':MRZPN:';
      $str .= md5($str.$_POST['password']);

      file_put_contents('site/config/auth.txt', $str);

      header('Location: '.WEBROOT);
      exit;
    }
  }
}

$install = new InstallMarzipan();

?>


<!DOCTYPE html>
  <html>
    <head>

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="core/css/signin.css" />
      <title>Sign in</title>
    </head>
    <body>

      <div class="container fill-height">
          <div class="row align-items-center fill-height">
            <div class="col-md-7 col-lg-5">
              <div class="card">
                <div class="card-block">
                  <h1 class="card-title">Welcome.</h1>
                  <p>Create a username and password to get started.</p>
                  <div id="alert"></div>
                  <form class="form" method="POST">
                    <div class="form-group">
                      <input type="text" name="username" class="form-control" placeholder="Username" />
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <button type="submit" name="signin" class="btn btn-primary btn-block">Get Started</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>

    </body>
  </html>
