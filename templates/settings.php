<div class="wrap">
  <h1>Instagram feed</h1>
  <h2>Settings</h2>

  <hr>

  <h3>How to create an Instagram Access Token?</h3>
  <h4>Step 1: Add a new app on "Facebook for Developers"</h4>
  <p>Go to <a href="https://developers.facebook.com/" target="_blank">https://developers.facebook.com/</a> and click on "Add a new app".</p>
  <h4>Step 2: Setup "Instagram Basic Display"</h4>
  <p>Within your newly created app, find "Instagram Basic Display" and click on "Set up"</p>
  <p>Scroll down and click on "Create new App"</p>
  <h4>Step 3: Adding a user to your newly created app</h4>
  <p>Go to your newly created "Instagram Basic Display" app and add a new Instagram Tester. Fill in the account here that you'd like to show up in the feed. Login to the account you just invited and accept the invite.</p>
  <h4>Step 4: Get your Access Token</h4>
  <p>Go to your "Instagram Basic Display" app and find the user you invited if they accepted you can see a "Generate Token" button. Click on the button and copy the access token that is generated.</p>
  <p>Paste this token inside the Access Token field in WordPress (scroll down)</p>
  
  <hr>

  <form method="post" action="options.php">
    <?php settings_fields(INSTAGRAM_FEED_PLUGIN_SLUG); ?>
    <?php do_settings_sections(INSTAGRAM_FEED_PLUGIN_SLUG); ?>

    <table class="form-table">
        <tr valign="top">
          <th scope="row">Access Token</th>
          <td><input type="text" name="<?php echo nbif_option_key(); ?>" style="width: 400px;" value="<?php echo get_option(nbif_option_key()); ?>" /></td>
        </tr>      
    </table>
    
    <?php submit_button('Save'); ?>
  </form>
</div>