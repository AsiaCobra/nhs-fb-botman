<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */
if ( !class_exists('NhsBotmanSetting' ) ):
class NhsBotmanSetting {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_footer', array($this, 'wp_enqueue_scripts') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'FB Bot Settings', 'FB Bot Settings', 'delete_posts', 'settings_fbbot', array($this, 'plugin_page') );
    }
    function wp_enqueue_scripts() {
         
        //wp_enqueue_style('string $handle', mixed $src, array $deps, mixed $ver, string $meida );
       wp_enqueue_style('customBotCss', NHSBOTMAN_PLUGIN_CSS_DIR . 'custom.bot.css', array(), '1.0.0', 'all' );
       //wp_enqueue_style('string $handle', mixed $src, array $deps, mixed $ver, bol $in_footer );
       wp_enqueue_script('jqueryclonerjs', NHSBOTMAN_PLUGIN_JS_DIR. 'jquery.cloner.js', array('jquery'), '1.0.0', 'true' );
       wp_enqueue_script('nhs-bot-custom-js', NHSBOTMAN_PLUGIN_JS_DIR. 'nhs-bot-custom.js', array('jquery'), '1.0.0', 'true' );
        ?>
        <!-- <script src="<?php echo NHSBOTMAN_PLUGIN_JS_DIR. 'jquery.cloner.js'; ?>"></script> -->
        <?php
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'facebook',
                'title' => __( 'Facebook Settings', 'wedevs' )
            ),
            array(
                'id'    => 'hearing',
                'title' => __( 'Hearing', 'wedevs' )
            ),
            array(
                'id'    => 'list_items',
                'title' => __( 'Lists', 'wedevs' )
            ),
             
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'facebook' => array(
                array(
                    'name'              => 'token',
                    'label'             => __( 'Page Token', 'wedevs' ),
                    'placeholder'       => __( 'Page Token', 'wedevs' ),
                    'type'              => 'text',
                ),
                array(
                    'name'              => 'app_secret',
                    'label'             => __( 'App Secret', 'wedevs' ),
                     'placeholder'       => __( 'App Secret', 'wedevs' ),
                
                    'type'              => 'text',
                ),
                array(
                    'name'              => 'verification',
                    'label'             => __( 'Verification', 'wedevs' ),
                     'placeholder'       => __( 'Verification', 'wedevs' ),
                
                    'type'              => 'text',
                ),
                array(
                    'name'              => 'get_started',
                    'label'             => __( 'Welcome Text', 'wedevs' ),
                    'placeholder'       => __( 'Send Will After Get Started Button', 'wedevs' ),
                
                    'type'              => 'textarea',
                ),
                array(
                    'name'              => 'menu',
                    'label'             => __( 'Persistent Menu', 'wedevs' ),
                    'placeholder'       => __( 'Send Will After Get Started Button', 'wedevs' ),
                
                    'type'              => 'multi_text',
                    'callback'            => 'callback_persistent_menu',
                    'options'           => array(                        
                        array(                        
                        'button'        => "Button Group",
                        ),     
                    ),
                ),
                array(
                    // 'name'              => 'dsafdas',
                    'label'             => __( 'Update Persistent Menu', 'wedevs' ),
                    'placeholder'       => __( 'Send Will After Get Started Button', 'wedevs' ),
                
                    // 'type'              => 'multi_text',
                    'callback'            => 'callback_set_persistent_menu',
                    
                ),
                
            ), 
            'hearing' => array(
                array(
                    'id'         => "id",
                    'name'       => "listen",
                    // 'name'       => "0",
                    'desc'       => __( '', 'wedevs' ),
                    'type'              => 'multi_text',
                    'callback'            => 'callback_multi_text',
                    // 'label'   => __( 'Hearing Lists', 'wedevs' ),
                    'options'           => array(                        
                        array(
                        'keyword'   => "Payload",
                        'textarea'  => "Reply Text",
                        'show_button'   => "Show Button Group",
                        'button'    => "Button Group",
                        // 'image_url' => "Image URL",
                        ),
                         
                    ),
                ),
               
                 
                
            ), 
            'list_items' => array(
                array(
                    'id'         => "id",
                    'name'       => "lists",
                    'type'              => 'multi_text',
                    'callback'            => 'callback_list_multi_text',
                    'options'           => array(                        
                        array(
                        'title'         => "Title",
                        'subtitle'      => "Sub Title",
                        'image'         => "Image URL",
                        'button'        => "Button Group",
                        
                        ),                       
                                         
                         
                    ),
                ),
               
                 
                
            ), 
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
new NhsBotmanSetting();
endif;

