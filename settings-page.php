<?php

add_action('admin_menu', function () {
  add_submenu_page(
    'options-general.php',
    'Instagram Feed',
    'Instagram Feed',
    'manage_options',
    INSTAGRAM_FEED_PLUGIN_SLUG,
    function () {
      include_once __DIR__ . '/templates/settings.php';
    },
    99,
  );
});

add_action('admin_init', function () {
  register_setting(INSTAGRAM_FEED_PLUGIN_SLUG, nbif_option_key());
});