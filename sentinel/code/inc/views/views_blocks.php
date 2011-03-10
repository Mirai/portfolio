<?php

/**
 * Block functions in this file can be called within view functions to return
 * a "block" as a string. That string would then be included as part of a template
 * var.
 * 
 * As these utilize the $page var, they are only to be used in view-based pages.
 *
 * NOTE: Block files must be HTML only, though they may contain template vars (like {{myvar}}).
 * 				All PHP must be executed here. Block template files must not be called/included directly
 *				except by functions in this file.
 *
 * To create a block, create a function below called "<something>_block()". 
 * If you want lots of markup in it, create a .html file in the blocks directory
 * and use the parseTemplate() function to manage content.
 *
 * Doing it this way limits how many functions call global variables and reduces
 * the knowledge needed to include a block (you don't need to manually handle all
 * the vars each block needs within each view that calls it).
 */

/* EXAMPLE ONLY! 
		todo: remove this sycamore function */
function block_event_month_selector($page, $prepop='')
{
	$template = dirname(__FILE__).'/../blocks/event_month_selector.html';
	
	# Build array of months with YYYYMM as key (one year starting from ~ last month)	
	$months = array();
	for($i=-1; $i<13; $i++) {
		$t 							= mktime(0,0,0,date("n")+$i,0,date("Y"));
		$key 						= date("Ym", $t);
		$months[$key] 	= date("Y M", $t);
	}
	# Create options
	$opts = "";
	foreach($months as $k=>$v) {
		$opts .= "<option value=\"{$k}\" ";
		if($prepop == $k) $opts .= " SELECTED ";
		$opts .= " >{$v}</option>\n";
	}	
	
	$t = array(); # template vars
	$t['options'] = $opts;
	if(!empty($prepop))
		$t['text_about_current'] = "Currently viewing ".date("F Y", strtotime($prepop.'01'));
	
	return parseTemplate($template, $t);
}

/* EXAMPLE ONLY! 
		todo: remove this sycamore function */
function block_header_image($page, $src)
{
	$template = dirname(__FILE__).'/../blocks/header_image.html';	
	$t = array(); # template vars	
	$t['src'] = $src;
	return parseTemplate($template, $t);
}


/* EXAMPLE ONLY! 
		todo: remove this sycamore function */
function block_noticebox($page)
{
	$template = dirname(__FILE__).'/../blocks/noticebox.html';	
	$t = array(); # template vars		
	$t['event'] = sycamoreContent::getContent('main_featured_event');	
	return parseTemplate($template, $t);
}



?>