/**
 * Get the value of a settings field
 *
 * @param string  $option  settings field name
 * @param string  $section the section name this field belongs to
 * @param string  $default default text if it's not found
 * @return string
 */
function bot_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}
function callback_multi_text($args){
        $hearings   = bot_get_option('listen','hearing');
        $value      = bot_get_option( $args['id'], $args['section'], $args['std'] );
        // echo $args['id'] . $args['section'];
        $options    = $args['options'];
        if( isset($value['keyword']) ){
            foreach ($value['keyword'] as $key => $val) {
                if( ! isset($options[$key]) ) $options[$key]    = $options[0];
            }
        }
        // print_r($value['button']);
        $html = "";
        $html  .= '<fieldset  class="hearing" data-toggle="cloner22">';
        foreach ( $options as $index => $option ) {
            $indexCount = $index;
            $indexCount++;
            $clonable = "<div  class='clonable clonable-clone'  data-clone-number='$indexCount'>";
            if($indexCount == 1)
                $clonable = "<div  class='clonable clonable-source hearable'  data-clone-number='$indexCount'>";
            $html .=  $clonable;
            $type =  "text";
            $size =  "regular";
            $placeholder =  "";
            $id = $args['id'];
            $section = $args['section'];
            $html    .= "<label class=' list-title clonable-increment-html'> Hearing $indexCount</label>";

            foreach( $option as $key => $label ){
                $val   =  $value[$key][$index] ?? "";
               
                $html    .= "<label for='' class='clonable-increment-html $id-$key'>$label $indexCount </label> ";
                if( $key == 'keyword' ){
                    // $checked = isset( $value[$key] ) ? $value[$key] : '0';
                    $html    .= sprintf( '<div class="input-group"><input type="%1$s" class="%2$s-text" id="%3$s[%4$s][%5$s]" name="%3$s[%4$s][%5$s][]" value="%6$s"%7$s/></div>', $type, $size, $args['section'], $id,$key, $val, $placeholder );
                    
                }elseif($key == 'textarea'){
                    // $html    .= '<div style="max-width: 500px;">';
                    // $editor_settings = array(
                    //     'teeny'         => true,
                    //     'textarea_name' => "{$args['section']}[$id][$key][]",
                    //     // 'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
                    //     'textarea_rows' => 10,
                    //     'editor_height' => 70,
                    //     'media_buttons' => false,
                    // );
                    // $textareaID = "{$args['section']}-$id-$key-$index";
                    // $html    .=nhs_get_wp_editor( $val, $textareaID, $editor_settings );
                    // $html    .= '</div>';
                    $html     .= sprintf( '<div class="input-group"><textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s][%4$s][]"%5$s>%6$s</textarea></div>', $size, $args['section'], $id,$key, $placeholder, $val );
                }elseif($key == "button"){
                    $html     .= "<div class='clonable-block button-clone s'  data-toggle='cloner' data-options='{\"clearValueOnClone\":false}'>";
                    $buttonDecrement = 3;
                    if(!$val) $val = array('name'=> ['button']);
                    $buttons =  $val;

                    if( isset($buttons['name']) && is_array($buttons['name']) ) $buttons = $val['name'];
                    foreach($buttons as $b_index => $button){
                        $button_name        = $button['name'] ?? "";
                        $button_type        = $button['type'] ?? "url";
                        $button_url         = $button['url'] ?? home_url();
                        $button_payload     = $button['payload'] ?? "";

                        if( isset($val['name'])  ){
                            $button_name        = $val['name'][$b_index] ?? "";
                            $button_type        = $val['type'][$b_index] ?? "url";
                            $button_url         = $val['url'][$b_index] ?? home_url();
                            $button_payload     = $val['payload'][$b_index] ?? "";
                            $button_url         = $button_payload;
                        }

                        $html     .= "<div class='clonable clonable-clone-number-decrement' data-clone-number='$buttonDecrement'>";
                        // $html     .= "$b_index $button_name $button";
                        
                        $html     .= "<label class='clonable-increment-for clonable-increment-html'> Button Name 1 </label>";
                        $html     .= "<div class='input-group'>";
                        $html     .= "<input type='text' class='$size-text clonable-increment button-increment' id='{$section}[$id][$key]' name='{$section}[$id][$key][$index][name][]' value='$button_name'/>";
                        $html     .= "</div>";
                        $html     .= "<div class='input-group'>";
                            $html     .= "<div class='input-group-list'>";
                            
                            $html     .= "<label class='clonable-increment-for clonable-increment-html'> Type 1 </label>";
                                // $html     .= "<label class='clonable-increment-for'> Type 1 </label>";
                                $html     .= "<select class='list-button-type clonable-increment button-increment' name='{$section}[$id][$key][$index][type][]' data-value='$button_type'>";
                                $html     .= "<option value='url'>URL</option>";
                                $html     .= "<option value='postback'>Post Back</option>";
                                $html     .= "<option value='phone_number'>Phone Number</option>";
                                $html     .= "</select>";                            
                            $html     .= "</div>";
    
                            $html     .= "<div class='input-group-list group-list-filter url'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> URL</label>";                       
                                $html     .= "<input type='text' class='$size-text clonable-increment' id='{$section}[$id][$key][\"url\"]' name='{$section}[$id][$key][$index][payload][]' value='$button_url'/>";
                            $html     .= "</div>";
                            $html     .= "<div class='input-group-list group-list-filter payload phone_number d-none'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> Phone Number</label>";                       
                                $html     .= "<input type='text' class='$size-text clonable-increment' id='{$section}[$id][$key][\"payload\"]' name='{$section}[$id][$key][$index][payload][]' value='$button_payload'/>";
                            $html     .= "</div>";
                            $html     .= "<div class='input-group-list group-list-filter payload postback d-none'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> PostBack keyword </label>";                       
                                $html     .= "<select name='{$section}[$id][$key][$index][payload][]' class='clonable-increment' data-value='$button_payload'>";
                                if( isset($hearings['keyword']) ){
                                    foreach( $hearings['keyword'] as $key_index => $keyword ){
                                        $html     .= "<option value='$keyword'>$keyword</option>";
                                    }
                                }                            
                                $html     .= "</select>";   
    
    
                            $html     .= "</div>";
    
                        $html     .= "</div>";                    
                        $html       .= '<button type="button" class="btn button-cancel clonable-button-close">Delete Button</button>';
                        $html       .= "</div>";
                        $buttonDecrement--;
                    }

                    $html       .= '<button class="clonable-button-add button-primary" type="button">Add New Button</button>';
                    $html       .= "</div>";
                }elseif($key == 'show_button' || $key == 'show_image'){
                    $val = empty($val) ? 'off' : $val;
                    $checked = $val == 'on' ? 'checked': '';
                    // $html    .= sprintf( '<div class="input-group"><input type="%1$s" class="%2$s-text" id="%3$s[%4$s][%5$s]" name="%3$s[%4$s][%5$s][]" value="%6$s"%7$s/></div>', $type, $size, $args['section'], $id,$key, $val, $placeholder );
                    // $html     .= "$val";
                    $html     .= "<input $checked  type='checkbox' class='$size-text checkbox'  value='$val'/>";
                    $html     .= "<input type='hidden' class='hidded_input clonable-increment-name'  name='{$section}[$id][$key][$index]' value='$val'/>";
                    $html     .= "<div class='input-group'>";
                    $html     .= "</div>";
                }else{
                    $html    .= sprintf( '<div class="input-group"><input type="%1$s" class="%2$s-text" id="%3$s[%4$s][%5$s]" name="%3$s[%4$s][%5$s][]" value="%6$s"%7$s/></div>', $type, $size, $args['section'], $id,$key, $val, $placeholder );
                }
            }
            $html .= '<button type="button" class="btn button-cancel clonable-button-close">Delete Hearing</button>';
            $html .= '</div>';
            if( $index >= ( count($options) - 1 ))
                $html .= '<button class="clonable-button-add button-primary"" type="button">Add New Hearing</button>';
        }
        $html .= '</fieldset>';    

        echo $html;
}
function callback_list_multi_text($args){
        $hearings   = bot_get_option('listen','hearing');
        $list_items   = get_option('list_items');
        $list_items   = $list_items['lists'] ?? array();
        $list_default_items   = get_default_items();
        if( $list_items ) $list_default_items = $list_items;
       
        $value      = bot_get_option( $args['id'], $args['section'], $args['std'] );
        $options    = $args['options'];
        for ($i=0; $i < count($list_default_items); $i++) { 
             if( ! isset($options[$i]) ) $options[$i]    = $options[0];
        }
        
        // print_r($options);
        $indexDecrement = 10;
        $html = "";
        $html  .= "<fieldset  class='clonable-block' data-toggle='cloner' data-options='{\"clearValueOnClone\":false}'>";
        foreach ( $options as $index => $option ) {
            $indexCount = $index;
            $indexCount++;
            
            if($index == 0){
                $html  .= "<div  class='clonable clonable-clone-number-decrement clonable-source'  data-clone-number='$indexDecrement'>";
            }else{
                $html  .= "<div  class='clonable clonable-clone-number-decrement clonable-clone'  data-clone-number='$indexDecrement'>";
            }
            // $html  .= "<div  class='clonable clonable-clone-number-decrement' data-ss='1' data-clone-number='$indexCount'>";
            $type =  "text";
            $size =  "regular";
            $placeholder =  "";
            $id = $args['id'];
            $section = $args['section'];
            $html    .= "<label class=' list-title clonable-increment-html'> List $indexCount</label>";
            foreach( $option as $key => $label ){
                // $val   =  $value[$key][$index] ?? "$key $index";
                $val   =  $list_default_items[$index][$key] ?? "$key $index";
               
                $html    .= "<label for='' class='clonable-increment-html'>$label $indexCount</label>";
                if( $key == 'keyword' ){
                    // $html    .= sprintf( '<div class="input-group"><input type="%1$s" class="%2$s-text" id="%3$s[%4$s][%5$s]" name="%3$s[%4$s][%5$s][]" value="%6$s"%7$s/></div>', $type, $size, $args['section'], $id,$key, $val, $placeholder );
                    $html    .= "<div class='input-group'><input type='$type' class='$size-text clonable-increment-name' id='' name='{$args['section']}[$id][$index][$key]' value='$val'/></div>";
                    
                }elseif($key == 'textarea'){
                    $html    .= '<div style="max-width: 500px;">';

                        $editor_settings = array(
                            'teeny'         => true,
                            'textarea_name' => "{$args['section']}[$id][$key][]",
                            'textarea_rows' => 10,
                            'editor_height' => 70,
                            'media_buttons' => false,
                        );
                        $textareaID = "{$args['section']}-$id-$key-$index";
                        $html    .=nhs_get_wp_editor( $val, $textareaID, $editor_settings );
                    $html    .= '</div>';

                }elseif($key == 'image'){
                    // $html  .= sprintf( '<div class="input-group"><input type="text" class="%1$s-text wpsa-url clonable-increment-name" id="%2$s[%3$s][%4$s]" name="%2$s[%3$s][0][%4$s]" value="%5$s"/>', $size, $args['section'], $id,$key, $val, );
                    $image = "<img src='$val' class='lists-img'>";
                    $html  .= "<div class='input-group'>$image<input type='text'  class='$size-text wpsa-url clonable-increment-name' id='{$section}[$id][$index][$key]' name='{$section}[$id][$index][$key]' value='$val'/>";
                    $html  .= '<input type="button" class="button wpsa-browse" value="Choose Image" /></div>';
                }elseif($key == 'button'){
                    // print_r($val);
                    $html     .= "<div class='clonable-block button-clone s'  data-toggle='cloner' data-options='{\"clearValueOnClone\":false,\"incrementName\":\"button-increment\"}'>";
                    $buttonDecrement = 3;
                    $buttons = $val;
                    if( isset($buttons['name']) && is_array($buttons['name']) ) $buttons = $val['name'];
                    foreach($buttons as $b_index => $button){
                        $button_name        = $button['name'] ?? "";
                        $button_type        = $button['type'] ?? "url";
                        $button_url         = $button['url'] ?? home_url();
                        $button_payload     = $button['payload'] ?? "";

                        if( isset($val['name'])  ){
                            $button_name        = $val['name'][$b_index] ?? "";
                            $button_type        = $val['type'][$b_index] ?? "url";
                            $button_url         = $val['url'][$b_index] ?? home_url();
                            $button_payload     = $val['payload'][$b_index] ?? "";
                            $button_url         = $button_payload;
                        }

                        $html     .= "<div class='clonable clonable-clone-number-decrement' data-clone-number='$buttonDecrement'>";
                        // $html     .= "$b_index $button_name $button";
                        
                        $html     .= "<label class='clonable-increment-for clonable-increment-html'> Button Name 1 </label>";
                        $html     .= "<div class='input-group'>";
                        $html     .= "<input type='text' class='$size-text clonable-increment button-increment' id='{$section}[$id][$key]' name='{$section}[$id][$index][$key][name][]' value='$button_name'/>";
                        $html     .= "</div>";
                        $html     .= "<div class='input-group'>";
                            $html     .= "<div class='input-group-list'>";
                            
                            $html     .= "<label class='clonable-increment-for clonable-increment-html'> Type 1 </label>";
                                // $html     .= "<label class='clonable-increment-for'> Type 1 </label>";
                                $html     .= "<select class='list-button-type clonable-increment button-increment' name='{$section}[$id][$index][$key][type][]' data-value='$button_type'>";
                                $html     .= "<option value='url'>URL</option>";
                                $html     .= "<option value='postback'>Post Back</option>";
                                $html     .= "<option value='phone_number'>Phone Number</option>";
                                $html     .= "</select>";                            
                            $html     .= "</div>";
    
                            $html     .= "<div class='input-group-list group-list-filter url'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> URL</label>";                       
                                $html     .= "<input type='text' class='$size-text clonable-increment' id='{$section}[$id][$key][\"url\"]' name='{$section}[$id][$index][$key][payload][]' value='$button_url'/>";
                            $html     .= "</div>";
                            $html     .= "<div class='input-group-list group-list-filter payload phone_number d-none'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> Phone Number</label>";                       
                                $html     .= "<input type='text' class='$size-text clonable-increment' id='{$section}[$id][$key][\"payload\"]' name='{$section}[$id][$index][$key][payload][]' value='$button_payload'/>";
                            $html     .= "</div>";
                            $html     .= "<div class='input-group-list group-list-filter payload postback d-none'>";
                                $html     .= "<label class='clonable-increment-for clonable-increment-html'> PostBack keyword </label>";                       
                                $html     .= "<select name='{$section}[$id][$index][$key][payload][]' class='clonable-increment' data-value='$button_payload'>";
                                if( isset($hearings['keyword']) ){
                                    foreach( $hearings['keyword'] as $key_index => $keyword ){
                                        $html     .= "<option value='$keyword'>$keyword</option>";
                                    }
                                }                            
                                $html     .= "</select>";   
    
    
                            $html     .= "</div>";
    
                        $html     .= "</div>";                    
                        $html       .= '<button type="button" class="btn button-cancel clonable-button-close">Delete Button</button>';
                        $html       .= "</div>";
                        $buttonDecrement--;
                    }

                    $html       .= '<button class="clonable-button-add button-primary" type="button">Add New Button</button>';
                    $html       .= "</div>";
                }else{
                    // $html    .= sprintf( 
                    //             '<div class="input-group">
                    //             <input type="%1$s" class="%2$s-text clonable-increment-name" id="%3$s[%4$s][%5$s]" name="%3$s[%4$s][0][%5$s]" value="%6$s"%7$s/></div>', 
                    //             $type, $size, $args['section'], $id,$key, $val, $placeholder 
                    //         );
                    $html    .= "<div class='input-group'><input type='$type' class='$size-text clonable-increment-name' id='' name='{$args['section']}[$id][$index][$key]' value='$val'/></div>";
                }
            }
            $html .= '<button type="button" class="btn button-cancel clonable-button-close">Delete List</button>';
            $html .= '</div>';
            if( $index >= ( count($options) - 1 ))
                $html .= '<button class="clonable-button-add button-primary"" type="button">Add New List</button>';
            
            $indexDecrement--;
        }
        $html .= '</fieldset>';
    

        echo $html;
}
function callback_persistent_menu($args){
        $hearings           = bot_get_option('listen','hearing');
        $list_items         = get_option('facebook');
        $menu_buttons       = $list_items['menu'] ?? array();
        
        $value              = bot_get_option( $args['id'], $args['section'], $args['std'] );
        $options            = $args['options'];
        if( count($menu_buttons) > 0 ) $options = $menu_buttons;        
        
        // print_r($value);
        // print_r($options);
        $indexDecrement = 10;
        $type =  "text";
        $size =  "regular";
        $placeholder =  "";
        $id = $args['id'];
        $section = $args['section'];
        $html = "";
        $html     .= "<div class='clonable-block button-clone'  data-toggle='cloner' data-options='{\"clearValueOnClone\":false,\"incrementName\":\"button-increment\"}'>";
        $buttonDecrement = 3;
        $b_index        = 0;
        $buttons = $value;
        if( isset($buttons['name']) && is_array($buttons['name']) ) $buttons = $val['name'];
        foreach($options as $index => $button){
            $button_name        = $button['title'] ?? "";
            $button_type        = $button['type'] ?? "web_url";
            $button_url         = $button['url'] ?? home_url();
            $button_payload     = $button['payload'] ?? "";

            if( isset($val['name'])  ){
                $button_name        = $val['name'][$index] ?? "";
                $button_type        = $val['type'][$index] ?? "web_url";
                $button_url         = $val['url'][$index] ?? home_url();
                $button_payload     = $val['payload'][$index] ?? "";
            }
            // if($button_type == 'url') $button_type = 'web_url';
            // if($button_name == 'url') $button_type = 'web_url';
            $html     .= "<div class='clonable clonable-clone-number-decrement' data-clone-number='$buttonDecrement'>";
            // $html     .= "$b_index $button_name $button";
            
            $html     .= "<label class='button-increment-for button-increment-html'> Menu title 1 </label>";
            $html     .= "<div class='input-group'>";
            $html     .= "<input type='text' class='$size-text button-increment-name button-increment-id' id='{$section}[$id]' name='{$section}[$id][$b_index][title]' value='$button_name'/>";
            $html     .= "</div>";
            $html     .= "<div class='input-group'>";
                $html     .= "<div class='input-group-list'>";
                
                $html     .= "<label class='button-increment-for button-increment-html'> Type 1 </label>";
                    // $html     .= "<label class='clonable-increment-for'> Type 1 </label>";
                    $html     .= "<select class='list-button-type button-increment-name ' name='{$section}[$id][$b_index][type]' data-value='$button_type'>";
                    $html     .= "<option value='web_url'>URL</option>";
                    $html     .= "<option value='postback'>Post Back</option>";
                    // $html     .= "<option value='phone_number'>Phone Number</option>";
                    $html     .= "</select>";                            
                $html     .= "</div>";

                $html     .= "<div class='input-group-list group-list-filter web_url'>";
                    $html     .= "<label class='clonable-increment-for clonable-increment-html'> URL</label>";                       
                    $html     .= "<input type='text' class='$size-text button-increment-name' id='{$section}[$id][\"url\"]' name='{$section}[$id][$b_index][url]' value='$button_url'/>";
                    $html     .= "<input type='hidden' class='clonable-increment' id='{$section}[$id][\"webview_height_ratio\"]' name='{$section}[$id][$b_index][webview_height_ratio]' value='full'/>";
                $html     .= "</div>";

                $html     .= "<div class='input-group-list group-list-filter payload phone_number d-none'>";
                    $html     .= "<label class='clonable-increment-for clonable-increment-html'> Phone Number</label>";                       
                    $html     .= "<input type='text' class='$size-text button-increment-name button-increment-id' id='{$section}[$id][\"payload\"]' name='{$section}[$id][$b_index][payload]' value='$button_payload'/>";
                $html     .= "</div>";

                $html     .= "<div class='input-group-list group-list-filter payload postback d-none'>";
                    $html     .= "<label class='clonable-increment-for clonable-increment-html'> PostBack keyword </label>";                       
                    $html     .= "<select name='{$section}[$id][$b_index][payload]' class='button-increment-name button-increment-id' data-value='$button_payload'>";
                    $html     .= "<option value='GET_START'>Get Started</option>";
                    if( isset($hearings['keyword']) ){
                        foreach( $hearings['keyword'] as $key_index => $keyword ){
                            $html     .= "<option value='$keyword'>$keyword</option>";
                        }
                    }                            
                    $html     .= "</select>";   


                $html     .= "</div>";

            $html     .= "</div>";                    
            $html       .= '<button type="button" class="btn button-cancel '.$id.' clonable-button-close">Delete Button</button>';
            $html       .= "</div>";
            $buttonDecrement--;
            $b_index++;
        }

        $html       .= '<button class="clonable-button-add button-primary" type="button">Add New Button</button>';
        $html       .= "</div>";


        echo $html;
}
function callback_set_persistent_menu($args){
   $html = "";
   $html .= "<div class='save_persistmenu'> <button class='save button' id='save_persist_menu'> Save Menu On Bot</button> <div class='save_result'></div> </div>";
   echo $html;
}

