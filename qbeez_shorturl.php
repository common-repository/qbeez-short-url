<?php
/*
Plugin Name: QBeez Short Urls
Plugin URI: http://www.qbeez.nl/27
Description: Creating new short urls on the go with QBeez.
Version: 1.0
Author: QBeez
Author URI: http://www.qbeez.nl/27
*/

define('DEFAULT_API_URL', 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/original_url/%s.json');

class QBeez_Short_URL
{
    /**
     * List of short URL website API URLs
     */
    function api_urls()
    {
        return array(
            array(
                'name' => 'qbeez.nl',
                'url'  => 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/original_url/%s.json',
                ),
            array(
                'name' => 'sqb.be',
                'url'  => 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/domain/sqb.be/original_url/%s.json',
                ),
            array(
                'name' => 'qqb.be',
                'url'  => 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/domain/qqb.be/original_url/%s.json',
                ),
            array(
                'name' => 'u2s.be',
                'url'  => 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/domain/u2s.be/original_url/%s.json',
                ),
            array(
                'name' => 'qpt.be',
                'url'  => 'http://api.qbeez.nl/v1/system/create/key/318763AAE6CDE91C030FFFD7C30E93D2/domain/qpt.be/original_url/%s.json',
                ),
            );
    }

    /**
     * Create short URL based on post URL
     */
    function create($post_id)
    {
        if (!$apiURL = get_option('qbeezShortUrlApiUrl')) {
            $apiURL = DEFAULT_API_URL;
        }

        $post = get_post($post_id);
        $pos = strpos($post->post_name, 'autosave');
        if ($pos !== false) {
            return false;
        }
        $pos = strpos($post->post_name, 'revision');
        if ($pos !== false) {
            return false;
        }
        
        /** All urls must be base64 encoded before passing to the api */
        $apiURL = str_replace('%s', base64_encode(get_permalink($post_id)), $apiURL);

        $result = false;

        if (ini_get('allow_url_fopen')) {
            if ($handle = @fopen($apiURL, 'r')) {
                $result = fread($handle, 4096);
                fclose($handle);
            }
        } elseif (function_exists('curl_init')) {
            $ch = curl_init($apiURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $result = @curl_exec($ch);
            curl_close($ch);
        }

        if ($result !== false) {
            
            /** Decode json url and strip down till url */
            $decoded_api_request = json_decode($result, true);
            
            delete_post_meta($post_id, 'QBeezShortURL');
            $res = add_post_meta($post_id, 'QBeezShortURL', $decoded_api_request["data"]["url"], true);
            return true;
        }
    }

    /**
     * Option list (default settings)
     */
    function options()
    {
        return array(
           'ApiUrl'         => DEFAULT_API_URL,
           'Display'        => 'Y',
           );
    }

    /**
     * Plugin settings
     *
     */
    function settings()
    {
        $apiUrls = $this->api_urls();
        $options = $this->options();
        $opt = array();

        if (!empty($_POST)) {
            foreach ($options AS $key => $val)
            {
                if (!isset($_POST[$key])) {
                    continue;
                }
                update_option('qbeezShortUrl' . $key, $_POST[$key]);
            }
        }
        foreach ($options AS $key => $val)
        {
            $opt[$key] = get_option('qbeezShortUrl' . $key);
        }
        include '../wp-content/plugins/qbeez-short-url/template/settings.tpl.php';
    }

    /**
     *
     */
    function admin_menu()
    {
        add_options_page('QBeez Settings', 'QBeez Settings', 10, 'qbeez_shorturl-settings', array(&$this, 'settings'));
    }

    /**
     * Display the short URL
     */
    function display($content)
    {

        global $post;

        if ($post->ID <= 0) {
            return $content;
        }

        $options = $this->options();

        foreach ($options AS $key => $val)
        {
            $opt[$key] = get_option('qbeezShortUrl' . $key);
        }

        $shortUrl = get_post_meta($post->ID, 'QBeezShortURL', true);

        if (empty($shortUrl)) {
            return $content;
        }

        $shortUrlEncoded = urlencode($shortUrl);

        ob_start();
        include './wp-content/plugins/qbeez-short-url/template/public.tpl.php';
        $content .= ob_get_contents();
        ob_end_clean();

        return $content;
    }
}

$gssu = new QBeez_Short_URL;

if (is_admin()) {
    add_action('edit_post', array(&$gssu, 'create'));
    add_action('save_post', array(&$gssu, 'create'));
    add_action('publish_post', array(&$gssu, 'create'));
    add_action('admin_menu', array(&$gssu, 'admin_menu'));
    add_action( 'add_meta_boxes', 'QBeez_info_box' );
    
    function QBeez_info_box() {
        
        add_meta_box(  'myplugin_sectionid', __( 'QBeez short url details', 'qbeez_textdomain' ), '_qbeez_content', 'post'  );
        add_meta_box(  'myplugin_sectionid', __( 'QBeez short url details', 'qbeez_textdomain' ), '_qbeez_content', 'page'  );
        
    }
    
    /* Prints the box content */
    function _qbeez_content( $post ) {
    
      // Use nonce for verification
      wp_nonce_field( plugin_basename( __FILE__ ), 'qbeez-short-url' );
    
      // The actual fields for data entry
  
      _e("Short url for this post: <strong>". get_post_meta($post->ID, 'QBeezShortURL', true) ."</strong><br />");
      _e("Short url analytics page: <strong><a href='". get_post_meta($post->ID, 'QBeezShortURL', true) ."+' target='_blank'>". get_post_meta($post->ID, 'QBeezShortURL', true) ."+</a></strong><br />", 'qbeez_textdomain' );
      _e("Short url QRcode: <strong>". get_post_meta($post->ID, 'QBeezShortURL', true) .".qrcode</strong> <br />", 'qbeez_textdomain' );
      _e("<img src='". get_post_meta($post->ID, 'QBeezShortURL', true) .".qrcode' height='87' width='87' />");
    }

} else {
    add_filter('the_content', array(&$gssu, 'display'));
}