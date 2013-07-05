Combined Wiki Search
================================

The Combined Wiki Search is a WordPress plugin that is intended to be used in conjunction with the [Wiki Embed](https://github.com/ubc/wiki-embed) plugin.

There are two features in the Combined Wiki Search:

1.  Wiki Search
2.  Wiki Tags

The featres can be used by entering the appropriate shortcodes and their properties into a new or existing page/post. Please see the section on Usage

Installation
------------

1. Download the plugin
2. Extract the folder into your wp-content/plugins folder
3. Enable the plugin

Usage
-----

Once the plugin is enabled, you will need to navigate to the plugin settings. To do this, navigate to `Settings > CW Search` for the settings page. In the settings page, you are able to specify the page which will contain the search results from the `Wiki Search` and you are also able to specify the Wiki that you wish to draw content from.

*	Please note that with Wikipedia, the URL that should be entered into the URL field is `YOUR_LANGUAGE_HERE.wikipedia.org/wiki/`

1.  Wiki Search

2.  Wiki Tags

To use the Wiki Tags feature, you will need to enter the shortcode into a page/post. For example

    [cws_tags]
      [cws_tag title="Albert Einstein" name="Einstein" size="5" color="blue" ]
    [/cws_tags]

will create a tag for the Wikipedia page for `Albert Einstein` with the tag name as `Einstein`, a tag size of 5, and a black colored tag. The optional parameters in the shortcode are the `name`, `size`, and `color` parameters. Leaving `name` blank will display the title as the tag name, leaving the `size` parameter empty will make the tag have a default size of 1, and leaving `color` empty will make the tag color black.

*   Please note that there are only sizes from 0 to 12, where 0 is 12px and 12 is 38px.

Changelog
---------

= 0.9 =
*   Pre-production release, please help us test it
