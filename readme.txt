=== Question Answer ===
	Contributors: pickplugins
	Donate link: http://pickplugins.com
	Tags:  Question Answer, Question, Answer
	Requires at least: 4.1
	Tested up to: 4.6
	Stable tag: 1.0.4
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	Create Awesome Question and Answer Website in a Minute

== Description ==

Built Question Answer site for your WordPress.

### Question Answer by http://pickplugins.com
* [Live Demo !&raquo; ](http://www.pickplugins.com/demo/question-answer/)
* [Documentation !&raquo; ](http://pickplugins.com/docs/documentation/question-answer/)


<strong>Plugin Features</strong>

* schema.org support.
* Archive page via shortcode.
* frontend question submission form via shortcode.
* Awesome account page via shortcode.






<strong>QA Account</strong>

`[qa_myaccount]` 


<strong>Question submission</strong>

`[qa_add_question]`


<strong>Question Archive</strong>

`[question_archive]`




<strong>Translation</strong>

Pluign is translation ready , please find the 'en.po' for default translation file under 'languages' folder and add your own translation. you can also contribute in translation, please contact us http://www.pickplugins.com/contact/


== Frequently Asked Questions ==

= Single question page showing 404 error , how to solve ? =

Pelase go "Settings > Permalink Settings" and save again to reset permalink.


= Single question page style broken, what should i do ? =

Please add follwoing action on your theme fucntions.php file , you need to edit container based on your theme
`
add_action('qa_action_before_single_question', 'qa_action_before_single_question', 10);
add_action('qa_action_after_single_question', 'qa_action_after_single_question', 10);

function qa_action_before_single_question() {
  echo '<div id="main" class="site-main">';
}

function qa_action_after_single_question() {
  echo '</div>';
}

`




== Installation ==

1. Install as regular WordPress plugin.<br />
2. Go your plugin setting via WordPress Dashboard and find "<strong>Question Answer</strong>" activate it.<br />


== Screenshots ==

1. Screenshot 1
2. Screenshot 2
3. Screenshot 3
4. Screenshot 4
5. Screenshot 5
6. Screenshot 6
7. Screenshot 7
8. Screenshot 8
9. Screenshot 9
10. Screenshot 10



== Changelog ==


	= 1.0.4 =
    * 28/08/2016 add - subscriber for questions.

	= 1.0.3 =
    * 28/08/2016 add - addons page.
	
	= 1.0.2 =
    * 27/08/2016 add - Bangla translation.

	= 1.0.1 =
    * 25/08/2016 add - Notifications.

	= 1.0.0 =
    * 10/08/2016 Initial release.
