window.addEventListener('load', function() {

  /**
  * Load the menu
  */
  var m, h, l;

  m = document.createElement("div");
  m.id = "mrzpn-menu";
  m.className = "mrzpn-menu";


  var nav = false;


  // Define a function to handle what happens when the core menu content is loaded
  xhrComplete = function (ev) {

      // Check the request is complete
      if (ev.target.readyState !== 4) {
          return;
      }

      // Clear the request
      xhr = null;
      xhrComplete = null;

      // Handle the result of the rotation
      if (parseInt(ev.target.status) === 200) {


          // Unpack the response (from JSON)
          // and add the content to the menu
          m.innerHTML = ev.target.responseText;

          // add the menu to the DOM
          document.body.appendChild(m);

          // Add a click event to the menu toggle
          var t = document.getElementById("mrzpn-menu-toggle");
          t.addEventListener('click', toggleMrzpnMenu);

          // Add a click event to the menu toggle
          var b = document.getElementById("mrzpn-menu-back");
          b.addEventListener('click', showMrzpnMenuHomePanel);

          // Add a click event to the menu toggle
          var a = document.getElementById("mrzpn-menu-navigation-add");
          a.addEventListener('submit', addMrzpnMenuNavigationItem);

          var d = document.getElementById("mrzpn-menu-navigation").getElementsByClassName("mrzpn-menu-navigation-remove");
          for(var i = 0; i < d.length; i++){
              d[i].addEventListener('click', removeMrzpnMenuNavigationItem);
          }

          var n = document.getElementById("mrzpn-main-menu").getElementsByTagName("a");
          for(var i = 0; i < n.length; i++){
            if(typeof n[i].getAttribute("data-mrzpn-panel") != 'undefined'){
              n[i].addEventListener('click', showMrzpnMenuPanel);
            }
          }

          // add a submit event to the page menu
          var p = document.getElementById("mrzpn-menu-page-settings");
          p.addEventListener('submit', submitMrzpnPageMenu);

          // add a submit event to the global menu
          var g = document.getElementById("mrzpn-menu-global-settings");
          g.addEventListener('submit', submitMrzpnGlobalMenu);

          // add a submit event to the global menu
          var z = document.getElementById("mrzpn-menu-navigation-settings");
          z.addEventListener('submit', submitMrzpnNavigationMenu);

          // add a submit event to the global menu
          var y = document.getElementById("mrzpn-menu-navigation-settings-submit");
          y.addEventListener('click', submitMrzpnNavigationMenu);

      } else {

      }
  };

  xhr = new XMLHttpRequest();
  xhr.addEventListener('readystatechange', xhrComplete);
  xhr.open('GET', '?action=get-core-menu');
  xhr.send();





  /**
  * Submit the page form
  */
  submitMrzpnPageMenu = function(ev){

    var set  = document.getElementById('mrzpn-menu-page-settings');
    ev.preventDefault();

    addMrzpnClass(set, 'mrzpn-waiting');


    payload = new FormData(set);

    console.log(payload);


    // Send the update content to the server to be saved
    function onStateChange(ev) {

        console.log(ev);
        // Check if the request is finished
        if (ev.target.readyState == 4) {

            response = JSON.parse(ev.target.responseText);

            if (ev.target.status == '200') {
                // Save was successful, notify the user with a flash
                removeMrzpnClass(set, 'mrzpn-waiting');
                document.getElementsByTagName('title')[0].innerHTML = document.getElementById('mrzpn-menu-page-title').value;

            } else {
                // Save failed, notify the user with a flash
                console.log('It\'s not good');
                removeMrzpnClass(set, 'mrzpn-waiting');
            }
        }

    }

    xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', onStateChange);
    xhr.open('POST', '?action=page-settings');
    xhr.send(payload);

  };





  /**
  * Submit the global form
  */
  submitMrzpnGlobalMenu = function(ev){

    var set  = document.getElementById('mrzpn-menu-global-settings');
    ev.preventDefault();

    addMrzpnClass(set, 'mrzpn-waiting');


    payload = new FormData(set);


    // Send the update content to the server to be saved
    function onStateChange(ev) {

        // Check if the request is finished
        if (ev.target.readyState == 4) {

            response = JSON.parse(ev.target.responseText);

            if (ev.target.status == '200') {

                // Save was successful, notify the user with a flash
                removeMrzpnClass(set, 'mrzpn-waiting');

            } else {
                // Save failed, notify the user with a flash
                console.log('It\'s not good');
                removeMrzpnClass(set, 'mrzpn-waiting');
            }
        }

    }

    xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', onStateChange);
    xhr.open('POST', '?action=global-settings');
    xhr.send(payload);

  };




  /**
  * Submit the navigation form
  */
  submitMrzpnNavigationMenu = function(ev){

    var set  = document.getElementById('mrzpn-menu-navigation-settings');
    ev.preventDefault();

    addMrzpnClass(set, 'mrzpn-waiting');


    payload = new FormData(set);


    // Send the update content to the server to be saved
    function onStateChange(ev) {

        // Check if the request is finished
        if (ev.target.readyState == 4) {

            console.log(ev);
            response = JSON.parse(ev.target.responseText);

            if (ev.target.status == '200') {

                // Save was successful, notify the user with a flash
                removeMrzpnClass(set, 'mrzpn-waiting');

            } else {
                // Save failed, notify the user with a flash
                console.log('It\'s not good');
                removeMrzpnClass(set, 'mrzpn-waiting');
            }
        }

    }

    xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', onStateChange);
    xhr.open('POST', '?action=navigation-settings');
    xhr.send(payload);

  };







  /**
  * Show and hide the menu
  */
  toggleMrzpnMenu = function(){
    var cnames = m.className.split(' ');
    if(cnames.indexOf('visible') == -1){
        m.className = m.className + ' visible';
    } else {
      m.className = '';
      for(var _i = 0; _i < cnames.length; _i++){
        if(cnames[_i] != 'visible'){
          m.className = m.className + cnames[_i];
        }
      }
    }
  };







  /**
  * Show and hide a panel
  */
  showMrzpnMenuPanel = function(){
    var a = this.getAttribute('data-mrzpn-panel');
    var b = document.getElementById('mrzpn-menu-back');
    var m  = document.getElementById('mrzpn-main-menu');
    if(typeof a != 'undefined'){
        var p = document.getElementById(a);

        if(typeof p != 'undefined'){
          addMrzpnClass(p, 'in');
          addMrzpnClass(b, 'show');
          removeMrzpnClass(m, 'in');
        }
    }
  };







  /**
  * Show and hide the home panel
  */
  showMrzpnMenuHomePanel = function(){
    var p = document.getElementsByClassName('mrzpn-menu-panel');
    for(var i = 0; i < p.length; i++){
        removeMrzpnClass(p[i], 'in');
    }

    var m  = document.getElementById('mrzpn-main-menu');
    var b = document.getElementById('mrzpn-menu-back');
    if(typeof m != 'undefined'){
        addMrzpnClass(m, 'in');
        removeMrzpnClass(b, 'show');
    }
  };




  /**
  * Add an item to the main navigation
  */
  addMrzpnMenuNavigationItem = function(ev){

    ev.preventDefault();

    // we need to make a copy of the template item
    var o, t, l, n, s1, s2, nt, nl, h, a
    o = document.getElementById('mrzpn-navigation-template');
    t = document.getElementById('mrzpn-navigation-template-text');
    l = document.getElementById('mrzpn-navigation-template-link');

    n = document.createElement('li');
    s1 = document.createElement('span');
    s2 = document.createElement('span');
    nt = t.cloneNode();
    nl = t.cloneNode();
    h = document.createElement('input');
    a = document.createElement('a');

    s1.innerHTML = t.value;
    s2.innerHTML = l.value;

    nt.id = '';
    nl.id = '';

    h.type = 'hidden';
    h.name = t.value;
    h.value = l.value;

    a.className = 'mrzpn-menu-navigation-remove';
    a.setAttribute('href', 'JavaScript:void(0)');
    a.setAttribute('data-mrzpn-menu-navigation-remove', null);
    a.addEventListener('click', removeMrzpnMenuNavigationItem);

    n.appendChild(s1);
    n.appendChild(s2);
    n.appendChild(nt);
    n.appendChild(nl);
    n.appendChild(h);
    n.appendChild(a);

    document.getElementById('mrzpn-menu-navigation').insertBefore(n, o);
    t.value = '';
    l.value = '';

    t.focus();
    // add a hidden input with title and link
    // append the title and link to the spans
    // append the new item before the template
    // clear out the template
  }




  /**
  * Remove an item from the main navigation
  */
  removeMrzpnMenuNavigationItem = function(){
    var li = this.parentElement;
    var ul = li.parentElement;
    ul.removeChild(li);
  };





  /**
  * Add a class
  */
  addMrzpnClass = function(ele, className){
    ele.className += ' '+className;
  };




  /**
  * Remove a class
  */
  removeMrzpnClass = function(ele, className){
    var classes, newClass;

    classes = ele.className.split(' ');
    newClass = '';

    for(var _i = 0; _i < classes.length; _i++){
      if(classes[_i] != className){
        newClass += ' '+classes[_i];
      }
    }

    ele.className = newClass.trim();
  };

});
