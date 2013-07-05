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

Once the plugin is enabled, you will need to navigate to the plugin settings. To do this, navigate to `Settings>CW Search` for the settings page. In the settings page, you are able to specify the page which will contain the search results from the `Wiki Search` and you are also able to specify the Wiki that you wish to draw content from.

1.  Wiki Search

2.  Wiki Tags
To use the Wiki Tags feature, you will need to enter the shortcode into a page/post. For example

    [cws_tags]
        [cws_tag title="Albert Einstein" name="Einstein" size="5"]
    [/cws_tags]

will create a tag for the Wikipedia page for `Albert Einstein` with the tag name as `Einstein` and a tag size of 5. The two optional parameters in the shortcode are the `name` and `size` parameters. Leaving `name` blank will display the title as the tag name, while leaving the `size` parameter empty will make the tag have a default size of 1.

Changelog
---------

= 0.9 =
*   Pre-production release, please help us test it
