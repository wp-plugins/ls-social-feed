=== LS Social Feed ===
Contributors: ladislav.soukup@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P6CKTGSXPFWKG&lc=CZ&item_name=Ladislav%20Soukup&item_number=LS%20Social%20Feed&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: Social Feed, Facebook, Twiter, Google+, aggregate, shortcode
Requires at least: 3.3.1
Tested up to: 3.5
Stable tag: 0.5.5

Shortcodes to display social feeds from Facebook, Google+ and Twitter. You can also aggregate these social networks to one feed.

== Description ==

! this is still work in progress and early beta !
In this version, several features are missing... but the code should be stable.

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
- English (default)

- Czech

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Feed is now updated, you should check it

No more settings are needed.

== Screenshots ==

1. Plugin settings

== Changelog ==

= 0.5.6 =
template tag shortcode added - [isempty] 

= 0.5.5 =
renamed %%fee_url%% => %%item_url%%
addedd %%debug%%

= 0.5.4 =
truncate shortcode

= 0.5.3 =
template tags shortcode [isset]

= 0.5.2 =
editable template

= 0.5 =
first beta version

