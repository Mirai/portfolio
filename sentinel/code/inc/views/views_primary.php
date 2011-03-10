<?php

/*----------------------------------------------------------------------------------
Primary View Functions for Sentinel Investments
------------------------------------------------------------------------------------
This file contains the primary view functions for the site. If additional views are
needed beyond what logically fits here, you can create another file (keep in same 
directory though).
-----------------------------------------------------------------------------------*/



/* EXAMPLE VIEW - USING STANDARD MULT COLS CONTENT TEMPLATE */
function example_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';
	$t_vars = array(); # template vars	
	
	switch(@$page[1])
	{
		case '2col':
			$t_vars['VIEW_CSS'] = 'twocol page-example';
			break;
		case '3col':
			$t_vars['VIEW_CSS'] = 'threecol page-example';
			break;
		case '1col':
		default:
			$t_vars['VIEW_CSS'] = 'page-example';
			break;
	}
	
	$t_vars['CONTENT_ABOVE'] 			= '<p>Testing (CONTENT_ABOVE)</p>';
	$t_vars['CONTENT_BELOW'] 			= '<p>Testing (CONTENT_BELOW)</p>';
	$t_vars['CONTENT_PRIMARY'] 		= file_get_contents(VIEW_CONTENT_PATH.'EXAMPLE_MAIN.html');	
	$t_vars['CONTENT_SECONDARY'] 	= file_get_contents(VIEW_CONTENT_PATH.'EXAMPLE_SIDEBAR.html');
	$t_vars['CONTENT_TERTIARY'] 	= '<p>Testing (CONTENT_TERTIARY)</p>';	
	
	return parseTemplate($t, $t_vars);
}

/* Index Page */
function index_view($page)
{
	global $sentinel;
	$template = VIEW_CONTENT_PATH.'homepage_public.html';
	
	$t = array(); # template vars
	
	# BUFFER OUTPUT FOR INCLUDES THAT ECHO ===========================
	ob_start();
	include(PAGE_CONTENT_PATH.'homepage_fundbanner.php');
	$t['COLUMN1'] = ob_get_contents(); ob_clean();
	include(PAGE_CONTENT_PATH.'homepage_news.php');
	$t['COLUMN2'] = ob_get_contents(); ob_clean();
	include(PAGE_CONTENT_PATH.'homepage_actions.php');
	$t['COLUMN3'] = ob_get_contents(); ob_clean();	
	ob_end_clean();
	# BUFFER OUTPUT FOR INCLUDES THAT ECHO ===========================

	return parseTemplate($template, $t);
}



function morningstar_detail_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_left 	= VIEW_CONTENT_PATH.'morningstar_detail_left.html';	
	$t_right 	= VIEW_CONTENT_PATH.'morningstar_detail_right.html';	
	$t_vars 					= array(); // template vars	
	$t_vars_left 			= array(); // template vars	
	$t_vars_right 		= array(); // template vars	
	
	$t_cross = BLOCK_TEMPLATES_PATH.'side_crosshair.html';
	$t_vars_cross = array();
		
	switch(@$page[2])
	{
		case 'hypothetical':
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Hypothetical Illustrator');
			$t_vars_right['HYPOTHETICAL_IS_ACTIVE'] = 'tools_active';
			$t_vars_left['IFRAME_URL'] = MORNINGSTAR_HYPO_URL;
			break;
		case 'screener':
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Screener');
			$t_vars_right['SCREENER_IS_ACTIVE'] = 'tools_active';
			$t_vars_left['IFRAME_URL'] = MORNINGSTAR_TOOL_URL.'SCR';
			break;
		case 'comparison':
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Fund Vs. Fund');
			$t_vars_right['COMPARISON_IS_ACTIVE'] = 'tools_active';
			$t_vars_left['IFRAME_URL'] = MORNINGSTAR_TOOL_URL.'FVF';
			break;
		case 'portfolio':
		default:
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Portfolio Analysis');
			$t_vars_right['PORTFOLIO_IS_ACTIVE'] = 'tools_active';
			$t_vars_left['IFRAME_URL'] = MORNINGSTAR_TOOL_URL.'PA';
			break;
	}
	
	$t_vars_right['HYPO_URL'] = MORNINGSTAR_HYPO_URL;
	
	$t_vars['VIEW_CSS'] = 'twocol page-morningstar-detail';
	
	$t_vars_cross['CROSS_HEADER'] = 'Contact';
	$t_vars_cross['CROSS_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'morningstar_detail_right_contact.html');
	
	$t_vars['CONTENT_PRIMARY'] = parseTemplate($t_left, $t_vars_left);
	$t_vars['CONTENT_SECONDARY'] = parseTemplate($t_right, $t_vars_right);
	$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}