function nhs_get_wp_editor( $content = '', $editor_id, $options = array() ) {
    ob_start();
 
    wp_editor( $content, $editor_id, $options );
 
    $temp = ob_get_clean();
    // $temp .= \_WP_Editors::enqueue_scripts();
    // $temp .= print_footer_scripts();
    // $temp .= \_WP_Editors::editor_js();
 
    return $temp;
}

function get_default_items(){
     $sliders   = [
        [
            'title'     => "ဘာစာအုပ်တွေမှာလို့ရလဲ? ",
            'subtitle'  => "မှာလို့ရနိုင်တဲ့စာအုပ်တွေ...",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "မှာလို့ရနိုင်တဲ့စာအုပ်တွေ...",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "can_order_books",
                ],
                [
                    'name'      => "website ကို သွားမယ်... ",
                    'type'      => "url",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "",
                ],
                [
                    'name'      => "App ကို Download လုပ်ထားမယ် ...",
                    'type'      => "url",
                    'url'       => "https://play.google.com/store/apps/details?id=com.aerialyangon.aerial_yangon",
                    'payload'   => "",
                ]
            ],
        ],
        [
            'title'     => "စာအုပ်ဘယ်လိုမှာရမလဲ?",
            'subtitle'  => "စာအုပ်မှာယူဖို့အတွက်...",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "စာအုပ်မှာယူဖို့အတွက် ...",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "for_order_books",
                ],
                [
                    'name'      => "website ကို သွားမယ်... ",
                    'type'      => "url",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "",
                ],
                [
                    'name'      => "ဖုန်းခေါ်မယ် ... ",
                    'type'      => "phone_number",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "09889003011",
                ]
            ],
        ],
        [
            'title'     => "စာအုပ်မှာရတာလွယ်လား?",
            'subtitle'  => "စာအုပ်မှာယူနည်း ... ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => " စာအုပ်မှာယူနည်...",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "how_order_books",
                ],
                
            ],
        ],
        [
            'title'     => "ဝန်ဆောင်မှုတွေက?",
            'subtitle'  => "",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => " ပန်းဆက်လမ်း ဝန်ဆောင်မှုများ ...",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                [
                    'name'      => " ဝန်ဆောင်မှုအားလုံး",
                    'type'      => "url",
                    'url'       => "https://www.pannsattlann.com/%e1%81%80%e1%80%94%e1%80%b9%e1%80%b1%e1%80%86%e1%80%ac%e1%80%84%e1%80%b9%e1%80%99%e1%82%88%e1%80%99%e1%80%ba%e1%80%ac%e1%80%b8/",
                    'payload'   => "",
                ],
                [
                    'name'      => "မှာယူဖူးတွေရဲ့ Review များ",
                    'type'      => "url",
                    'url'       => "https://www.pannsattlann.com/%e1%81%80%e1%80%94%e1%80%b9%e1%80%b1%e1%80%86%e1%80%ac%e1%80%84%e1%80%b9%e1%80%99%e1%82%88%e1%80%99%e1%80%ba%e1%80%ac%e1%80%b8/",
                    'payload'   => "",
                ],
                
            ],
        ],
        [
            'title'     => "ငွေကြိုလွှဲပေးချေလို့ရလား?",
            'subtitle'  => " ငွေပေးချေမှုစနစ်များ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "  ငွေပေးချေမှုစနစ်များ",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                [
                    'name'      => " ငွေကြိုလွှဲနိုင်သည့်ပေးချေမှုပုံစံများ ...",
                    'type'      => "url",
                    'url'       => "https://www.pannsattlann.com/%e1%81%80%e1%80%94%e1%80%b9%e1%80%b1%e1%80%86%e1%80%ac%e1%80%84%e1%80%b9%e1%80%99%e1%82%88%e1%80%99%e1%80%ba%e1%80%ac%e1%80%b8/",
                    'payload'   => "",
                ],
                 
                
            ],
        ],
        [
            'title'     => "စာအုပ်ဖတ်ညွှန်းများဖတ်မယ်",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => " စာအုပ်ဖတ်ညွှန်းများဖတ်မယ်",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                 
                 
                
            ],
        ],
        [
            'title'     => " အရောင်းရဆုံးစာအုပ်များ ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "  အရောင်းရဆုံးစာအုပ်များ ",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                 
                 
                
            ],
        ],
        [
            'title'     => " လစဉ်စာပြုံးပွဲတော် စာအုပ်များ ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "  လစဉ်စာပြုံးပွဲတော် စာအုပ်များ ",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                 
                 
                
            ],
        ],
        [
            'title'     => " အသစ်ထွက်စာအုပ်များ ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "  အသစ်ထွက်စာအုပ်များ ",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],
                 
                 
                
            ],
        ],
        [
            'title'     => " ပန်းဆက်လမ်းအကြောင် ",
            'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
            'button'    => [
                [
                    'name'      => "  ပန်းဆက်လမ်းအကြောင် ",
                    'type'      => "postback",
                    'url'       => "https://www.pannsattlann.com",
                    'payload'   => "psl_service",
                ],                
                 
                
            ],
        ],
    ];
    return $sliders;
}