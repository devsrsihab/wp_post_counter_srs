<?php 

/*
Plugin Name: SRS Post Counter
Plugin URI: https://srsplugins.com
Description: This is a simple plugin to display the number of posts in a category.
Version: 1.0    
Author: Md. Sohanur Rohman Sihab
Author URI: https://srsplugins.com

 */

 class SRSPostCounter{

    // register the admin page menu action 
    public function __construct(){
        add_action('admin_menu', [$this, 'admin_Page']);
        add_action('admin_init', [$this, 'srspc_settings']);
        add_filter('the_content', [$this, 'ifWrap']);
    }

    //  ifWrap
    function ifWrap($content){
      if (is_main_query() AND is_single() AND 
      (
        get_option('srspc_wordcount', '1') OR
        get_option('srspc_charactercount', '1') OR
        get_option('srspc_readtime', '1')
      )) {
        return $this->createHTML($content);
      }
      return $content;
    }


    // create post counter html in single post page
    function createHTML($content){
      $html = '<h3>' . esc_html(get_option('srspc_headline', 'Post Statistics')) . '</h3><p>';

      // word count 
      if (get_option('srspc_wordcount', '1') || get_option('srspc_readtime', '1')) {
        $word_count = str_word_count(strip_tags($content));
      }

      // word count html 
      if (get_option('srspc_wordcount', '1')) {
        $html .= 'This post has ' . $word_count . ' words.<br>';
      }

      // character count html
      if (get_option('srspc_charactercount', '1')) {
        $html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
      }

      // read time html
      if (get_option('srspc_readtime', '1')) {
          $reading_time_seconds = round(($word_count / 225) * 60); // Convert minutes to seconds
          $minutes = floor($reading_time_seconds / 60); // Get whole minutes
          $seconds = $reading_time_seconds % 60; // Get remaining seconds

          $html .= "This post will take about {$minutes}m {$seconds}s to read.<br>";
      }


      $html .= '</p>';


      // define locaiotn 
      if (get_option('srspc_location', '0') === '0') {
       return $html . $content;
      }
      return $content . $html;
    }

   //  srspc_settings
    function srspc_settings(){
      // setting section 
      add_settings_section('srspc_first_section', null, null, 'srspc_setting_page');

      // display location 
      add_settings_field('srspc_location', 'Display Location', [$this, 'srspc_locationHTML'], 'srspc_setting_page', 'srspc_first_section');
      register_setting('srspc_plugin', 'srspc_location', ['sanitize_callback' => [$this, 'srspc_sanitize_location'], 'default' => '0']);

      // counter heading
      add_settings_field('srspc_headline', 'Headline Text', [$this, 'headingHTML'], 'srspc_setting_page', 'srspc_first_section');
      register_setting('srspc_plugin', 'srspc_headline', ['sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics']);

      // word count 
      add_settings_field('srspc_wordcount', 'Word Count', [$this, 'srspcCheckboxHTML'], 'srspc_setting_page', 'srspc_first_section', ['theName' => 'srspc_wordcount']);
      register_setting('srspc_plugin', 'srspc_wordcount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

      // character count
      add_settings_field('srspc_charactercount', 'Character Count', [$this, 'srspcCheckboxHTML'], 'srspc_setting_page', 'srspc_first_section', ['theName' => 'srspc_charactercount']);
      register_setting('srspc_plugin', 'srspc_charactercount', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);

      // read time
      add_settings_field('srspc_readtime', 'Read Time', [$this, 'srspcCheckboxHTML'], 'srspc_setting_page', 'srspc_first_section', ['theName' => 'srspc_readtime']);
      register_setting('srspc_plugin', 'srspc_readtime', ['sanitize_callback' => 'sanitize_text_field', 'default' => '1']);
   }

    // setup setting option 
    function admin_Page(){
       add_options_page(
         'SRS Post Counter',
         'SRS Counter',
         'manage_options',
         'srspc_setting_page',
         array($this, 'srsHTMLForm'));
    }

   // sanitize location
    function srspc_sanitize_location($input) {
    if ($input != '0' AND $input != '1') {
      add_settings_error('srspc_location', 'srs_counter_location_error', 'Display location must be either beginning or end.');
      return get_option('srspc_location');
    }
    return $input;
  }

    // ============= setting htmls =================
    // 1. srspc_locationHTML
    function srspc_locationHTML(){?>
       
    <select name="srspc_location">
      <option value="0" <?php selected(get_option('srspc_location'), '0') ?>>Beginning of post</option>
      <option value="1" <?php selected(get_option('srspc_location'), '1') ?>>End of post</option>
    </select>
      <?php 
    }

    // 2. headingHTML
    function headingHTML(){ ?>
    <input type="text" value="<?php echo esc_attr(get_option('srspc_headline')) ?>" name="srspc_headline" id="srspc_headline">
    <?php 
    }

    // 3. reuseable checkbox function
    function srspcCheckboxHTML($args) { ?>
    <input type="checkbox" <?php checked(get_option($args['theName'], '1')) ?> value="1" name="<?php echo $args['theName'] ?>" >
    <?php 
    }


    // ============= Setting Section  ================
    function srsHTMLForm(){?>
  
      <div class="wrap">
            <h1>SRS Word Count Settings Section 01</h1>

            <form action="options.php" method="POST">
              <?php
              settings_fields('srspc_plugin');
              do_settings_sections('srspc_setting_page');
              submit_button();
              
              
              ?>
            </form>
      </div>

    <?php
    }
 }

 new SRSPostCounter();

