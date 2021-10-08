<?php 

// get_header();

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;


use BotMan\Drivers\Facebook\Extensions\User;
use BotMan\Drivers\Facebook\Events\MessagingReads;
use BotMan\Drivers\Facebook\Events\MessagingOptins;
use BotMan\Drivers\Facebook\Extensions\ListTemplate;
use BotMan\Drivers\Facebook\Extensions\MediaTemplate;
use BotMan\Drivers\Facebook\Extensions\MediaUrlElement;
use BotMan\Drivers\Facebook\Events\MessagingReferrals;
use BotMan\Drivers\Facebook\Extensions\MediaAttachmentElement;
use BotMan\Drivers\Facebook\Extensions\ButtonTemplate;
use BotMan\Drivers\Facebook\Events\MessagingDeliveries;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use BotMan\Drivers\Facebook\Extensions\ReceiptTemplate;
use BotMan\Drivers\Facebook\Extensions\ReceiptElement;
use BotMan\Drivers\Facebook\Extensions\ReceiptAddress;
use BotMan\Drivers\Facebook\Extensions\ReceiptSummary;
use BotMan\Drivers\Facebook\Extensions\ReceiptAdjustment;
use BotMan\Drivers\Facebook\Exceptions\FacebookException;
use BotMan\Drivers\Facebook\Extensions\OpenGraphTemplate;
use BotMan\Drivers\Facebook\Extensions\OpenGraphElement;
use BotMan\Drivers\Facebook\Extensions\AirlineUpdateTemplate;
use BotMan\Drivers\Facebook\Extensions\AirlineCheckInTemplate;
use BotMan\Drivers\Facebook\Extensions\AirlineItineraryTemplate;
use BotMan\Drivers\Facebook\Extensions\Airline\AirlineBoardingPass;

use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;


$fbBotConf      = get_option( 'facebook');
$get_started    = $fbBotConf['get_started'] ?? "Hello, You are Welcom from my chat";
$hearings       = bot_get_option('listen','hearing');
$list_items     = get_option('list_items');
$list_items     = $list_items['lists'] ?? array();
$fbBotConf      = [
     'facebook' => $fbBotConf,
//      'facebook' => [
//     //   'token' => 'EAAFf5Ksvx1IBAFlVjAgUfCspZBxcVA2agvcBEfvU43fnv6nLZBZChOHgDn7lSCJbkIfUZCFhmUTPgztxeVd8IL4O9UsUZAZAR2hX0eHshsLY6IFFUtqXQkjiYOhxwZAshKpwFX4WWkmgUZBO5dmlKPjVpWdYOVTEtZAxxtpvufDfuGmLMyd5ywWhwT7DnwcjpshwZD',
//       'token' => 'EAAFf5Ksvx1IBACtDSvT6QBxkY0ElDpNzyru6ZBpCAv8C43uOMKMN5Sk1DYIQItOhLzVLZCCk9chrHvwNjiWWkj0nSRzH135ru79ZAlFAjKfob3Xl3cv6iqWmHVZCKKZBYer5o2SVfuarR5jwklM7CqjLsrUCLVLiKHLuB99wRMPDJIzJrM8R31RmLC9mogXQZD',
//       'app_secret' => '412527d361ed3b259cf6eaa1a96c540d',
//       'verification'=>'nayhtetsoe',
//   ]
];

// Load the driver(s) you want to use
DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);

// Create an instance
$botman = BotManFactory::create($fbBotConf);
 
// Give the bot something to listen for.
$request  = $_REQUEST;


$botman->hears('GET_STARTED|Get Started|GET_START', function ($bot) use ($get_started){
    // $user          = $bot->getUser();
    // $firstname     = $user->getUsername();
    // $text          = str_replace("{first_name}",$firstname, $get_started);
    $text          = str_replace("{first_name}","", $get_started);

    // $bot->reply('helloe');
    $bot->reply($text);
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
    $bot->typesAndWaits(2);
    // $bot->say("helo");
    $bot->reply(GenericTemplate::create()
        ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
        ->addElements(array_values($elements))
    );

});
 