function morningstar_tools_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_vars = array();
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Morningstar Tools');
	
	$t_vars['VIEW_CSS'] = 'page-morningstar-tools';
	
	$t_vars_tools = array();
	$t_vars_tools['HYPO_URL'] = MORNINGSTAR_HYPO_URL;
	
	$t_vars['CONTENT_PRIMARY'] = parseTemplate(VIEW_CONTENT_PATH.'morningstar_tools.html', $t_vars_tools);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}


function sustainable_investing_view($page)
{	
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';
	$t_vars['VIEW_CSS'] = 'twocol';
	$t_vars['CONTENT_PRIMARY'] = file_get_contents(VIEW_CONTENT_PATH.'sustainable_investing_left.html');
	$t_cross = BLOCK_TEMPLATES_PATH.'side_crosshair.html';
	$t_vars_cross = array();
	
	$t_vars_cross['CROSS_HEADER'] = 'The Essential Elements of Sustainable Investing';
	$t_vars_cross['CROSS_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'sustainable_investing_cross.html');
	
	$t_grey = BLOCK_TEMPLATES_PATH.'related_links.html';
	$t_vars_grey = array();
	
	$t_vars_grey['GREY_HEADER'] = 'Learn more about Sustainable Investing at Sentinel';
	$t_vars_grey['GREY_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'sustainable_investing_grey.html');
	
	$t_right 	= VIEW_CONTENT_PATH.'sustainable_investing_right.html';
	$t_vars_right 		= array(); // template vars	
	
	$t_vars['CONTENT_SECONDARY'] = parseTemplate($t_right, $t_vars_right);
	$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);
	$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_grey, $t_vars_grey);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
	//return file_get_contents(VIEW_CONTENT_PATH.'/sustainable_investing.html');
}

function social_screens_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';
	$t_vars['VIEW_CSS'] = 'twocol';
	$t_vars['CONTENT_PRIMARY'] = file_get_contents(VIEW_CONTENT_PATH.'social_screens_left.html');	
	
	$t_cross = BLOCK_TEMPLATES_PATH.'side_crosshair.html';
	$t_vars_cross = array();
	
	$t_vars_cross['CROSS_HEADER'] = 'Screening Criteria';
	$t_vars_cross['CROSS_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'social_screens_right.html');
	
	$t_vars['CONTENT_SECONDARY'] = parseTemplate($t_cross, $t_vars_cross);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}

function dst_vision_view($page)
{
	$t_vars_dst = array();
	$t_vars_dst['DST'] = EXTERNAL_URL_DSTVISION;
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | DST Vision');
	$t_vars['VIEW_CSS'] = 'dstvision-view';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate(VIEW_CONTENT_PATH.'dst_vision.html', $t_vars_dst);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';
	
	return parseTemplate($t, $t_vars);
}

function fan_web_view($page)
{
	$t_vars_fanweb = array();
	$t_vars_fanweb['fanweb'] = EXTERNAL_URL_FANWEB;
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | My Account');
	$t_vars['VIEW_CSS'] = 'fan-web-view';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate(VIEW_CONTENT_PATH.'fanweb.html', $t_vars_fanweb);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}

function fan_plan_view($page)
{
	$t_vars_fanplan = array();
	$t_vars_fanplan['fanplan'] = EXTERNAL_URL_FANPLAN;
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Fan Plan');
	$t_vars['VIEW_CSS'] = 'fan-plan-view';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate(VIEW_CONTENT_PATH.'fanplan.html', $t_vars_fanplan);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}

function proxy_voting_view($page)
{
	$t_vars_proxy = array();
	$t_vars_proxy['proxy'] = EXTERNAL_URL_PROXY;
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Proxy Voting Information');
	$t_vars['VIEW_CSS'] = 'proxy-voting-view';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate(VIEW_CONTENT_PATH.'proxyvoting.html', $t_vars_proxy);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}



function shareholder_view($page)
{	
	return file_get_contents(VIEW_CONTENT_PATH.'/shareholder_left.html');
}

/* ... */
function advisor_center_my_profile_view($page)
{
	global $auth;
	SentinelPage::setCurrentPageTitle('Advisor Center | My Profile');
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_vars['VIEW_CSS'] = 'twocol';
	
	# OB -------------------
	ob_start();
	include(PAGE_CONTENT_PATH.'advisor_center_my_profile.php');
	$t_vars['CONTENT_PRIMARY'] = ob_get_contents(); 
	ob_end_clean();
	# END OB ---------------

	return parseTemplate($t, $t_vars);
}

