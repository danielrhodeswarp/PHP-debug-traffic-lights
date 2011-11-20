# PHP debug traffic lights

## What is it?

It's a couple of little PHP scripts that you can hook into your Zend Server / PHP's
"auto_prepend_file" and "auto_append_file" directives to get a debug bubble in the
top right of your browser for any HTML output that PHP generates on your development
server.

The debug bubble tells you how long it took PHP to generate the HTML page. Also:

* The debug bubble is green if your HTML page is created by PHP in less than 0.5 seconds
* It is orange if the time taken is more than 0.5 seconds but less than 1 second
* It is red if the HTML page took more than 1 second for PHP to create

As well as page generation speed, the generated markup is checked for correctness as per the
specified DOCTYPE. Tidy is used for this. Any markup errors present can be shown by clicking
on the [?] link in the debug bubble.

## What does it need to run?

* Zend Server / Apache with PHP
* Tidy wrapper extension for PHP


## What's hot?

* Very instant - and obvious - rating of your PHP script's speed performance
* In browser markup check and result report
* Works inobtrusively on any browser and doesn't need a toolbar etc


## What's not?

* Generated HTML is assumed to be in UTF-8 encoding
* HTML5 markup is not checked for correctness (because Tidy can't do this)
* Uses Tidy which, as a project, seems to be mostly dead in the water
* Reports only *generation* time of the HTML page which is only half the story (there is also how light and suitable the page is for transmission over the internet and etc etc)
* It uses ob_*() functions and so, if your PHP scripts are using those too, something will screw up!
* If your PHP scripts do a hard exit() then "auto_append_file" doesn't kick in and so there is no debug bubble
* Don't forget that your output HTML will have all the debug bubble stuff tagged on to the
end of it!

## Possible enhancements

* Automatic detection of character encoding (to then tell Tidy)
* I know that xDebug profiling information and etc can be plugged in to the debug bubble
(have done similar in the past but no longer use xDebug)
