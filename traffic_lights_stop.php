<?php

/**
 * @package    Mapanese (https://github.com/danielrhodeswarp/PHP-debug-traffic-lights)
 * @copyright  Copyright (c) 2011 Warp Asylum Ltd (UK).
 * @license    see LICENCE file in source code root folder     New BSD License
 */

//Put this file somewhere and set it as your PHP dev server's "auto_append_file"

//----script's behaviour is this:
//give red, green or orange based only on SPEED
//with a link to any (X)HTML errors regardless of speed colour
//----

$time_taken = microtime(true) - $traffic_lights_start_microtime;
$clean_time = sprintf("%.04f", $time_taken);

$output = ob_get_clean();
echo $output;

//----------------------------
$lightbox_colour = '';	//the debug bubble
$lightbox_start = <<<HTML
<div id="tl_lightbox" style="z-index:10000; text-align:center; position:fixed; border:2px solid black; background-color:LIGHTBOXCOLOUR; opacity:0.75; -moz-border-radius:50%; -webkit-border-radius:50%; padding-top:1em; color:black; top:2%; left:88%; width:10%; height:10%;">
HTML;
$lightbox_stop = '</div>';
$lightbox_content = '';

//---------------------------
$errorbox_start = <<<HTML
<div id="tl_errorbox" style="z-index:1000; white-space:pre; overflow:auto; display:none; position:fixed; border:2px solid black; background-color:red; font-weight:bold; width:45%; height:45%; top:27%; left:27%;">
HTML;
$errorbox_stop = '</div>';
$errorbox_content = '';
$should_show_errorbox = false;

//Don't do anything for non-HTML files (ie. Ajax returns etc)
if(preg_match('/.*[<]html.*/i', $output))
{
	$tidy = new tidy();
	//$output = str_replace('<table', '<table summary=""', $output);
	$actual_type = 'HTML4';
	$error_type = 'HTML4';
	
	if(preg_match('/DTD XHTML 1.[0,1]/', $output))	//XHTML v1.0 or v1.1
	{
		$tidy->parseString($output, array('input-xml' => TRUE, 'show-errors' => 1000), 'utf8');//NOTE we assume utf8 HTML pages!
		$actual_type = 'XHTML';
		$error_type = 'XHTML';
	}
	
	elseif(preg_match('/[<][!]DOCTYPE html[>]/i', $output))	//HTML5
	{
		$actual_type = 'HTML5';
		$error_type = 'HTML4';	//no tidy lint for HTML5 (yet??)
		
		//$tidy->parseString($output, array('show-errors' => 1000), 'utf8');//NOTE we assume utf8 HTML pages!
	}
	
	else	//assume HTML4
	{
		$tidy->parseString($output, array('show-errors' => 1000), 'utf8');//NOTE we assume utf8 HTML pages!
	}
	
	$lightbox_content .= '<br/>' . $actual_type;
	
	if(tidy_warning_count($tidy) > 0 || tidy_error_count($tidy) > 0)
	{
		$lightbox_content .= "<br/><strong>Errors! <span style=\"cursor:pointer; color:blue; text-decoration:underline;\" onclick=\"var errorbox = document.getElementById('tl_errorbox'); if(errorbox.style.display == 'none'){errorbox.style.display = 'block';}else{errorbox.style.display = 'none';}\">[?]</span></strong>";
		
		$errorbox_content = "<em>{$error_type} errors!</em><br/>" . htmlspecialchars($tidy->errorBuffer);
		$errorbox_content .= "<br/><span style=\"color:blue; text-decoration:underline; cursor:pointer;\" onclick=\"window.open('view-source:' + document.location, 'sourcewin', 'width=640, height=480, scrollbars=yes, statusbar=yes');\">View source</span>";  //works in Chrome and Firefox
		
		$should_show_errorbox = true;
	}
	
	if($actual_type == 'HTML5')
	{
		$lightbox_content .= "<br/><em>can't check</em>";
	}
	
	$red_cutoff = 1;
	$orange_cutoff = 0.5;
	
	//All clear
    if($time_taken < $orange_cutoff /*and empty($lightbox_content)*/)
	{
		$lightbox_colour = 'green';
		
		$lightbox_content = "{$clean_time}s" . $lightbox_content;
	}

	//Orange
    elseif($time_taken < $red_cutoff /*and empty($lightbox_content)*/)
	{
		$lightbox_colour = 'orange';
		
		$lightbox_content = "{$clean_time}s" . $lightbox_content;
    }

	//Red
    else
	{
		$lightbox_colour = 'red';
		
		$lightbox_content = "{$clean_time}s" . $lightbox_content;
	}
	
	if(!empty($lightbox_content))
	{
		echo str_replace('LIGHTBOXCOLOUR', $lightbox_colour, $lightbox_start) . $lightbox_content . $lightbox_stop;
	}
	
	if($should_show_errorbox)
	{
		echo $errorbox_start . $errorbox_content . $errorbox_stop;
	}
}