/* Index Page */
function advisor_center_home_view($page)
{
	global $sentinel;
	global $auth;
	SentinelPage::setCurrentPageTitle('Advisor Center');
	$template = VIEW_CONTENT_PATH.'homepage_advisor_center.html';
	
	$user = new SentinelUser($auth->getUsername());
	$wholesalers = SentinelUser::getUserWholesaler($user->user_zip);
		
	$t = array(); # template vars
	
	/** Defaults -- hide some stuff by default ... (clear below if needed) 
			You can use the CSS class "hide" to set visibility: none */
	$t['WHOLESALER_PICTURE1_CSS'] = 'hide';
	$t['WHOLESALER_PICTURE2_CSS'] = 'hide';
	$t['WHOLESALER_BLOCK1_CSS'] = 'hide';
	$t['WHOLESALER_BLOCK2_CSS'] = 'hide';	
		
	if(is_array($wholesalers)) {
		$wholesaler1 = new SentinelWholesaler($wholesalers[0]);
		
		if(SentinelWholesaler::isThumbnailAvailable($wholesaler1->wholesaler_id)) {
			$t['WHOLESALER_PICTURE1'] = SentinelWholesaler::getThumbnailHTML($wholesaler1->wholesaler_id);
			$t['WHOLESALER_PICTURE1_CSS'] = ''; # removing "hide" set above	
		}
		$t['WHOLESALER_BLOCK1_CSS'] = ''; # removing "hide" set above
		$t['WHOLESALER_NAME1'] = $wholesaler1->wholesaler_first." ".$wholesaler1->wholesaler_last;
		$t['WHOLESALER_TITLE1'] = $wholesaler1->wholesaler_title;
		$t['WHOLESALER_CONTACT1'] = str_replace('-', '.', $wholesaler1->wholesaler_phone).'&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/inc/email_wholesaler.php?to='.$wholesaler1->wholesaler_id.'" onClick="return popup(this, \'notes\')">email</a>';
		
		if(isset($wholesalers[1])) {
			$wholesaler2 = new SentinelWholesaler($wholesalers[1]);
		
			if(SentinelWholesaler::isThumbnailAvailable($wholesaler2->wholesaler_id)) {
				$t['WHOLESALER_PICTURE2'] = SentinelWholesaler::getThumbnailHTML($wholesaler2->wholesaler_id);
				$t['WHOLESALER_PICTURE2_CSS'] = ''; # removing "hide" set above
			}
			$t['WHOLESALER_BLOCK2_CSS'] = ''; # removing "hide" set above
			$t['WHOLESALER_NAME2'] = $wholesaler2->wholesaler_first." ".$wholesaler2->wholesaler_last;
			$t['WHOLESALER_TITLE2'] = $wholesaler2->wholesaler_title;
			$t['WHOLESALER_CONTACT2'] = str_replace('-', '.', $wholesaler2->wholesaler_phone).'&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/inc/email_wholesaler.php?to='.$wholesaler2->wholesaler_id.'" onClick="return popup(this, \'notes\')">email</a>';
		}
	} else { # GENERIC DISPLAY (WHEN USER HAS NO SPECIFIC WHOLESALERS)
		$t['WHOLESALER_PICTURE1'] = '';	
		$t['WHOLESALER_BLOCK1_CSS'] = ''; # removing "hide" set above
		$t['WHOLESALER_NAME1'] = 'National Sales Desk';
		$t['WHOLESALER_TITLE1'] = '800-233-4332';
		$t['WHOLESALER_CONTACT1'] = '<a href="/inc/email_wholesaler.php" onClick="return popup(this, \'notes\')">email</a>';
	}	

	$t['COLUMN1'] = file_get_contents(VIEW_CONTENT_PATH.'advisor_homepage_video.html');
	$t['COLUMN3'] = file_get_contents(VIEW_CONTENT_PATH.'advisor_homepage_action.html');	
	
	# BUFFER OUTPUT FOR INCLUDES THAT ECHO ===========================
	ob_start();
	include(PAGE_CONTENT_PATH.'advisorhomepage_news.php');
	$t['COLUMN2'] = ob_get_contents();
	ob_end_clean();
	# BUFFER OUTPUT FOR INCLUDES THAT ECHO ===========================

	return parseTemplate($template, $t);
}


