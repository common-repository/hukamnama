=== Hukamnama ===
Contributors: inderpreet99
Donate link: https://inderpreetsingh.com/
Tags: hukamnama, sikh, sikhism, gurbani, gurmukhi, translation, shortcode
Requires at least: 4.0
Tested up to: 6.4.2
Stable tag: 0.5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display today's hukamnama using Sikher API on your Gurudwara site.

== Description ==

Display today's hukamnama using [Sikher API](http://api.sikher.com/) on your Gurudwara site.

= Permissions =

A ragi user role is created. Typically, this role will be assigned to a user account given to the local ragi jatha or granthi who can set the hukamnama through the WP Admin console. This allows us to limit the account of the user setting the hukamnama.

"Hukamnama" capability is created for easy permissions. It is default assigned to ragi, administrator, author and editor.

= Usage =

WordPress in itself makes a lot of sense to use on a Gurudwara website. This plugin can add a way to record hukamnama's on the site. You can either choose to make this public, or have it be shown on digital signage. At a local Gurudwara in Everett, [Gurudwara Sikh Sangat Boston](https://bostonsikhsangat.com), we use a [chromium browser on a Raspberry Pi](https://inderpreetsingh.com/2016/12/26/reload-problems-for-raspberry-pi-as-a-digital-signage-solution/) to show the hukamnama to the sangat. That would be the ideal way of using this hukamnama plugin.

= HTTPS =

The Sikher API doesn't support HTTPS yet [Issue #11](https://github.com/sikher/gurbanidb/issues/11). The plugin proxies requests to Sikher API through WP in the meantime.

= Tips =

* Use a page theme/template with extra space, since there is a lot of text.
* Ideally, use a responsive theme so that the text auto-scales for larger displays (such as digital signage) and mobile devices alike.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/hukamnama` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Add the `[hukamnama]` shortcode to a page.
1. Use the `WP Admin > Hukamnama > Settings` screen to configure. Save the page in the last step here to have the plugin target this page.
1. Set the hukamnama by randomly opening Sri Guru Granth Sahib Ji once a day, and noting the shabad and page in the `WP Admin > Hukamnama` screen.

== Frequently Asked Questions ==

= Can I contribute to this plugin? =

Submit a pull request at the [inderpreet99/hukamnama Bitbucket repo](https://bitbucket.com/inderpreet99/hukamnama).

= Problems? =

Report in Support ASAP.

== Screenshots ==

1. Frontend display of today's hukamnama
2. Set daily hukamnama (`WP Admin > Hukamnama`)
3. Change Hukamnama plugin settings (`WP Admin > Hukamnama > Settings`)

== Upgrade Notice ==

= 0.5 =
* Release plugin on WordPress.org.

== Changelog ==

= 0.5 =
* Release plugin on WordPress.org.
