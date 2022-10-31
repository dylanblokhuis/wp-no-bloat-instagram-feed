<?php
/*
Plugin Name: No Bloat Instagram Feed
Description: Access your Instagram feed easily through a single php function
Version:     1.0.0
Author:      Dylan Blokhuis
*/

define('INSTAGRAM_FEED_PLUGIN_SLUG', 'no-bloat-instagram-feed');

function nbif_option_key() {
  return "no_bloat_instagram_feed_access_token";
}

function nbif_option_token_expiry_date() {
  return "no_bloat_instagram_feed_token_expiry_date";
}

/**
 * @param string $access_token
 * @return mixed int|null
 */
function nbif_refresh_access_token($access_token) {
  $refresh_token_response = wp_remote_get("https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=" . $access_token);
  if (is_wp_error($refresh_token_response)) {
    error_log("Error refreshing Instagram access token: " . $refresh_token_response->get_error_message());
    return null;
  }
  if ($refresh_token_response['response']['code'] !== 200) {
    error_log("Error refreshing Instagram access token: " . $refresh_token_response['body']);
    return null;
  }

  $refresh_token_response_body = json_decode($refresh_token_response['body']);
  $date = new DateTime();
  $date->add(new DateInterval('PT' . $refresh_token_response_body->expires_in . 'S'));
  return $date->getTimestamp();
}

/**
 * Gets the stored access token, if it's almost expired, refreshes it
 * 
 * @return string|WP_Error
 */
function nbif_get_access_token() {
  $access_token = get_option(nbif_option_key());
  if (!$access_token) {
    return new WP_Error(500, "No access token set");
  }

  // refresh the token if there's no expiry date
  if (!get_option(nbif_option_token_expiry_date())) {
    $expiry_date = nbif_refresh_access_token($access_token);
    if (!$expiry_date) {
      return new WP_Error(500, "Error refreshing Instagram access token");
    }
    update_option(nbif_option_token_expiry_date(), $expiry_date);
  }

  $expiry_date = intval(get_option(nbif_option_token_expiry_date()));
  $five_days_before_now = time() - 432000;

  // if the token is almost expired, refresh it, since it cant be refreshed if it's expired.
  if ($expiry_date < $five_days_before_now) {
    $new_expiry_date = nbif_refresh_access_token($access_token);
    if (!$new_expiry_date) {
      return new WP_Error(500, "Error refreshing Instagram access token");
    }

    update_option(nbif_option_token_expiry_date(), $new_expiry_date);
  }

  return $access_token;
}

/**
 * Gets the instagram feed from the instagram api
 * @return array|WP_Error
 */
function nbif_get_instagram_feed() {
  $cached_value = get_transient('nbif_instagram_feed');
  if ($cached_value) {
    return json_decode($cached_value)->data;
  }

  $access_token = nbif_get_access_token();
  if (is_wp_error($access_token)) {
    return $access_token;
  }

  $response = wp_remote_get("https://graph.instagram.com/me/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username&access_token=" . $access_token);
  if (is_wp_error($response)) {
    return new WP_Error(500, "Error fetching Instagram feed: " . $response->get_error_message());
  }
  if ($response['response']['code'] !== 200) {
    return new WP_Error(500, "Error fetching Instagram feed: " . $response['body']);
  }

  $five_minutes = 300;
  set_transient("nbif_instagram_feed", $response['body'], $five_minutes);
  
  return json_decode($response['body'])->data;
}

require_once "settings-page.php";