function sales_insights_and_commentary_view($page)
{	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Insights and Commentary');
	
	$t = VIEW_TEMPLATES_PATH."content_three_col.html";
	$t_vars = array(); 
		
	# FOR DETAIL PAGES (INDIVIDUAL ARTICLE)
	# ---------------------------------------
	# --- FOR STATIC PAGES/FRIENDLY NAMES
	if(@!empty($page[2]) && @!is_numeric($page[2])) { 
		# $content_t = insights_and_commentary_CONTENT_PATH."insights_and_commentary_detail.html";
		
		# Shared vars/templates

		$t_vars['VIEW_CSS'] 	= 'page-insights-and-commentary-detail '; # append to only!
		$t_grey = BLOCK_TEMPLATES_PATH.'related_links.html';
		$t_vars_grey = array();		
		$t_vars_grey['GREY_HEADER'] = 'Related Materials';
		$t_cross = BLOCK_TEMPLATES_PATH.'side_crosshair.html';
		$t_vars_cross = array();		
		$t_vars_cross['CROSS_HEADER'] = 'Related Links';

		# Per-item vars/templates
		switch($page[2])
		{
			# "commentary"
			case 'slouching-toward-recovery':
				$t_vars['VIEW_CSS'] .= 'threecol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_slouching_links.html');				
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_slouching_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_slouching_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				$t_vars['CONTENT_TERTIARY'] = "<img src=\"/images/christian.jpg\" />";
				$t_vars['CONTENT_TERTIARY'] .= "<img src=\"/images/pullquote_slouching.gif\" />";
				$t_vars['CONTENT_ABOVE'] = "<h1>Slouching Toward Recovery, 05.01.09</h1>";
				$t_vars['CONTENT_BELOW'] = "<p>The CBOE Volatility Index&reg; (VIX&reg;) is a key measure of market expectations of near-term volatility conveyed by S&P 500 stock index option prices. It is considered by many to be a key indicator of investor sentiment and market volatility.</p>";
				$t_vars['CONTENT_BELOW'] .= "<div class=\"backLink\"><a href=\"/advisor-center/insights-and-commentary\"><img vspace=\"0\" hspace=\"0\" border=\"0\" src=\"/images/left_arrow_00a4e4.gif\"/> Return to Ideas & Insights</a></div>";
	
				break;
			case 'china-to-the-rescue':
				$t_vars['VIEW_CSS'] .= 'threecol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_china_links.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_china_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_china_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				$t_vars['CONTENT_TERTIARY'] = "<img src=\"/images/christian.jpg\" />";
				$t_vars['CONTENT_TERTIARY'] .= "<img src=\"/images/pullquote_china.gif\" />";
				$t_vars['CONTENT_ABOVE'] = "<h1>China to the rescue, 04.02.09</h1><h4>Beijing's stimulus package is far better than Washington's.</h4><h4>&nbsp;</h4>";
				$t_vars['CONTENT_BELOW'] = "<p><a name=\"1\"></a><sup>1</sup> \"Africa. Altered States, Ordinary Miracles\" Richard Dowden. Portobello Books, 2008</p>";
				$t_vars['CONTENT_BELOW'] .= "<p>The Standard & Poor's 500 Index is an unmanaged index considered representative of the U.S. stock market. An investment cannot be made directly in an index.</p>";
				$t_vars['CONTENT_BELOW'] .= "<div class=\"backLink\"><a href=\"/advisor-center/insights-and-commentary\"><img vspace=\"0\" hspace=\"0\" border=\"0\" src=\"/images/left_arrow_00a4e4.gif\"/> Return to Ideas & Insights</a></div>";
				break;
			case 'importance-of-being-global':
				$t_vars['VIEW_CSS'] .= 'threecol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_global_links.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_global_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_global_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				$t_vars['CONTENT_TERTIARY'] = "<img src=\"/images/kateschapiromarketinsights.jpg\" />";
				$t_vars['CONTENT_ABOVE'] = "<h1>The Importance of Being Global, 09.02.09</h1><h4>Economic rebalancing and why it matters to the 21st century investor</h4>";
				break;
			case 'for-what-its-worth':
				$t_vars['VIEW_CSS'] .= 'threecol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_worth_links.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_worth_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_worth_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				$t_vars['CONTENT_TERTIARY'] = "<img src=\"/images/brownlee.jpg\" />";
				$t_vars['CONTENT_ABOVE'] = "<h1>For what it's worth, 02.23.09</h1>";
				$t_vars['CONTENT_BELOW'] = "<div class=\"backLink\"><a href=\"/advisor-center/insights-and-commentary\"><img vspace=\"0\" hspace=\"0\" border=\"0\" src=\"/images/left_arrow_00a4e4.gif\"/> Return to Ideas & Insights</a></div>";
				break;
			case 'tread-lightly-keep-an-eye-on-earnings':
				$t_vars['VIEW_CSS'] .= 'threecol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_tread_links.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_tread_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(insights_and_commentary_CONTENT_PATH.'commentary_tread_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				$t_vars['CONTENT_TERTIARY'] = "<img src=\"/images/christian.jpg\" />";
				$t_vars['CONTENT_TERTIARY'] .= "<img src=\"/images/quotes/tread_detail.gif\" />";
				$t_vars['CONTENT_ABOVE'] = "<h1>Tread Lightly...Keep an Eye on Earnings, 06.29.09</h1>";
				$t_vars['CONTENT_BELOW'] = "<p><a name=\"1\"></a><sup>1</sup> The S&P 500 Index is an unmanaged index considered representative of the U.S. stock market.  
An investment cannot be made directly into an index.</p>";
				$t_vars['CONTENT_BELOW'] .= "<div class=\"backLink\"><a href=\"/advisor-center/insights-and-commentary\"><img vspace=\"0\" hspace=\"0\" border=\"0\" src=\"/images/left_arrow_00a4e4.gif\"/> Return to Ideas & Insights</a></div>";
				break;
			# "advisor insights"
			
			default: # no match
				SentinelPage::setCurrentPageTitle('Article not found');
				$t_vars['CONTENT_PRIMARY'] = "Article not found";
				break;
		}
	}
	# --- FOR NUMERIC RETRIEVAL (FROM DB)
	elseif(@!empty($page[2]))	{
		$t = insights_and_commentary_CONTENT_PATH."insights_and_commentary_detail.html";
		$t_vars['VIEW_CSS'] 	= 'page-insights-and-commentary-detail'; # append to only!
		$content_t_vars = array();
		
		# Retrieve insight object
		$insight = new SentinelNewsItem($page[2]);

		if(!$insight->id) { # insight not found
			SentinelPage::setCurrentPageTitle('Article not found');
			$content_t_vars['h2'] 				= 'Not Found';
			$content_t_vars['content'] 		= '';
		} else {
			SentinelPage::setCurrentPageTitle('Insights and Commentary | '.$insight->news_name);
			$content_t_vars['h2'] 				= $insight->news_name;
			$content_t_vars['content'] 		= $insight->news_full;
		}
		
		# Parse whatever set of vars were set above into the outer template content area
		$t_vars['CONTENT_PRIMARY'] = parseTemplate($content_t, $content_t_vars);			
		$t_vars['CONTENT_SECONDARY'] 	= '';
		$t_vars['CONTENT_TERTIARY'] 	= '';
	}
	# --- FOR INDEX (NOT AN INDIVIDUAL ITEM) 
	else {
		SentinelPage::setCurrentPageTitle('Insights and Commentary');
		$content_t = insights_and_commentary_CONTENT_PATH."insights_and_commentary_index.html";
		$content_t_vars = array();
		$content_t_vars['VIEW_CSS'] 	= 'page-insights-and-commentary-index';
		# Parse whatever set of vars were set above into the outer template content area
		$t_vars['CONTENT_PRIMARY'] = parseTemplate($content_t, $content_t_vars);			
		$t_vars['CONTENT_SECONDARY'] 	= '';
		$t_vars['CONTENT_TERTIARY'] 	= '';
	}	
	
	# RETURN FINAL PARSE (USED BY ALL CASES ABOVE)
	return parseTemplate($t, $t_vars);
}



