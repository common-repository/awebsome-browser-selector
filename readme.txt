=== Awebsome! Browser Selector ===
Contributors: raulillana
Tags: awebsome, browser, selector, specific, CSS, UA
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: trunk
License: GPLv2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G442YSKBRKTJ2

Empower your CSS selectors! Write specific CSS code for each Platform/Browser/Version the right way.

== Description ==

Modifies the body tag classes adding some Platform/Browser/Version codes, so you can add quick and clean CSS patches.

= Inspired by =

* [PHP CSS Browser Selector](http://bastian-allgeier.de/css_browser_selector "PHP CSS Browser Selector") from [Bastian Allgeier](http://bastian-allgeier.de "Bastian Allgeier")
* [JS CSS Browser Selector](http://rafael.adm.br/css_browser_selector) from [Rafael Lima](http://rafael.adm.br "Rafael Lima")

= Based in =

* [PhpUserAgent](http://donatstudios.com/PHP-Parser-HTTP_USER_AGENT "PHP User Agent Parser") from [Jesse Donat](http://donatstudios.com "Jesse Donat").

= Available Platform Codes =

**Desktop**

*	**win** - Microsoft Windows
*	**lnx** - x11 and Linux distros
*	**mac** - MacOS
*   **cros** - ChromeOS

**Mobile**

*	**android** - Android
*	**iphone** - iPhone
*	**ipad** - iPad
*	**blackberry** - Blackberry
*	**winphone** - Windows Phone OS
*	**kindle** - Kindle
*	**kindlefire** - Kindle Fire

**Consoles**

*   **xbox** - Xbox 360
*   **ps3** - PlayStation 3
*   **wii** - Nintendo Wii

= Available Browser Codes =

*	**ie** - Internet Explorer
*	**iemobile** - IEMobile
*	**firefox** - Mozilla, Firefox
*	**camino** - Camino
*	**opera** - Opera
*	**safari** - Safari
*	**chrome** - Google Chrome
*	**kindle** - Kindle
*	**silk** - Silk
*	**lynx** - Lynx
*	**wget** - Wget
*	**curl** - Curl

== Installation ==

Go easy!  
Upload, activate and enjoy developing.

== Frequently Asked Questions ==

= Where should I write my CSS specific code? =

At the end of your theme CSS file will be fine. 

= How can I apply a patch for specific Platform/Browser/version? =

1. Filtering by Platform: `.kindle`
1. Filtering by Browser: `.opera`
1. Filtering by Browser and Version: `.ie.v7`
1. Filtering by Platform and Browser: `.win.ie`
1. Filtering by Platform, Browser and Version: `.win.ie.v8`

`.Platform.Browser.Version #id .class { display:block; }`

So, this way you can apply CSS3 patches seamlessly...

`/* fallback/image non-cover color & fallback image & W3C Markup */
#element { background-color: #1a82f7; background-image: url('images/fallback-gradient.png'); background-image: linear-gradient(to bottom, #FFFFFF 0%, #00A3EF 100%); }

/* Safari + Chrome + iPhone + iPad */
.safari #element,
.chrome #element,
.iphone #element,
.ipad #element { background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#2F2727), to(#1a82f7)); background-image: -webkit-linear-gradient(top, #2F2727, #1a82f7); }

/* Firefox */
.firefox #element { background-image: -moz-linear-gradient(top, #2F2727, #1a82f7); }

/* Opera */
.opera #element { background-image: -o-linear-gradient(top, #2F2727, #1a82f7); }

/* IE */
.ie #element { background-image: -ms-linear-gradient(top, #2F2727, #1a82f7); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2F2727', endColorstr='#1a82f7', gradientType='0'); }`

= Something's messing with my classes or they're not showing... =

Try clearing your caches.

== Screenshots ==

This plugins just adds some classes to your body tag, so screenshots not really needed. ;)

== Changelog ==

= Future Release =

* Codes managing UI.

= 2.2 =

* Updated PhpUserAgent.
* Added consoles platform styles.

= 2.1 =

* Fixed readme.txt.
* Fixed BuddyPress support.
* Removed webkit and gecko generic classes (use browser specific classes instead).

= 2.0 =

* Revamped code using WP API's and OOPHP.
* Revamped UA parsing code.
* Revamped docs.
* Removed all the cache messy stuff added on v1.1 (sorry [@jrevillini](http://profiles.wordpress.org/jrevillini/ "@jrevillini")!).

= 1.2.1 =

* Fixed working bug.

= 1.2 =

* Fixed some coding bugs.
* Added more code comments.

= 1.1 =

* Updated readme.txt.
* Fixed BuddyPress support (kudos [@landwire](http://profiles.wordpress.org/landwire/ "@landwire")!).
* Fixed caching bug for WP Super Cache and W3 Total Cache incompatibility (kudos [@jrevillini](http://profiles.wordpress.org/jrevillini/ "@jrevillini")!).

= 1.0.1 =

* Updated descriptions.

= 1.0 =

* Born with basic functionality and docs.

== Upgrade Notice ==

= 2.2 =

Updated parser class.
Added consoles platform styles.

= 2.1 =

Update required!!

= 2.0 =

Update required!!

= 1.2.1 =

Update required!!

= 1.2 =

Update required!!

= 1.1 =

* BuddyPress and cache plugin issues fixed. Update required!

= 1.0.1 =

* Updated descriptions. No Update required!