<?php
/*
Plugin Name: Gravity Forms Regular Expression Validation
Plugin URI: https://github.com/mmirus/gravityforms-regex-validation
Description: Add regular expression validation option to Gravity Forms single text input
Author: Matt Mirus
Author URI: https://github.com/mmirus
Version: 1.0.1
GitHub Plugin URI: https://github.com/mmirus/gravityforms-regex-validation
*/

namespace GF_RegEx;

class GF_RegEx {
  function __construct() {
    // add field
    add_action('gform_field_advanced_settings', array($this, 'settings_fields'), 10, 2);
    
    // add script
    add_action('gform_editor_js', array($this, 'editor_script'));
    
    // add validation
    add_filter('gform_field_validation', array($this, 'validate'), 10, 4);
  }
  
  // add regex validation setting fields
  public function settings_fields($position, $form_id) {
    //create settings on position 375 (right after "enable password input")
    if ( $position == 375 ) {
      ?>
      <li class="regex_setting field_setting">
        <input type="checkbox" id="field_regex_validation" onclick="SetFieldProperty('regexValidation', this.checked); ToggleInputRegEx()" onkeypress="SetFieldProperty('regexValidation', this.checked); ToggleInputRegEx()" />
        <label for="field_regex_validation" class="inline">
          <?php _e("Use Regular Expression Validation", "gf_regex"); ?>
        </label>
        
        <div id="field_regex_pattern_container" style="padding-top: 10px; display: block;">
          <p>
            <label for="field_regex_pattern" class="inline">RegEx Pattern:&nbsp;</label>
            <input type="text" id="field_regex_pattern" onchange="SetFieldProperty('regexPattern', this.value)" class="fieldwidth-2" placeholder="">
          </p>
          <p>
            <label for="field_regex_message" class="inline">Validation Message:&nbsp;</label>
            <input type="text" id="field_regex_message" onchange="SetFieldProperty('regexMessage', this.value)" class="fieldwidth-2" placeholder="The value provided is not valid for this field.">
          </p>
        </div>
      </li>
      <?php
    }
  }
  
  // Action to inject supporting script to the form editor page
  public function editor_script() {
    ?>
    <script type='text/javascript'>
      // adding setting to fields of type "text"
      fieldSettings.text += ", .regex_setting";

      // binding to the load field settings event to initialize the checkbox
      jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
        jQuery("#field_regex_validation").attr("checked", field["regexValidation"] == true);
        jQuery("#field_regex_pattern").val(field["regexPattern"]);
        jQuery("#field_regex_message").val(field["regexMessage"]);
        
        ToggleInputRegEx(true);
      });
      
      // show / hide regex pattern input field
      function ToggleInputRegEx(isInit){
        var speed = isInit ? "" : "slow";
        if(jQuery('#field_regex_validation').is(":checked")){
          jQuery('#field_regex_pattern_container').show(speed);
        }
        else{
          jQuery('#field_regex_pattern_container').hide(speed);
        }
      }
    </script>
    <?php
  }

  // validate submitted data against provided regex
  public function validate($result, $value, $form, $field) {
    // if validation has passed so far, and regex validation is enabled, and a pattern was provided, and a value was provided
    if ($result['is_valid'] && $field['regexValidation'] && !empty($field['regexPattern']) && !empty($value)) {
      $regex = '/' . $field['regexPattern'] . '/';
      if (preg_match($regex, $value) !== 1) {
        $result['is_valid'] = false;
        
        // use custom regex validation message if provided
        $result['message'] = 'The value provided is not valid for this field.';
        if (!empty($field['regexMessage'])) {
          $result['message'] = $field['regexMessage'];
        }
      }
    }
    
    return $result;
  }
}

new GF_Regex();
