=== LS Social Feed ===
Contributors: ladislav.soukup@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_name=Ladislav%20Soukup&item_number=LS%20Social%20Feed&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: Social Feed, Facebook, Twiter, Google+, aggregate, shortcode, template, full control, customize
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 0.6

Shortcodes to display social feeds from Facebook, Google+ and Twitter. You can also aggregate these social networks to one feed. Full template control!

== Description ==
This plugin will fetch feed from social network and display it using simple shortcode. There is also aggregated feed from several social networks.
You can build your own feed template using several template variables and shortcodes in admin panel.

Please note... This plugin is still "work in progress". I'm using it on several projects, but it is still not 100% ready.
Also, there is one "starting" template, but without CSS styles... You will need to customize it before use. On the other hand, you have full control over template a display style.

= shortcodes =
= aggregated =
[lssf facebook="DobryAndel" twitter="LadaSoukup" gplus="101145411178741720361" items=6]

= twitter =
[lssf_twitter id="LadaSoukup" items=4]

= facebook =
[lssf_facebook id="DobryAndel" items=2]

= Google+ =
[lssf_gplus id="101145411178741720361" items=2]


= Translatable =
All text can be translated using standart language files, text domain is: "ls_social_feed".

= Included translations: =
English, Czech

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Feed is now updated, you should check it
4. Now, please, open admin panel (Settings => LS Social Feed) and fill your Facebook App settings (App Id and App Secret), and your Google+ API key
5. Update feed template and custom CSS

== Screenshots ==

1. Plugin settings

== Changelog ==

= 0.6 =
- template tags tweaks 
- better data handling 
- getting ready for future functions 

= 0.5.6 =
template tag shortcode added - [isempty] 

= 0.5.5 =
renamed %%feed_url%% => %%item_url%%
addedd %%debug%%

= 0.5.4 =
truncate shortcode

= 0.5.3 =
template tags shortcode [isset]

= 0.5.2 =
editable template

= 0.5 =
first beta version