foreach($hearings['keyword']  as $index =>  $keyword){
    $reply          = $hearings['textarea'][$index];
    $show_button    = $hearings['show_button'][$index];
    $buttons        = $hearings['button'][$index] ?? 0;
    if( $show_button && isset($buttons['name'])){
        $reply      = ButtonTemplate::create($reply);
        foreach($buttons['name'] as $key => $name){
            $type       = $buttons['type'][$key] ?? '';
            $payload    = $buttons['payload'][$key] ?? '';
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
    $botman->hears($keyword, function (BotMan $bot) use( $reply ) {     
        // $bot->typesAndWaits(1);
        $bot->typesAndWaits(2);
        $bot->reply($reply);
    });
}

/**
 * Start for Developer Testing
 */


$botman->hears('nhstellmemore', function (BotMan $bot) {
    $bot->reply(ButtonTemplate::create('👨... မင်္ဂလာပါ .... ပန်းဆက်လမ်း website/ App မှ စာအုပ်ဝယ်ရတာအရမ်းလွယ်ကူပါတယ်။ နှစ်သက်ရာစာအုပ်ကို စိတ်တိုင်းကျမွှေနှောက်ကြည့်ရှုပြီး ရွေးချယ်ဝယ်ယူနိုင်ကာ အမှာမှတ်တမ်းတွေလည်း စနစ်တကျရှိစေမှာဖြစ်ပါတယ်။ 

    အလွယ်ကူဆုံးဝယ်ယူနည်းကတော့... 
    ၁။ Website/App  သို့ဝင်ပါ 
    ၂။ နှစ်သက်ရာစာအုပ်များကို ရွေးပြီး စျေးဝယ်ခြင်းထဲသို့ထည့်ပါ
    ၃။ အမှာတင်မည်နှိပ်ကာ အမည်၊ ဖုန်းနံပါတ်၊ လိပ်စာ ဖြည့်သွင်းပြီး အမှာတင်လိုက်ရုံပါပဲ။ 
    (Account ဖွင့်မှာယူမယ်ဆိုရင်တော့ မှာယူခဲ့တဲ့စာအုပ်တွေနဲ့ ပို့ဆောင်မှုခြေရာခံစနစ်ကို အလွယ်တကူ ပြန်ကြည့်လို့ရမှာပါ)
    ◾️')
        ->addButton(ElementButton::create('Tell me more')
            ->type('postback')
            ->payload('nhstesting')
        )
        ->addButton(ElementButton::create('Show me the docs')
            ->url('http://botman.io/')
        )
    );
});
$botman->hears('nhstesting', function (BotMan $bot) {
    $list_items   = get_option('list_items');
    $list_items   = $list_items['lists'] ?? array();
    $bot->reply(ButtonTemplate::create('👨... မင်္ဂလာပါ .... ပန်းဆက်လမ်း website/ App မှ စာအုပ်ဝယ်ရတာအရမ်းလွယ်ကူပါတယ်။ နှစ်သက်ရာစာအုပ်ကို စိတ်တိုင်းကျမွှေနှောက်ကြည့်ရှုပြီး ရွေးချယ်ဝယ်ယူနိုင်ကာ အမှာမှတ်တမ်းတွေလည်း စနစ်တကျရှိစေမှာဖြစ်ပါတယ်။ 

    အလွယ်ကူဆုံးဝယ်ယူနည်းကတော့... 
    ၁။ Website/App  သို့ဝင်ပါ 
    ၂။ နှစ်သက်ရာစာအုပ်များကို ရွေးပြီး စျေးဝယ်ခြင်းထဲသို့ထည့်ပါ
    ၃။ အမှာတင်မည်နှိပ်ကာ အမည်၊ ဖုန်းနံပါတ်၊ လိပ်စာ ဖြည့်သွင်းပြီး အမှာတင်လိုက်ရုံပါပဲ။ 
    (Account ဖွင့်မှာယူမယ်ဆိုရင်တော့ မှာယူခဲ့တဲ့စာအုပ်တွေနဲ့ ပို့ဆောင်မှုခြေရာခံစနစ်ကို အလွယ်တကူ ပြန်ကြည့်လို့ရမှာပါ)
    ◾️')
        ->addButton(ElementButton::create('Tell me more')
            ->type('postback')
            ->payload('nhstellmemore')
        )
        ->addButton(ElementButton::create('Show me the docs')
            ->url('http://botman.io/')
        )
    );
});
$botman->hears('nhstesting2', function (BotMan $bot) {
    $bot->reply(GenericTemplate::create()
    ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
    ->addElements([
        Element::create('BotMan Documentation')
            ->subtitle('All about BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('http://botman.io')
            )
            ->addButton(ElementButton::create('tell me more')
                ->payload('nhstellmemore')
                ->type('postback')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        Element::create('BotMan Laravel Starter')
            ->subtitle('This is the best way to start with Laravel and BotMan')
            ->image('http://botman.io/img/botman-body.png')
            ->addButton(ElementButton::create('visit')
                ->url('https://github.com/mpociot/botman-laravel-starter')
            ),
        
        
    ])
    );
});
$botman->hears('nhsbutton', function (BotMan $bot) {
    $bot->reply(ButtonTemplate::create('Do you want to know more about BotMan?')
	->addButton(ElementButton::create('Tell me more')
	    ->type('postback')
	    ->payload('nhstellmemore')
	)
	->addButton(ElementButton::create('Show me the docs')
	    ->url('http://botman.io/')
	)
    );
});
$botman->hears('nhsimage', function (BotMan $bot) {
    $extras = $bot->getMessage(); 


    $bot->reply(GenericTemplate::create()
        ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
        ->addElements([
            Element::create('BotMan Documentation')
                ->subtitle('All about BotMan')
                ->image('http://botman.io/img/botman-body.png')
                ->addButton(ElementButton::create('visit')
                    ->url('http://botman.io')
                )
                ->addButton(ElementButton::create('tell me more')
                    ->payload('nhstellmemore')
                    ->type('postback')
                ),
            Element::create('BotMan Laravel Starter')
                ->subtitle('This is the best way to start with Laravel and BotMan')
                ->image('http://botman.io/img/botman-body.png')
                ->addButton(ElementButton::create('visit')
                    ->url('https://github.com/mpociot/botman-laravel-starter')
                ),
        ])
    );
    $bot->reply('image');
});
$botman->hears('nhsimage1', function (BotMan $bot) use ( $list_items ){
    $elements = [];
    if( !$list_items ) $bot->reply('no');
    foreach($list_items as $slider){

        $image = "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png";
        $url = " https://www.pannsattlann.com";
        $element = Element::create($slider['title']);
        $element->subtitle($slider['subtitle'])
            ->image($image);

            $buttons = $slider['button'];

            foreach($buttons['name']  as  $b_index => $name){

                $type       = $buttons['type'][$b_index];
                $payload    = $buttons['payload'][$b_index];

                if( $type  == 'url' ){
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
            // foreach($slider['button'] as $button){
            //     if( $button['type']  == 'url' ){
            //         $element->addButton(ElementButton::create($button['name'])
            //             ->url($payload)
            //         );

            //     }else{
            //         $element->addButton(ElementButton::create($button['name'])
            //             ->payload($button['payload'])
            //             ->type($button['type'])
            //         );

            //     }
            // }
        $elements[] = $element;
            

    }
    // $sliders   = [
    //     [
    //         'title'     => "ဘာစာအုပ်တွေမှာလို့ရလဲ? ",
    //         'subtitle'  => "မှာလို့ရနိုင်တဲ့စာအုပ်တွေ...",
    //         'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
    //         'button'    => [
    //             [
    //                 'name'      => "မှာလို့ရနိုင်တဲ့စာအုပ်တွေ...",
    //                 'type'      => "postback",
    //                 'url'       => "https://www.pannsattlann.com",
    //                 'payload'   => "can_order_books",
    //             ],
    //             [
    //                 'name'      => "website ကို သွားမယ်... ",
    //                 'type'      => "url",
    //                 'url'       => "https://www.pannsattlann.com",
    //                 'payload'   => "",
    //             ],
    //             [
    //                 'name'      => "App ကို Download လုပ်ထားမယ် ...",
    //                 'type'      => "url",
    //                 'url'       => "https://play.google.com/store/apps/details?id=com.aerialyangon.aerial_yangon",
    //                 'payload'   => "",
    //             ]
    //         ],
    //     ],
    //     [
    //         'title'     => "စာအုပ်ဘယ်လိုမှာရမလဲ?",
    //         'subtitle'  => "စာအုပ်မှာယူဖို့အတွက်...",
    //         'image'     => "https://www.pannsattlann.com/wp-content/uploads/2020/09/logo.png",
    //         'button'    => [
    //             [
    //                 'name'      => "စာအုပ်မှာယူဖို့အတွက် ...",
    //                 'type'      => "postback",
    //                 'url'       => "https://www.pannsattlann.com",
    //                 'payload'   => "for_order_books",
    //             ],
    //             [
    //                 'name'      => "website ကို သွားမယ်... ",
    //                 'type'      => "url",
    //                 'url'       => "https://www.pannsattlann.com",
    //                 'payload'   => "",
    //             ],
    //             [
    //                 'name'      => "ဖုန်းခေါ်မယ် ... ",
    //                 'type'      => "url",
    //                 'url'       => "https://www.pannsattlann.com",
    //                 'payload'   => "",
    //             ]
    //         ],
    //     ],
    // ];
    // $elements = [];
    // foreach($sliders as $slider){
    //     $element = Element::create($slider['title']);
    //         $element->subtitle($slider['subtitle'])
    //         ->image($slider['image']);

    //         foreach($slider['button'] as $button){
    //             if( $button['type']  == 'url' ){
    //                 $element->addButton(ElementButton::create($button['name'])
    //                     ->url($button['url'])
    //                 );

    //             }else{
    //                 $element->addButton(ElementButton::create($button['name'])
    //                     ->payload($button['payload'])
    //                     ->type($button['type'])
    //                 );

    //             }
    //         }
    //     $elements[] = $element;
            

    // }
    $bot->reply(GenericTemplate::create()
        ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
        ->addElements(array_values($elements))
    );
});
 



$botman->on('messaging_referrals', function($payload, $bot) {
    if( isset( $payload['referral'] ) ){
        $userID     = $payload['referral']['ref'];
        $user        = get_user_by( 'id', intval($userID) );
        if( $user->ID ){
            // $psid  = $payload['sender']['id'];
            // update_user_meta( $user->ID, 'psid', $psid );
            // update_user_meta( $user->ID, 'payload', $payload );
            // $text = "Hello {$user->display_name}, you will get notification with facebook messager for order details. Thanks";
            // // $bot->reply($text);
            // $bot->reply(
            //     ButtonTemplate::create($text)                
            //     ->addButton(ElementButton::create('Return To Site ?')
            //         ->url('https://beautymaxshop.com/my-account/fb-messenger-notification')
            //     )
            // );

            // $bot->reply(
            //     ButtonTemplate::create("Facebook Messenger Notification ?. Get Notification with facebook messenger for your order details.")                
            //     ->addButton(
            //         ElementButton::create('Tell me more')
            //         ->type('postback')
            //         ->payload('hello')                    
            //     )
            //     ->addButton(
            //         ElementButton::create('Tell me more 2')
            //         ->type('postback')
            //         ->payload('pyae')                    
            //     )
            // );
            // update_option( 'messaging_referrals', $payload );
        }
    }
});

$botman->on('messaging_optins', function($payload, $bot) {
    $bot->reply('messaging_optins yourself.');

});

/***
 * End for devloper Testing
 */
 


// Start listening
$botman->listen();
// get_footer();