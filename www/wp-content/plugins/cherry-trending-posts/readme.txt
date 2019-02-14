=== Cherry Trending Posts ===
Contributors: Zemez
Tags: posts, widget, popular, views, rating, comments, cherry framework
Requires at least: 4.5
Tested up to: 4.9.4
Stable tag: 1.0.1
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Adds rating and views count for posts and custom post types.


== Description ==

This plugin will allow you to track and display the most popular posts of your WordPress website based on the number of views, rating and the number of comments; and thus increase its view-per-visitor rate.

The plugin comprises three main components:

* __Post Views Counter__ – displays the number of page views for each post;
* __Post Rating__ – displays the number of votes and the average rating using a visual five-star system;
* __Widget__ – shows the most popular posts.

== Installation ==

1. Upload cherry-trending-posts folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

And of course, you will find new 'Cherry Trending Posts' widget in 'Appearance - Widgets' menu in your WordPress admin panel.

== Frequently Asked Questions ==

= How to use? =

It is not enough to install and activate the plugin to make it work properly. You also need to add 2 actions to the template.

1. `do_action( 'cherry_trend_posts_display_rating' )` / `do_action( 'cherry_trend_posts_return_rating' )` - display / return HTML for ratings
2. `do_action( 'cherry_trend_posts_display_views' )` / `do_action( 'cherry_trend_posts_return_views' )` - display / return HTML for post views counter

For example, in the twentysixteen template you need to add the actions to the __template-parts/content-single.php__ file. In your template the actions can be stored in a different file.

== Screenshots ==

1. Widget settings

== Changelog ==

= 1.0.0 =

* Initial release

== Documentation ==

= Options =

You can place the list of your top posts on any page using the Widgets tab in your WordPress dashboard.
The Cherry Trending Posts widget itself is highly customizable: besides the basic settings such as specifying the widget title and setting the maximum displayed length of a post title you can:

* Filter the posts by their _views_, _comments_ or _rating_;
* Choose, whether to show the _most rated_ or the _highest rated_ posts;
* Show post from specific tags and categories;
* Display or hide certain metadata (post author, publishing date, rating, comments, etc.);
* Set the excerpt length (if displayed);
* Change the button text;
* And more.

= Widget =

You can add the posts to the pages with the help of widget which offers you the following settings.

1. __Title__ - Specify the widget title
2. __Title length__ in characters (0 -- hide, -1 -- full)  - Set title length
3. __Filter by__:
	* _Views_ - Filter posts by Views
	* _Rating_ - Filter posts by Rating
	* _Comments_ - Filter posts by Comments
4. __Select rating type__:
	* _Most Rated_ - Filter posts by Most Rated type
	* _Highest Rated_ - Filter posts by the highest rate
5. __Show from__:
	* _Category_ - Show posts from category
	* _Tag_ - Show posts by tags
6. __Number of post to show__ (Use -1 to show all posts) - Here you can define the number of posts to display
7. __Offset__ (ignored when `"posts_per_page" => -1` (show all posts) is used) - This property specifies the number of post to displace or pass over
8. __Excerpt length__ in words (0 -- hide, -1 -- all) - This property sets the number of words limit for excerpt
9. __Display meta__ - This feature adds metadata to the post:
	* _Date_
	* _Author_
	* _View_
	* _Rating_
	* _Comments_
	* _Category_
	* _Tag_
	* _Read More_
10. __Button text__ - Add text to the button

= Cache =

If any of the cache plugins is used on the website, for a proper operation of the post ratings counter, you need to add the following code to the __functions.php__ file:

`add_filter( 'cherry_trend_posts_cache_fix', '__return_true' );`