function sales_tools_and_ideas_view($page)
{	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Sales Ideas & Insights');
	
	$t = VIEW_TEMPLATES_PATH."content_three_col.html";
	$t_vars = array(); 
		
	# FOR DETAIL PAGES (INDIVIDUAL ARTICLE)
	# ---------------------------------------
	# --- FOR STATIC PAGES/FRIENDLY NAMES
	if(@!empty($page[2]) && @!is_numeric($page[2])) { 
		# $content_t = sales_tools_and_ideas_CONTENT_PATH."sales_tools_and_ideas_detail.html";
		
		# Shared vars/templates

		$t_vars['VIEW_CSS'] 	= 'page-sales-tools-and-ideas-detail '; # append to only!
		$t_grey = BLOCK_TEMPLATES_PATH.'related_links.html';
		$t_vars_grey = array();		
		$t_vars_grey['GREY_HEADER'] = 'Related Materials';
		$t_cross = BLOCK_TEMPLATES_PATH.'side_crosshair.html';
		$t_vars_cross = array();		
		$t_vars_cross['CROSS_HEADER'] = 'Related Links';

		# Per-item vars/templates
		switch($page[2])
		{
			# "sales tools"
			case 'consistency':
				$t_vars['VIEW_CSS'] .= 'twocol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_consistency_links.html');
				$t_vars_grey['GREY_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_consistency_materials.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_consistency_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_consistency_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_grey, $t_vars_grey);
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);				
				break;			
			case 'correlation':
				$t_vars['VIEW_CSS'] .= 'twocol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_correlation_links.html');
				$t_vars_grey['GREY_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_correlation_materials.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_correlation_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_correlation_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_grey, $t_vars_grey);
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);				
				break;
			case 'the-importance-of-diversification':
				$t_vars['VIEW_CSS'] .= 'twocol ';
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_diversification_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'tools_diversification_right.html');
				break;
			# "fund sales ideas"
			case 'an-impressive-record-of-positive-returns':
				$t_vars['VIEW_CSS'] .= 'twocol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_impressive_links.html');
				$t_vars_grey['GREY_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_impressive_materials.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_impressive_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_impressive_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_grey, $t_vars_grey);
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);
				break;
			case 'sentinel-government-securities-fund':				
				$t_vars['VIEW_CSS'] .= 'twocol ';
				$t_vars_cross['CROSS_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_governmentsecurities_links.html');
				$t_vars_grey['GREY_LINKS'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_governmentsecurities_materials.html');
				$t_vars['CONTENT_PRIMARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_governmentsecurities_left.html');
				$t_vars['CONTENT_SECONDARY'] = file_get_contents(sales_tools_and_ideas_CONTENT_PATH.'ideas_governmentsecurities_right.html');
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_grey, $t_vars_grey);
				$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);	
				break;
			default: # no match
				SentinelPage::setCurrentPageTitle('Article not found');
				$t_vars['CONTENT_PRIMARY'] = "Article not found";
				break;
		}
	}
	# --- FOR NUMERIC RETRIEVAL (FROM DB)
	elseif(@!empty($page[2]))	{
		$t = sales_tools_and_ideas_CONTENT_PATH."sales_tools_and_ideas_detail.html";
		$t_vars['VIEW_CSS'] 	= 'page-sales-tools-and-ideas-detail'; # append to only!
		$content_t_vars = array();
		
		# Retrieve insight object
		$insight = new SentinelNewsItem($page[2]);

		if(!$insight->id) { # insight not found
			SentinelPage::setCurrentPageTitle('Article not found');
			$content_t_vars['h2'] 				= 'Not Found';
			$content_t_vars['content'] 		= '';
		} else {
			SentinelPage::setCurrentPageTitle('Sales Tools and Ideas | '.$insight->news_name);
			$content_t_vars['h2'] 				= $insight->news_name;
			$content_t_vars['content'] 		= $insight->news_full;
		}
		
		# Parse whatever set of vars were set above into the outer template content area
		$t_vars['CONTENT_PRIMARY'] = parseTemplate($content_t, $content_t_vars);			
		$t_vars['CONTENT_SECONDARY'] 	= '';
		$t_vars['CONTENT_TERTIARY'] 	= '';
	}
	# --- FOR INDEX (NOT AN INDIVIDUAL ITEM) 
	else {
		SentinelPage::setCurrentPageTitle('Sales Tools and Ideas');
		$content_t = sales_tools_and_ideas_CONTENT_PATH."sales_tools_and_ideas_index.html";
		$content_t_vars = array();
		$content_t_vars['VIEW_CSS'] 	= 'page-sales-tools-and-ideas-index';
		# Parse whatever set of vars were set above into the outer template content area
		$t_vars['CONTENT_PRIMARY'] = parseTemplate($content_t, $content_t_vars);			
		$t_vars['CONTENT_SECONDARY'] 	= '';
		$t_vars['CONTENT_TERTIARY'] 	= '';
	}	
	
	# RETURN FINAL PARSE (USED BY ALL CASES ABOVE)
	return parseTemplate($t, $t_vars);
}



