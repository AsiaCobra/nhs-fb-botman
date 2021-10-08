<?php
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
function nhs_add_page_template ($templates) {
    $templates['botman-template.php'] = "Facebook Messenger Bot";
    return $templates;
    }
add_filter ('theme_page_templates', 'nhs_add_page_template');
function nhs_redirect_page_template ($template) {
    if ('botman-template.php' === get_page_template_slug())
        $template = NHSBOTMAN_PLUGIN_BASE   . '/botman-template.php';
    return $template;
    }
add_filter ('page_template', 'nhs_redirect_page_template');

add_action('wp_ajax_set_persistmenu','set_persistmenu');
function set_persistmenu(){
    $response = sent_fb_profile();
    wp_send_json($response);
}
// add_action('wp_footer','testFooter');
// add_action('admin_footer','testFooter');
function testFooter(){
    echo '<div style=" margin-left: 202px; display:none;">';
    $config = get_option( 'facebook');
    // output2(array_values($config['menu']));
    // print_r(get_option( 'elements'));
    // $data = get_profile_settings();
    // $data = sent_fb_profile();
    // output2(json_encode($data));
    // output2($data);
    // output2(get_fb_profile());
    // output2($data);
     $list_items     = get_option('list_items');
     output2($list_items);
     $list_items     = getListItems();
    output2($list_items);
    $hearings   = bot_get_option('listen','hearing');
    foreach($hearings['keyword']  as $index =>  $keyword){
        $reply          = $hearings['textarea'][$index];
        $show_button    = $hearings['show_button'][$index];
        $buttons        = $hearings['button'][$index] ?? 0;
        if( $show_button == 'on' && isset($buttons['name'])){
            // output2($buttons);
            // echo $show_button;
            $reply = ButtonTemplate::create($reply);
            foreach($buttons['name'] as $key => $name){
                $type = $buttons['type'][$key] ?? '';
                $payload = $buttons['payload'][$key] ?? '';
                if( $type == 'url' ){
                    $reply->addButton(ElementButton::create($name)
                        ->url($payload)
                    );
                }else{
                    $reply->addButton(ElementButton::create($name)
                        ->type($type)
                        ->payload($payload)
                    );
                }
            }
        }
    // $botman->hears($keyword, function (BotMan $bot) use( $reply ) {     
    //     // $bot->typesAndWaits(2);
    //     $bot->reply($reply);
    // });
    // output2($reply);
    }
    echo "</div>";
}

function get_fb_profile(){
    $config     = get_option('facebook');
    $page_token = $config['token'];
    $url        = "https://graph.facebook.com/v9.0/me/messenger_profile?fields=whitelisted_domains,greeting,persistent_menu&access_token=$page_token";
    $response   = wp_remote_get( $url,
    array(
        'timeout'     => 120,
        'httpversion' => '1.1',
    )
    );
     return json_decode(wp_remote_retrieve_body($response));
}
function sent_fb_profile($data=array()){
    $config     = get_option('facebook');
    $page_token = $config['token'];
    $url        = "https://graph.facebook.com/v9.0/me/messenger_profile?access_token=$page_token";
    // $url = "http://wpbot.nhs/botman/";
    $data       = get_profile_settings();    
    $response = wp_remote_post( $url,
        array(
            'body'     => wp_json_encode($data),
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
        )
    );
    return json_decode(wp_remote_retrieve_body($response));
}

function get_profile_settings(){
    $config = get_option('facebook');
    $data = array(
        'get_started'   => [
            'payload'   => "GET_STARTED",
        ],
        'whitelisted_domains' => [home_url(),'https://ngrok.io/'],
        'persistent_menu'       => [
            [
            'locale' => 'default',
            'composer_input_disabled' => 'false',
            'call_to_actions' => array_values($config['menu']),
            ]
        ]
    );
   
    return $data;
}
if( !function_exists('output2') ){
    function output2($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
}
function list_items(){
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
function getListItems(){
    $list_items     = get_option('list_items');
    $list_items     = $list_items['lists'] ?? array();

   
    $elements = [];
    foreach($list_items as $slider){
        $image  = $slider['image']; // "https://www.pannsattlann.com/wp-content/uploads/2020/12/Regular-Image-Recommended.png";
        // $url    = "https://www.pannsattlann.com";

        $element = Element::create($slider['title']);
            $element->subtitle($slider['subtitle'])
            ->image($image);

            $buttons = $slider['button'];

            foreach($buttons['name']  as  $b_index => $name){

                $type       = $buttons['type'][$b_index];
                $payload    = $buttons['payload'][$b_index];
                
                if( $type  == 'url' ){
                    $url        = $payload ?? "https://www.pannsattlann.com";
                    $element->addButton(ElementButton::create($name)
                        ->url($url)
                    );

                }else{
                    $element->addButton(ElementButton::create($name)
                        ->payload($payload)
                        ->type($type)
                    );

                }
            }
           
        $elements[] = $element;
            

    }
    return array_values($elements);
}