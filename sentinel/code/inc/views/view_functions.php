<?php

/** 
 * Get array with keys=>values for 'limit' and 'offset' given
 * an argument formatted like 'x,y' (where x and y are numeric).
 * NOTE! Imposes a hard limit on per-page results. 
 * returns FALSE if not properly formatted.
 * @param str $pair Limit,Offset pair
 * @return mixed Array OR FALSE
 */
function parsePaginationString( $pair=FALSE )
{
	if(!$pair) return FALSE;
	
	$hard_limit = 100; # prevent obvious abuse
	
	$e = explode(',', $pair);
	
	$ret = array();
	if(@is_numeric($e[0]) && @is_numeric($e[1])) {
	
		# Forces limit down if needed.
		if($e[0] > $hard_limit)
			$e[0] = $hard_limit;
	
		$ret['limit'] 	= $e[0];
		$ret['offset'] 	= $e[1];
		return $ret;
	} else return FALSE;	
}

function validatePaginationString( $pair='' )
{	
	$re = '/^\d+,\d+$/';
	
	if(preg_match($re, $pair) === 1)
		return TRUE;
	else return FALSE;
}



/** 
 * Generate generic pagination HTML
 * @param string $base_url This will be the entire value of href attributes, prior to the pagination pair.   
 * @param int $total_records
 * @param int $limit
 * @param int $offset
 * @return string
 */
function generatePaginationHTML($base_url, $total_records, $limit, $offset)
{
	if($total_records < 1)  return "";
	
	$max_pages = 25;
	$ret = array();
	$ret[] = "<div class=\"pagination\">Page ";
	
	# Force limit to be at least 1
	$limit = (int) $limit;
	if($limit < 1) 
		$limit = 1;

	$perpage 		= $limit;
	$pages 			= ceil( (int) $total_records / $perpage );	
	
	if($pages < 2) return "";
	
	for($i=0;$i<$pages;$i++) {
		$link_offset = $limit * $i;
		$page_num = $i+1;

		# label the currently-viewed page's link with a CSS class
		if($offset == $link_offset) 
			$css_class = 'pagination_current_page';
		else $css_class = '';

		# break out of this if there are a crazy amount of pages
		if($i >= $max_pages) {
			$ret[] = "<span>...</span>";
			break;
		}
		else {
			$ret[] = "<span class=\"{$css_class}\">"
									."<a href=\"{$base_url}?pg={$limit},{$link_offset}\">"
									."{$page_num}</a></span>";
		}
	}
	
	if($total_records > 0)
		$ret[] = "<div class=\"pagination_total\">{$total_records} results found</div>";
	
	$ret[] = "</div>";
	return implode("\n", $ret);
}



/**
 * Parse a template file
 * Given a template file and an associative array of values, return 
 * the template with the values replacing matching "template vars".
 * Within the template files, the template vars should look like {{this}}.
 * @param string $template_path Path to the template file
 * @param array $template_vars Name=>value array of template "vars"
 * @return string
 */
function parseTemplate($template, $template_vars, $template_is_path=TRUE)
{	
	if($template_is_path)
		$template_contents = file_get_contents($template);
	else $template_contents = $template;
	
	# Replace all defined template vars...
	foreach($template_vars as $name=>$value) {
		$template_contents = str_replace('{{'.$name.'}}', $value, $template_contents);
	}
	# Remove any remaining template vars before returning the parsed value.
	$template_contents = preg_replace(	"/({{[^}]*}})/", 
																			"", 
																			$template_contents);	
	return $template_contents;
}

/** 	
	Redirect to error page.
	Useful when a URL is bad, etc. It's a header redirect, so it will fail if you've
	output anything to the browser.
	*/
function error_redirect()
{
	header("HTTP/1.0 404 Not Found");
	echo parseTemplate(VIEW_CONTENT_PATH.'404_not_found.html', array());
}

/** 	
	Redirect to "invalid viewmode" page
	Example/intended usage: logged in user attempts to access a page she should be able to,
	but currently has insufficient viewmode.
	*/
function viewmode_redirect()
{
	header("HTTP/1.0 401 Unauthorized");
	echo parseTemplate(VIEW_CONTENT_PATH.'401_not_authorized.html', array());
}



/** 
 Spit out the valuesin an array or object (formatted with pre).
 Saves a little coding. 
 !!!! ONLY FOR DEVELOPMENT AND TESTING !!!!!
 */
function dumpit($v)
{
	# If $v is an object, replace it with an array of its properties.
	if(is_object($v)) {
		$v = get_object_vars($v);
	}
	echo "<hr /><pre>"; var_dump($v); "</pre><hr />";
}


?>