function advisor_login_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';
	$t_vars = array();
	
	$t_form = VIEW_CONTENT_PATH.'advisor_login.html';
	$t_vars_form = array();
	
	$t_vars_form['username'] = htmlentities(@$_GET['username'], ENT_QUOTES);
	if(@$_SESSION['fe']['UNVERIFIABLE']) {
		$t_vars_form['error_message'] = "Please call 800-233-4332 for assistance accessing the Advisor Center.";
		unset($_SESSION['fe']['UNVERIFIABLE']);
	}
	elseif(@$_SESSION['fe']['FORM_EMAIL']) {
		$t_vars_form['error_message'] = "We could not locate the email address entered.  Please re-enter your information, or call 800-233-4332 for assistance accessing the Advisor Center.";
		unset($_SESSION['fe']['FORM_EMAIL']);
	}
	elseif(@$_SESSION['fe']['NOT_FOUND']) {
		$t_vars_form['error_message'] = "The email address or password you entered does not match our records.  Please re-enter your information, or call 800-233-4332 for assistance accessing the Advisor Center.";
		unset($_SESSION['fe']['NOT_FOUND']);
	}
	elseif(isset($_SESSION['password_success'])) {
		$t_vars_form['error_message'] = "An email has been sent to you with your password.";
		unset($_SESSION['password_success']);
	}
		
	$t_vars['VIEW_CSS'] = 'onecol page-advisor-login';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate($t_form, $t_vars_form);
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = "";
	$t_vars['CONTENT_BELOW'] = '';
	
	$_SESSION['fv'] = array();
	$_SESSION['fe'] = array();

	return parseTemplate($t, $t_vars);
}



