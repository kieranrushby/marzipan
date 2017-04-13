<div class='mrzpn-menu-back' id='mrzpn-menu-back'><span></span><span></span></div>
<div class='mrzpn-menu-toggle' id='mrzpn-menu-toggle'><span></span><span></span><span></span></div>

<div class="mrzpn-menu-content">



  <div class="mrzpn-menu-panel mrzpn-menu-main-menu in" id="mrzpn-main-menu">
    <ul>
        <li><a href="JavaScript:void(0);" data-mrzpn-panel="mrzpn-page-settings">Page settings</a></li>
        <li><a href="JavaScript:void(0);" data-mrzpn-panel="mrzpn-navigation">Navigation</a></li>
        <li><a href="JavaScript:void(0);" data-mrzpn-panel="mrzpn-global-settings">Website settings</a></li>
        <li><a href='?action=logout'>Sign out</a></li>
    </ul>
  </div>



  <div class="mrzpn-menu-panel mrzpn-page-settings" id="mrzpn-page-settings">

    <h5 class='title'>Page Settings</h5>

    <form class="mrzpn-menu-form" id="mrzpn-menu-page-settings">
      <div class="mrzpn-menu-form-group">
        <label for="mrzpn-menu-page-title">Page title</label>
        <input type="text" name="mrzpn-menu-page-title" id="mrzpn-menu-page-title" class="mrzpn-menu-form-control" value="[!page_title]" />
      </div>

      <div class="mrzpn-menu-form-group">
        <label for="mrzpn-menu-page-script">Page script tag</label>
        <textarea name="mrzpn-menu-page-script" id="mrzpn-menu-page-script" class="mrzpn-menu-form-control" >[!page_script]</textarea>
      </div>

      <button type="submit" name="save" class="mrzpn-menu-btn">Save</button>
    </form>

  </div>



  <div class="mrzpn-menu-panel mrzpn-navigation" id="mrzpn-navigation">
    <h5 class='title'>Navigation</h5>

    <form class="mrzpn-menu-form" id="mrzpn-menu-navigation-settings">
      <ul id="mrzpn-menu-navigation">
      [!main_nav]
      </ul>
    </form>

    <form id="mrzpn-menu-navigation-add">
      <input type='text' id='mrzpn-navigation-template-text' class='mrzpn-menu-form-control' value='' placeholder='text'/>
      <input type='text' id='mrzpn-navigation-template-link' class='mrzpn-menu-form-control' value='' placeholder='link'/>
      <button type="submit" class="mrzpn-menu-btn" value="submit">Add</button>
    </form>

    <button type="submit" class="mrzpn-menu-btn" id="mrzpn-menu-navigation-settings-submit" value="submit">Save</button>


  </div>



  <div class="mrzpn-menu-panel mrzpn-global-settings" id="mrzpn-global-settings">
    <h5 class='title'>Website Settings</h5>

    <form class="mrzpn-menu-form" id="mrzpn-menu-global-settings">
      <div class="mrzpn-menu-form-group">
        <label for="mrzpn-menu-website-name">Website name</label>
        <input type="text" name="mrzpn-menu-website-name" id="mrzpn-menu-website-name" class="mrzpn-menu-form-control" value="[!website_name]" />
      </div>

      <div class="mrzpn-menu-form-group">
        <label for="mrzpn-menu-global-script">Global script tag</label>
        <textarea name="mrzpn-menu-global-script" id="mrzpn-menu-global-script" class="mrzpn-menu-form-control" placeholder="">[!global_script]</textarea>
      </div>

      <button type="submit" name="save" class="mrzpn-menu-btn">Save</button>
    </form>
  </div>

</div>
