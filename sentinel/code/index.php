<?php 
require_once('inc/settings.php');

/* 	FUNDDETAIL MAPPING -------------------------------------------
		We collect all the funddetail vanity URLs from the DB for use
		both here and later. If the requested URL is a match, we'll set
		the appropriate variables and change $page[0] to simply 'funddetail'.
		The view mapping switch below will handle the request from there. */
$fd_vu_obj = SentinelFundDetailVanityUrl::getInstance(); 
$funddetail_urls = $fd_vu_obj->urls;
foreach($funddetail_urls as $fid=>$farr) {
	if(@$page[0] == $farr['url']) { 	
		# requested url is a funddetail page -- set appropriate vars
		$_GET['fund_id'] = $farr['fund'];
		$page[0] = 'funddetail';
	}
} # END FUNDDETAIL MAPPING 


/* VIEW MAPPING  -------------------------------------------
		To add a new "page", you may need to add-to/modify the switch below. 
		You will definitely need to add-to or modify the 
		inc/views/views_primary.php file. */
switch(@$page[0])
{

	# ---------------------------------------
	# TESTING - JUSTIN JULY 09	-----------
	case 'nrp':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Sentinel Investments welcomes advisors from National Retirement Partners');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include('temp/jbtest-20090731.html');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;
	# ---------------------------------------
	# TESTING - JUSTIN SEPT 09	-----------
	case '100709':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Practical Implications of Investing in an Uncertain Market');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include('inc/views/content/100709.html');
		break;
	# ---------------------------------------
	# TESTING - JUSTIN SEPT 09	-----------
	case 'jbtest-20090901':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('jbtest-20090901');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include('temp/jbtest-20090901.html');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;
	# ---------------------------------------
	# TESTING - JUSTIN SEPT 09	-----------
	case 'sometimes-the-key-to-winning':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Sometimes The Key To Winning Is Not Losing');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include('temp/jbtest-20090909.html');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;
	# ---------------------------------------

	# September 28, 2009 - Teleconference Case 1232	-----------
	case 'email001':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Get Direct Insight at the 2009 WMS Conference');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include('inc/views/content/emails/si_email_schwartz1009.html');
		break;
	# ---------------------------------------

	# September 28, 2009 - Teleconference Case 1232	-----------
	case 'email002':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Get Direct Insight at the 2009 WMS Conference');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include('inc/views/content/emails/si_email_cronin1009.html');
		break;
	# ---------------------------------------


	# Sentinel Tax Center	-----------
	case 'tax-center':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Sentinel Tax Center');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include('inc/views/content/taxcenter.html');
		break;
	# ---------------------------------------





	# TESTING MENUS -------------------------
	case 'menutest':
		$m = SentinelMenu::renderMenu(1000, 500, $page[0]);
		echo $m;
		break;
	case 'menupathtest':
		$m = SentinelMenu::getParentItems('/sentinel_news.php', 1000, TRUE);
		dumpit($m);
		break;
 	# ---------------------------------------

	# ---------------------------------------
 	# TESTING MULT COLS TEMPLATE	-----------
	case 'example':	
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Example');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo example_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;
	# ---------------------------------------


	# REGEX TESTING -------------------------
# 	case 'retest':
# 		
# 		$v = '*/test/*';
# 		$tomatch = 'XDASDF/test';
# 		
# 		# Convert pattern to a regex pattern
# 		$v = '!^'.str_replace('*', '\S*', $v).'$!';
# 		if(preg_match($v, $tomatch) > 0)	
# 			echo "<strong>TRUE</strong><br />({$v} matches {$tomatch})";
# 		else echo "<strong>FALSE</strong><br />({$v} does NOT match {$tomatch})"; 
# 		break;
	# ---------------------------------------


	# ---------------------------------------
	# NEW PHASE 1D PAGES --------------------
	
	# Temporary listing of new/updated pages
	case 'phase1d': # TODO: Remove this 
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('Phase 1D Pages');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include(VIEW_CONTENT_PATH.'TEMPORARY_PHASE1D_PAGELIST.html');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');		
		break;	
	
	case 'advisor-login':
		requireAuth(FALSE);
		
		if($auth->checkAuth())
			header("Location: /advisor-center");
			
		SentinelPage::setCurrentPageTitle('Advisor Login');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo advisor_login_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		
	case 'advisor-registration':
		requireAuth(FALSE);
		
		if($auth->checkAuth())
			header("Location: /advisor-center");
			
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include(PAGE_CONTENT_PATH.'advisor_registration.php');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');		
		break;
	
	# -- Forgotten password overlay (used by advisor-registration)
	case 'forgotten-password': 
		requireAuth(FALSE);		
		include(VIEW_CONTENT_PATH.'forgotten_password.html');
		break;
	
	# --- Below are all pages to appear as children of "advisor-center/"
	case 'advisor-center':
		requireAuth(TRUE, 'advisor-center/'.@$page[1]);
		
		# VIEWMODE ... If currently in "public site" viewmode, chg viewmode to advisor site
		if(SentinelAccess::getCurrentViewmode($auth) < 200)
			SentinelAccess::setCurrentViewmode(200, $auth);		
			
		# Check for the specific page
		# Note: Page titles are set in view functions
		switch(@$page[1])
		{
			case '':
				$out = advisor_center_home_view($page);
				break;
			case 'my-profile': # todo
				$out = advisor_center_my_profile_view($page);
				break;
			case 'product-information':
				$out = product_information_view($page);
				break;
			case 'insights-and-commentary':
				$out = sales_insights_and_commentary_view($page); 
				break;
			case 'sales-tools-and-ideas':
				$out = sales_tools_and_ideas_view($page);
				break;
			case 'morningstar-tools':
				morningstar_auth($auth);
				$out = morningstar_tools_view($page);
				break;
			case 'morningstar-detail':
				morningstar_auth($auth, @$page[2]);
				$out = morningstar_detail_view($page);
				break;
			case 'electronic-quarterly-dealer-statements':
				$out = quarterly_announcement_view($page);
				break;
			case 'dst-vision':
				if(!DEVELOPMENT_MODE)
					secure_page(); # Redirect to SSL version of self if not SSL
				$out = dst_vision_view($page);
				break;					
			default:
				error_redirect(); exit(); # needs exit call or it won't redirect.
				break;			
		}	
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo $out;
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
 	# (end advisor-center child pages) --------------------------------------------
 	
 	case 'shareholder-center':  
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Shareholder Center');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo shareholder_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
 	
	case 'investment-philosophy':  
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Investment Philosophy');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo investment_philosophy_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		

	# FUND DETAIL PAGES (see code at top of this file for details)
 	case 'funddetail':
 		requireAuth(FALSE);
 		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Fund Detail'); # todo: customize title based on fund 
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo funddetail_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;

	case 'whats-uncommon-about-sentinel-common-stock-fund':
 		requireAuth(FALSE);
 		SentinelPage::setCurrentPageTitle("The Essential Elements of Investing | What's uncommon about Sentinel Common Stock Fund?"); # todo: customize title based on fund 
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo commonstock_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');	
		break;

	# PERFORMANCE PAGES ----------------------
	case 'performance':  					# (from pre-Phase 1d: /retail_shares.php?share=A&report=1)
		$_GET['share'] 		= 'A'; # NOTE! only for 'performance' case!
		$_GET['report'] 	= '1'; # NOTE! only for 'performance' case!
	case 'institutional-shares':	# formerly institutional_shares.php	
	case 'retail-shares': 				# formerly retail_shares.php	
		requireAuth(FALSE);		
		if($page[0] == 'performance' || $page[0] == 'retail-shares') 
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Retail Shares');
		if($page[0] == 'institutional-shares') 
			SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Institutional Shares');
			
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		include(PAGE_CONTENT_PATH.'widget_retail_shares2.php');
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
	# (end) PERFORMANCE PAGES ----------------		
		
		
	case 'sustainable-investing':  # (from pre-Phase 1d: /sustainable_investing.php)
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | About Sentinel | Sustainable Investing');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo sustainable_investing_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		
	case 'social-screens':  # (from pre-Phase 1d: /sentinel_social_screens.php)
		requireAuth(FALSE);
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Social Screens');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo social_screens_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
	
	
		
	# MAIN/INDEX PAGE	
	case '':
		requireAuth(FALSE);
		# --- IF NON-PUBLIC VIEWMODE, SEND TO ADVISOR HOME
		if(SentinelAccess::getCurrentViewmode($auth) > 100) 
			header("Location: /advisor-center");
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Welcome');
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');		
		echo index_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');			
		break;
			
	case 'thanks':
		requireAuth(FALSE);
		echo thanks_view($page);
		break;
		
	case 'my-account':
		if(!DEVELOPMENT_MODE)
			secure_page(); # Redirect to SSL version of self if not SSL
		requireAuth(FALSE);
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');		
		echo fan_web_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
				
	case 'fan-plan':
		if(!DEVELOPMENT_MODE)
			secure_page(); # Redirect to SSL version of self if not SSL
		requireAuth(FALSE);
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');		
		echo fan_plan_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		
	case 'proxy-voting-information':
		requireAuth(FALSE);
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');		
		echo proxy_voting_view($page);
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		
	case 'contact-sentinel':
		requireAuth(FALSE);
		
		SentinelPage::setCurrentPageTitle('The Essential Elements of Investing | Contact Us');
		
		ob_start();
		include(PAGE_CONTENT_PATH.'widget_contact.php');
		$out = ob_get_contents();
		ob_end_clean();
		
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo $out;
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
	
	case 'confidential-contact':
		requireAuth(FALSE);
		
		SentinelPage::setCurrentPageTitle("The Essential Elements of Investing | Contact Sentinel's Chief Compliance Officer");
		
		ob_start();
		include(PAGE_CONTENT_PATH.'widget_confidential.php');
		$out = ob_get_contents();
		ob_end_clean();
		
		include(PAGE_ELEMENTS_PATH.'pre_html.php');
		include(PAGE_ELEMENTS_PATH.'html_top.php');
		echo $out;
		include(PAGE_ELEMENTS_PATH.'html_bottom.php');
		break;
		
	# ERROR/INVALID ... TODO: SET ERROR_REDIRECT TO SEND A 404
	default:
		echo error_redirect();
		break;
}
?>