function forgotten_password_overlay_view()
{
	# OB -------------------
	ob_start();
	include($fd_dir.'page_elements/funddetail_page.php');
	$t_vars['CONTENT_PRIMARY'] = ob_get_contents(); 
	ob_end_clean();
	# END OB ---------------
}	

function product_information_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_asset = file_get_contents(BLOCK_TEMPLATES_PATH.'product_information_asset.html');
	$t_fund = file_get_contents(BLOCK_TEMPLATES_PATH.'product_information_fund.html');
	
	SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Product Information'); 
	
	$t_vars = array(); // template vars
	
	$t_left = VIEW_CONTENT_PATH.'product_information_left.html';
	$t_vars_left = array();
	
	$products = SentinelPortfolio::get_funddetail_products();

	$jumpHTML = "";
	
	$i = 0;
	foreach($products as $assetClass => $funds) {
		$jumpHTML .= "<a href=\"#".$assetClass."\">".$assetClass."</a>";
		if($i < 3) $jumpHTML .= "&nbsp;&nbsp|&nbsp;&nbsp;\n";
		$i++;
	}
	$t_vars_left['JUMP_MENU'] = $jumpHTML;

	foreach($products as $assetClass => $funds) {
		
		$t_vars_asset = array();
		
		$t_vars_asset['ASSET_TITLE'] = $assetClass;		
		
		foreach($funds as $fundID => $fund) {			
			$t_vars_fund = array();
			
			$t_vars_fund['FUND_ID'] = $fundID;
			$t_vars_fund['FUND_NAME'] = $fund['name'];
			$t_vars_fund['FUND_URL'] = $fund['url'];
			$t_vars_fund['ASSET_STYLE'] = strtolower(str_replace(' ', '', $assetClass));
			$t_vars_fund['CSS_INSTITUTIONAL'] = 'hide';
			$t_vars_fund['CSS_RETAIL'] = 'hide';
			
			$shareHTML = "";
			
			foreach($fund['share'] as $share) {
				$shareHTML .= $share['class']." Shares: <a href=\"/".$fund['url']."?share_class=".$share['class']."\">".$share['symbol']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
			}
					
			$t_vars_fund['FUND_SHARES'] =  substr($shareHTML, 0, strlen($shareHTML) - 25);
			$t_vars_fund['FUND_TAG'] = $fund['tag'];
			
			if(is_file(DOCUMENT_PATH.$fund['institutional'])) {
				$t_vars_fund['FUND_INSTITUTIONAL'] = "<a href=\"/inc/download_document.php?fileID=".$fund['institutional']."&id=FALSE\">INSTITUTIONAL FACT SHEET</a>";
				$size = filesize(DOCUMENT_PATH.$fund['institutional']);
				$t_vars_fund['FUND_INSTITUTIONAL'] .= " (PDF ".(SentinelDocument::documentFilesize($size)).")";
				$t_vars_fund['CSS_INSTITUTIONAL'] = '';
			}
			
			if(is_file(dirname(__FILE__).'/../../pdf/'.$fund['retail'])) {
				$t_vars_fund['FUND_RETAIL'] = "<a href=\"/pdf/".$fund['retail']."\" target=\"_blank\">RETAIL FACT SHEET</a>";
				$size = filesize(dirname(__FILE__).'/../../pdf/'.$fund['retail']);
				$t_vars_fund['FUND_RETAIL'] .= " (PDF ".(SentinelDocument::documentFilesize($size)).")";
				$t_vars_fund['CSS_RETAIL'] = '';
			}
			
			@$t_vars_asset['ASSET_FUNDS'] .= parseTemplate($t_fund, $t_vars_fund, FALSE);
		}
		
		@$t_vars_left['PRODUCT_INFORMATION'] .= parseTemplate($t_asset, $t_vars_asset, FALSE);
	}
	
	$t_right = BLOCK_TEMPLATES_PATH.'related_links.html';
	$t_vars_right = array();
	$t_vars_right['GREY_HEADER'] = 'Related Links';
	$t_vars_right['GREY_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'product_information_right.html');
	
	$t_vars['VIEW_CSS'] = 'twocol page-product-information';
	$t_vars['CONTENT_PRIMARY'] = parseTemplate($t_left, $t_vars_left);
	$t_vars['CONTENT_SECONDARY'] = parseTemplate($t_right, $t_vars_right);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}


