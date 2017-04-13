<?php defined('MRZPN_EXECUTE') or die('Access Denied.'); ?>
<!DOCTYPE html>
  <html>
    <head>

      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="<?=WEBROOT;?>core/css/signin.css" />
      <title>Sign in</title>
    </head>
    <body>

      <div class="container fill-height">
          <div class="row align-items-center fill-height">
            <div class="col-md-7 col-lg-5">
              <div class="card">
                <div class="card-block">
                  <h1 class="card-title">Sign in.</h1>
                  <div id="alert"></div>
                  <form class="form" id="signin">
                    <div class="form-group">
                      <input type="text" name="username" class="form-control" placeholder="Username" />
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <button type="submit" name="signin" class="btn btn-primary btn-block">Sign in</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
      </div>


<script>

      window.addEventListener('load', function() {

        var login, alert;
        login = document.getElementById("signin");
        alert = document.getElementById("alert");


        if(typeof login != 'undefined'){

          login.addEventListener('submit', function(ev){
            ev.preventDefault();
            addClass(login, 'waiting');


            payload = new FormData(login);

            console.log(payload);


            // Send the update content to the server to be saved
            function onStateChange(ev) {

                console.log(ev);
                // Check if the request is finished
                if (ev.target.readyState == 4) {

                    response = JSON.parse(ev.target.responseText);

                    if (ev.target.status == '200') {
                        // Save was successful, notify the user with a flash
                        removeClass(alert, 'alert');
                        removeClass(alert, 'alert-danger');
                        alert.innerHTML = '';
                        window.location.replace(response.url);
                    } else {
                        // Save failed, notify the user with a flash
                        console.log('It\'s not good');
                        removeClass(login, 'waiting');
                        addClass(alert, 'alert alert-danger');
                        alert.innerHTML = response.error;
                    }
                }

            }

            xhr = new XMLHttpRequest();
            xhr.addEventListener('readystatechange', onStateChange);
            xhr.open('POST', '?action=login');
            xhr.send(payload);
          })
        }

        function addClass(ele, className){
          ele.className += ' '+className;
        }

        function removeClass(ele, className){
          var classes, newClass;

          classes = ele.className.split(' ');
          newClass = '';

          for(var _i = 0; _i < classes.length; _i++){
            if(classes[_i] != className){
              newClass += ' '+classes[_i];
            }
          }

          ele.className = newClass.trim();
        }

      });

      </script>

    </body>
  </html>
