#  No Bloat Instagram Feed
Access your Instagram feed easily through a single php function

Go to "Settings > Instagram Feed" and read the instructions. Your access token will be refreshed automatically and instagram feed will be cached with transients.

```php
$feed_items = nbif_get_instagram_feed();
// $feed_items is an array of objects with the following properties:
// $item->id
// $item->caption
// $item->media_type
// $item->media_url
// $item->permalink
// $item->timestamp
// $item->username

```