/* Funddetail Pages */
function funddetail_view($page)
{
	$template = VIEW_TEMPLATES_PATH."content_three_col.html";
	$t_vars = array();
	$fd_dir = dirname(__FILE__).'/../../inc/funddetail_scripts/';
	
	# OB -------------------
	ob_start();
	include($fd_dir.'page_elements/funddetail_page.php');
	$t_vars['CONTENT_PRIMARY'] = ob_get_contents(); 
	ob_end_clean();
	# END OB ---------------

	$t_vars['VIEW_CSS'] = '';
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = '';
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($template, $t_vars);
}

/* Investment Philosophy Page*/
function investment_philosophy_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_cross		 	= BLOCK_TEMPLATES_PATH.'side_crosshair.html';
	$t_vars_cross		= array(); // template vars
	
	$t_vars_cross['CROSS_HEADER'] = 'Related Links';
	$t_vars_cross['CROSS_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'investment_philosophy_cross.html');
	
	$t_vars['VIEW_CSS'] = 'twocol page-investment-philosophy';
	$t_vars['CONTENT_PRIMARY'] = file_get_contents(VIEW_CONTENT_PATH.'investment_philosophy_left.html');
	$t_vars['CONTENT_SECONDARY'] = file_get_contents(VIEW_CONTENT_PATH.'investment_philosophy_right.html');
	$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = "";
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}


/*Advisor Announcements*/

function quarterly_announcement_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	
	SentinelPage::setCurrentPageTitle('Electronic Quarterly Dealer Statements');
	$t_vars['VIEW_CSS'] = 'twocol page-investment-philosophy';
	$t_vars['CONTENT_PRIMARY'] = file_get_contents(VIEW_CONTENT_PATH.'quarterly_announcement.html');
	$t_vars['CONTENT_SECONDARY'] = '';
	$t_vars['CONTENT_SECONDARY'] .= '';
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = "";
	$t_vars['CONTENT_BELOW'] = '';

	return parseTemplate($t, $t_vars);
}

/*End of Advisor Announcements*/


function commonstock_view($page)
{
	$t = VIEW_TEMPLATES_PATH.'content_three_col.html';	
	$t_cross		 	= BLOCK_TEMPLATES_PATH.'side_crosshair.html';
	$t_vars_cross		= array(); // template vars
	
	$t_vars_cross['CROSS_HEADER'] = 'Related Links';
	$t_vars_cross['CROSS_LINKS'] = file_get_contents(VIEW_CONTENT_PATH.'75_commonstock_cross.html');
	
	$t_vars['VIEW_CSS'] = 'twocol page-investment-philosophy';
	$t_vars['CONTENT_PRIMARY'] = file_get_contents(VIEW_CONTENT_PATH.'75_commonstock_left.html');
	$t_vars['CONTENT_SECONDARY'] = file_get_contents(VIEW_CONTENT_PATH.'75_commonstock_right.html');
	$t_vars['CONTENT_SECONDARY'] .= parseTemplate($t_cross, $t_vars_cross);
	$t_vars['CONTENT_TERTIARY'] = '';
	$t_vars['CONTENT_ABOVE'] = "";
	$t_vars['CONTENT_BELOW'] = file_get_contents(VIEW_CONTENT_PATH.'75_commonstock_below.html');

	return parseTemplate($t, $t_vars);
}


?>