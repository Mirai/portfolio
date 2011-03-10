<?php 

require_once("inc/db.php");


$type = (isset($_GET['type'])) ? $_GET['type'] : $_SERVER['argv'][1];

function libxml_display_error($error)
{
    $return = "<br/>\n";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<b>Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<b>Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<b>Fatal Error $error->code</b>: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " in <b>$error->file</b>";
    }
    $return .= " on line <b>$error->line</b>\n";

    return $return;
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    $errorList = "";
    foreach ($errors as $error) {
        $errorMsg = libxml_display_error($error);
        $errorList .= $errorMsg;
        print $errorMsg;
    }
    mail("robert@drwebsolutions.com","XML-to-DB Validation Errors Found!",$errorList,"From: webmaster@sentinelinvestments.com" . "\r\n" . "Reply-To: webmaster@sentinelinvestments.com" . "\r\n" . "X-Mailer: PHP/" . phpversion());
    libxml_clear_errors();
}

// Enable user error handlingYtd	
libxml_use_internal_errors(true);
	
switch($type)
{
case '1':
	$data = 'data/daily_price_return.xml';
	$schema = "data/daily_pricing.xsd";
	break;
case '2':
	$data = 'data/month-end_price_return.xml';
	$schema = "data/fund_performance.xsd";
	$table= "`monthly_performance`";
	break;
case '3':
	$data = 'data/quarter-end_price_return.xml';
	$schema = "data/fund_performance.xsd";
	$table = "`quarterly_performance`";
	break;
case '4':
	$data = 'data/funds_main.xml';
	$schema = "data/funds_main.xsd";
	break;
case '5':
	$data = "data/Morningstar_Essentials.xml";
	$schema = "data/Morningstar_Essentials.xsd";
	break;
case '6':
	$data = "data/Lipper_Fund_Performance.xml";
	$schema = "data/Lipper_Fund_Performance.xsd";
	break;
case '7':
	$data = "data/daily_yield.xml";
	$schema = "data/daily_yield.xsd";
	break;
case '8':
	$data = "data/FactSet_Fund_Details.xml";
	$schema = "data/FactSet_Fund_Details.xsd";
	break;
case '9':
	$data = "data/Holdings.xml";
	$schema = "data/Holdings.xsd";
	break;
case '10':
	$data = "data/BondEdge.xml";
	$schema = "data/BondEdge.xsd";
	break;
case '11':
	$data = 'data/wholesalers.xml';
	$schema = 'data/wholesalers.xsd';
	break;
case '12':
	$data = 'data/territories.xml';
	$schema = 'data/territories.xsd';
	break;
case '13':
	$data = 'data/PortfolioManagers.xml';
	$schema = 'data/PortfolioManagers.xsd';
	break;
case '14':
	$data = 'data/Marketing_Text.xml';
	$schema = 'data/Marketing_Text.xsd';
	break;
}

$xml = new DOMDocument();
$xml->load($data);

if (!$xml->schemaValidate($schema)) {
    print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
    libxml_display_errors();
} else {
	$xml = simplexml_load_file($data);
	$date = $xml->date;
	
	switch($type)
	{
	case '1':
 $cleanTable = "TRUNCATE TABLE `daily_pricing`";
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		//daily insertion case.  loop through all funds
		foreach($xml->fund as $fund)
		{
			//loop through all shares of the fund
			foreach($fund->share as $share)
			{
				//check to see if data has been inserted today for the share
				$query_checkShare = sprintf("SELECT * FROM `daily_pricing` WHERE `dateStart` = '%s' AND `fundNumber` = '%s'",$date,$share->fund_number);
				$checkShare = mysql_query($query_checkShare,$sentinel) or die(mysql_error());
				$totalRows_checkShare = mysql_fetch_assoc($checkShare);
				
				if($totalRows_checkShare != 0)
				{
					//data has been inserted today, update instead of inserting
					$updateShare = sprintf("UPDATE `daily_pricing` SET `navPrice`='%s',`navChange`='%s',`popPrice`='%s',`popChange`='%s',`netAssets`='%s',`loadYtd`='%s',`loadOne`='%s',`loadThree`='%s',`loadFive`='%s',`loadTen`='%s',`loadIncept`='%s',`noLoadYtd`='%s',`noLoadOne`='%s',`noLoadThree`='%s',`noLoadFive`='%s',`noLoadTen`='%s',`noLoadIncept`='%s',`dateStart`='%s' WHERE `fundNumber`='%s'",$share->nav,$share->nav_change,$share->pop,$share->pop_change,$share->net_assets,$share->load[0]->ytd,$share->load[0]->oneyr,$share->load[0]->thryr,$share->load[0]->fivyr,$share->load[0]->tenyr,$share->load[0]->incept,$share->no_load[0]->ytd,$share->no_load[0]->oneyr,$share->no_load[0]->thryr,$share->no_load[0]->fivyr,$share->no_load[0]->tenyr,$share->no_load[0]->incept,$date,$share->fund_number);
					//echo $updateShare."<br />";
					$updateResult = mysql_query(str_replace("$","",str_replace("%","",$updateShare)),$sentinel) or die(mysql_error());
				} else {
					//data has not been inserted today, insert data
  $insertShare = sprintf("INSERT INTO `daily_pricing` (`portfolioID`,`fundNumber`,`navPrice`,`navChange`,`popPrice`,`popChange`,`netAssets`,`noLoadYtd`,`noLoadOne`,`noLoadThree`,`noLoadFive`,`noLoadTen`,`noLoadIncept`,`loadYtd`,`loadOne`,`loadThree`,`loadFive`,`loadTen`,`loadIncept`,`dateStart`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$fund->portfolio_id,$share->fund_number,$share->nav,$share->nav_change,$share->pop,$share->pop_change,$share->net_assets,$share->load[0]->ytd,$share->load[0]->oneyr,$share->load[0]->thryr,$share->load[0]->fivyr,$share->load[0]->tenyr,$share->load[0]->incept,$share->no_load[0]->ytd,$share->no_load[0]->oneyr,$share->no_load[0]->thryr,$share->no_load[0]->fivyr,$share->no_load[0]->tenyr,$share->no_load[0]->incept,$date);
					//echo $insertShare."<br />";
					$insertResult = mysql_query(str_replace("$","",str_replace("%","",$insertShare)),$sentinel) or die(mysql_error());
				}
			}
		}
		break;
	case '2':
	case '3':
     $cleanTable = sprintf("TRUNCATE TABLE %s",$table);
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		//monthly insertion case, loop through all funds
		foreach($xml->fund as $fund)
		{
			//loop through all shares of the fund
			foreach($fund->share as $share)
			{
				//check to see if data has been inserted today for the share
				$query_checkShare = sprintf("SELECT * FROM %s WHERE `dateStart` = '%s' AND `fundNumber` = '%s'",$table,$date,$share->fund_number);
				$checkShare = mysql_query($query_checkShare,$sentinel) or die(mysql_error());
				$totalRows_checkShare = mysql_fetch_assoc($checkShare);
				
				if($totalRows_checkShare != 0)
				{
					//data has been inserted today, update instead of inserting
					$updateShare = sprintf("UPDATE %s SET `navPrice`='%s',`offeringPrice`='%s',`netAssets`='%s',`divDate`='%s',`dividend`='%s',`secYield`='%s',`noLoadYtd`='%s',`noLoadOne`='%s',`noLoadThree`='%s',`noLoadFive`='%s',`noLoadTen`='%s',`noLoadIncept`='%s',`loadYtd`='%s',`loadOne`='%s',`loadThree`='%s',`loadFive`='%s',`loadTen`='%s',`loadIncept`='%s',`dateStart`='%s' WHERE `fundNumber` = '%s'",$table,$share->nav,$share->offering_price,$share->net_assets,$share->div_date,$share->dividend,$share->sec_yield,$share->load[0]->ytd,$share->load[0]->oneyr,$share->load[0]->thryr,$share->load[0]->fivyr,$share->load[0]->tenyr,$share->load[0]->incept,$share->no_load[0]->ytd,$share->no_load[0]->oneyr,$share->no_load[0]->thryr,$share->no_load[0]->fivyr,$share->no_load[0]->tenyr,$share->no_load[0]->incept,$date,$share->fund_number);
					//echo $updateShare."<br />";
					$updateResult = mysql_query(str_replace("$","",str_replace("%","",$updateShare)),$sentinel) or die(mysql_error());
				} else {
					//data has not been inserted today, insert data
  					
					$insertShare = sprintf("INSERT INTO %s (`portfolioID`,`fundNumber`,`navPrice`,`offeringPrice`,`netAssets`,`divDate`,`dividend`,`secYield`,`noLoadYtd`,`noLoadOne`,`noLoadThree`,`noLoadFive`,`noLoadTen`,`noLoadIncept`,`loadYtd`,`loadOne`,`loadThree`,`loadFive`,`loadTen`,`loadIncept`,`dateStart`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$table,$fund->portfolio_id,$share->fund_number,$share->nav,$share->offering_price,$share->net_assets,$share->div_date,$share->dividend,$share->sec_yield,$share->load[0]->ytd,$share->load[0]->oneyr,$share->load[0]->thryr,$share->load[0]->fivyr,$share->load[0]->tenyr,$share->load[0]->incept,$share->no_load[0]->ytd,$share->no_load[0]->oneyr,$share->no_load[0]->thryr,$share->no_load[0]->fivyr,$share->no_load[0]->tenyr,$share->no_load[0]->incept,$date);
					//echo $insertShare."<br />";
					
					$insertResult = mysql_query(str_replace("$","",str_replace("%","",$insertShare)),$sentinel) or die(mysql_error());
				}
			}
		}
		break;
	case '4':
  $cleanTable = "TRUNCATE TABLE `fund_data`";
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());   
		//fund information insertion, loop through all funds
		foreach($xml->fund as $fund)
		{
			//loop through all shares of the fund
			foreach($fund->share as $share)
			{
				//check to see if data has been inserted today for the share
				$query_checkShare = sprintf("SELECT * FROM `fund_data` WHERE `dateStart` = '%s' AND `fundNumber` = '%s'",$date,$share->fund_number);
				$checkShare = mysql_query($query_checkShare,$sentinel) or die(mysql_error());
				$totalRows_checkShare = mysql_fetch_assoc($checkShare);
				
				if($totalRows_checkShare != 0)
				{
					$inception_bits = explode("/",$share->incept_date);
					
					$inception = mktime(0,0,0,$inception_bits[0],$inception_bits[1],$inception_bits[2]);
					//data has been inserted today, update instead of inserting
					$updateShare = sprintf("UPDATE `fund_data` SET `portfolioID`='%s',`fundName`='%s',`objective`='%s',`shareClass`='%s',`symbol`='%s',`cusip`='%s',`inceptDate`='%s',`salesCharge`='%s',`divFreq`='%s',`initInvest`='%s',`benchmarkID`='%s',`expenseRatio`='%s',`maxSalesCharge`='%s',`dateStart`='%s' WHERE `fundNumber`='%s'",$fund->portfolio_id,$fund->fund_name,$fund->objective,$share->class,$share->ticker_symbol,$share->cusip,$inception,$share->sales_charge,$share->div_freq,$share->initial_investment,$share->benchmark_id,$share->expense_ratio,$share->max_sales_charge,$date,$share->fund_number);
					$updateResult = mysql_query(str_replace("$","",str_replace("%","",$updateShare)),$sentinel) or die(mysql_error());
				} else {
					//data has not been inserted today, insert data
					
					$inception_bits = explode("/",$share->incept_date);
					$inception = mktime(0,0,0,$inception_bits[0],$inception_bits[1],$inception_bits[2]);
                      
					$insertShare = sprintf("INSERT INTO `fund_data` (`portfolioID`,`fundName`,`objective`,`fundNumber`,`shareClass`,`symbol`,`cusip`,`inceptDate`,`salesCharge`,`divFreq`,`initInvest`,`benchmarkID`,`expenseRatio`,`maxSalesCharge`,`dateStart`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$fund->portfolio_id,$fund->fund_name,$fund->objective,$share->fund_number,$share->class,$share->ticker_symbol,$share->cusip,$inception,$share->sales_charge,$share->div_freq,$share->initial_investment,$share->benchmark_id,$share->expense_ratio,$share->max_sales_charge,$date);
					$insertResult = mysql_query(str_replace("$","",str_replace("%","",$insertShare)),$sentinel) or die(mysql_error());
				}
			}
		}
		break;
	case '5':
  $cleanTable = "TRUNCATE TABLE `morningstar`";
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		foreach($xml->Class as $class)
		{     
			$insertSQL = sprintf("INSERT INTO `morningstar` (`shareID`,`fundName`,`feeStatus`,`secID`,`monthEndDate`,`morningstarCategory`,`ratingEndDate`,`ratingGroupOverall`,`overallRating`,`ratingGRoup3Yr`,`threeYrRating`,`ratingGroup5Yr`,`fiveYrRating`,`ratingGroup10Yr`,`tenYrRating`,`oneYrPercRank`,`threeYrPercRank`,`fiveYrPercRank`,`tenYrPercRank`,`oneYrAbsRank`,`oneYrNumFunds`,`threeYrAbsRank`,`threeYrNumFunds`,`fiveYrAbsRank`,`fiveYrNumFunds`,`tenYrAbsRank`,`tenYrNumFunds`,`equityStylebox`,`bondStylebox`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$class->confluence_fund_class_id,$class->confluence_fund_class_name,$class->Fee_Status,$class->SecId,$class->Month_End_Date,$class->Morningstar_Category,$class->RatingEndDate,$class->RatingGroupOverall,$class->Overall_Morningstar_Rating,$class->RatingGroup3Year,$class->Three_Year_Morningstar_Rating,$class->RatingGroup5Year,$class->Five_Year_Morningstar_Rating,$class->RatingGroup10Year,$class->Ten_Year_Morningstar_Rating,$class->One_Yr_Category_Percentile_Rank,$class->Three_Yr_Category_Percentile_Rank,$class->Five_Yr_Category_Percentile_Rank,$class->Ten_Yr_Category_Percentile_Rank,$class->One_Yr_Category_Absolute_Rank,$class->One_Yr_Category_Number_of_Funds,$class->Three_Yr_Category_Absolute_Rank,$class->Three_Yr_Category_Number_of_Funds,$class->Five_Yr_Category_Absolute_Rank,$class->Five_Yr_Category_Number_of_Funds,$class->Ten_Yr_Category_Absolute_Rank,$class->Ten_Yr_Category_Number_of_Funds, $class->Morningstar_Equity_Stylebox, $class->Morningstar_Bond_Stylebox);
			$result = mysql_query($insertSQL,$sentinel) or die(mysql_error());
		}
		break;
	case '6':
     $cleanTable = "TRUNCATE TABLE `lipper`";
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		foreach($xml->Class as $class)
		{
			
			$dataString = (($class->standard_numeric_rank_1_year) ? "'".$class->standard_numeric_rank_1_year."'" : "NULL").",";
			$dataString .= (($class->count_of_funds_within_classification_1_year) ? "'".$class->count_of_funds_within_classification_1_year."'" : "NULL").",";
			$dataString .= (($class->standard_numeric_rank_3_year) ? "'".$class->standard_numeric_rank_3_year."'" : "NULL").",";
			$dataString .= (($class->count_of_funds_within_classification_3_year) ? "'".$class->count_of_funds_within_classification_3_year."'" : "NULL").",";
			$dataString .= (($class->standard_numeric_rank_5_year) ? "'".$class->standard_numeric_rank_5_year."'" : "NULL").",";
			$dataString .= (($class->count_of_funds_within_classification_5_year) ? "'".$class->count_of_funds_within_classification_5_year."'" : "NULL").",";
			$dataString .= (($class->standard_numeric_rank_10_year) ? "'".$class->standard_numeric_rank_10_year."'" : "NULL").",";
			$dataString .= (($class->count_of_funds_within_classification_10_year) ? "'".$class->count_of_funds_within_classification_10_year."'" : "NULL");
			
               
 
			$insertSQL = sprintf("INSERT INTO `lipper` (`shareID`,`shareName`,`fundClassificationCode`,`latestMonthEndDate`,`standardNumRank1Yr`,`countOfFunds1Yr`,`standardNumRank3Yr`,`countOfFunds3Yr`,`standardNumRank5Yr`,`countOfFunds5Yr`,`standardNumRank10Yr`,`countOfFunds10Yr`) VALUES ('%s','%s','%s','%s',%s)",$class->confluence_fund_class_id,$class->confluence_fund_class_name,$class->fund_classification_code,$class->latest_month_end_date,$dataString);
			$result = mysql_query($insertSQL,$sentinel) or die(mysql_error());
		}
		break;
 case '7':
  $cleanTable = "TRUNCATE TABLE `daily_yield`";
  $cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
  foreach($xml->fund as $fund) {
       foreach($fund->share as $share) {
            if($share->sec_yield != 'N/A') {
                 
                 $insertSQL = sprintf("INSERT INTO `daily_yield` (`portfolioID`,`fundNumber`,`secYield`,`dateStart`) VALUES ('%s','%s','%s','%s')",$fund->portfolio_id,$share->fund_number,$share->sec_yield,$xml->date);       
                 $result = mysql_query($insertSQL,$sentinel) or die(mysql_error());
            }
       }    
  }
  break;
	case '8':
		$cleanTable = "TRUNCATE TABLE `factset_details`";
		$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		foreach($xml->Portfolio as $portfolio) {
			$pfID = $portfolio->Portfolio_Id;
			$monthEnd = $portfolio->Month_End_Date;
			$holdings = $portfolio->Number_Of_Holdings;
			$cashPerc = $portfolio->Cash_Percentage;
			$medMarketCap = ($portfolio->Weighted_Median_Market_Cap != "" ? "'".$portfolio->Weighted_Median_Market_Cap."'" : "NULL");
			$percLT3B = ($portfolio->Percent_Less_Than_3_Billion != "" ? "'".$portfolio->Percent_Less_Than_3_Billion."'" : "NULL");
			$perc3To12B = ($portfolio->Percent_3_To_12_Billion != "" ? "'".$portfolio->Percent_3_To_12_Billion."'" : "NULL");
			$perc12To25B = ($portfolio->Percent_12_To_25_Billion != "" ? "'".$portfolio->Percent_12_To_25_Billion."'" : "NULL");
			$perc25To50B = ($portfolio->Percent_25_To_50_Billion != "" ? "'".$portfolio->Percent_25_To_50_Billion."'" : "NULL");
			$percGT50B = ($portfolio->Percent_Greater_Than_50_Billion != "" ? "'".$portfolio->Percent_Greater_Than_50_Billion."'" : "NULL");
			
			$insertSQL = sprintf("INSERT INTO `factset_details` (`portfolioID`,`numHoldings`,`cashPercentage`,`weightedMedianMarketCap`,`percLT3B`,`perc3To12B`,`perc12To25B`,`perc25To50B`,`percGT50B`,`monthEndDate`) VALUES ('%s','%s','%s',%s,%s,%s,%s,%s,%s,'%s')",$pfID, $holdings, $cashPerc, $medMarketCap, $percLT3B, $perc3To12B, $perc12To25B, $perc25To50B, $percGT50B, $monthEnd);
			//echo $insertSQL;
			$result = mysql_query($insertSQL, $sentinel) or die(mysql_error());
			}
		break;
		case '9': // Fund detail Top Holdings parse
		$cleanTable = "TRUNCATE TABLE `factset_holdings`";
		$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		
		foreach($xml->Portfolio as $portfolio) {
			$pfID = $portfolio->portfolio_id;
			
			foreach($portfolio->Holding as $holding) {
				$secName = mysql_real_escape_string($holding->Holding_name);
				$pfPerc = $holding->percentage;
				$equityIndicator = $holding->Is_Equity_Indicator;
				$secIndustry = ($holding->Industry_name != "" ? "'".mysql_real_escape_string($holding->Industry_name)."'" : "NULL");
				$monthEnd = $holding->as_of_date;
				
				$insertSQL = sprintf("INSERT INTO `factset_holdings` (
														`portfolioID`,
														`secName`,
														`secIndustry`,
														`portfolioPerc`,
														`equityIndicator`,
														`monthEndDate`
													) VALUES ('%s','%s',%s,'%s',%s,'%s')",
														$pfID,
														$secName,
														$secIndustry,
														$pfPerc,
														$equityIndicator,
														$monthEnd);
				//echo $insertSQL;
				$result = mysql_query($insertSQL, $sentinel) or die(mysql_error());
				}
			}
		break;
		case '10':
		$cleanTable = "TRUNCATE TABLE `bond_edge`";
		$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
		
		foreach($xml->Portfolio as $portfolio) {
			$pfID = $portfolio->Portfolio_Id;
			$monthEnd = $portfolio->Month_End_Date;
			$effMaturity = $portfolio->Effective_Maturity;
			$effDur = $portfolio->Effective_Duration;
   $avgQuality = $portfolio->Average_Quality;
			
			$insertSQL = sprintf("INSERT INTO `bond_edge` (`portfolioID`,`effMaturity`, `effDuration`,`monthEndDate`,`averageQuality`) VALUES ('%s','%s','%s','%s','%s')", $pfID, $effMaturity, $effDur, $monthEnd, $avgQuality);
			$result = mysql_query($insertSQL, $sentinel) or die(mysql_error());
			}
		break;
		case '11': // wholesaler data parse
			$cleanTable = "TRUNCATE TABLE `wholesaler`";
			$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
			$cleanTable2 = "TRUNCATE TABLE `wholesaler_territory`";
			$cleanResult2 = mysql_query($cleanTable2, $sentinel) or die(mysql_error());
			
			foreach($xml->Wholesaler as $wholesaler) {
				$wholesalerRole = $wholesaler->Role;
				$wholeslaerEmail = '';
				$wholesalerPhone = '';
				$wholesalerExtension = '';
				$wholesalerIndustry = '';

				if(!empty($wholesaler->Emails->Email)) {
					foreach($wholesaler->Emails->Email as $email) {
						if($email->Type == 'business email')
							$wholesalerEmail = $email->Address;
					}
				}
				
				if(!empty($wholesaler->Phones->Phone)) {
					foreach($wholesaler->Phones->Phone as $phone) {
						if($phone->Type == 'business cell' && $wholesalerRole == "External Wholesaler")
							$wholesalerPhone = $phone->Number;
						elseif($phone->Type == 'business land line' && $wholesalerRole == "Internal Wholesaler")
							$wholesalerPhone = $phone->Number;
					}
				}
				
				if(!empty($wholesaler->IndustryDesignations)) {
					foreach($wholesaler->IndustryDesignations as $industry) {
						$wholesalerIndustry = $industry->IndustryDesignation;
					}
				}
				
				$insertSQL = sprintf("INSERT INTO `wholesaler` (`wholesaler_id`, `wholesaler_first`, `wholesaler_last`, `wholesaler_title`, `wholesaler_role`, `wholesaler_industry`, `wholesaler_email`, `wholesaler_phone`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $wholesaler->Person_ID, $wholesaler->First_Name, $wholesaler->Last_Name, $wholesaler->Title, $wholesaler->Role, $wholesalerIndustry, $wholesalerEmail, $wholesalerPhone);
				
				$result = mysql_query($insertSQL, $sentinel) or die(mysql_error());
				
				if(!empty($wholesaler->Territories->Territory)) {
					foreach($wholesaler->Territories->Territory as $territory) {
						$insertSQL2 = sprintf("INSERT INTO `wholesaler_territory` (`wholesaler_id`, `territory_id`) VALUES ('%s', '%s')", $wholesaler->Person_ID, $territory->Code);
						$result2 = mysql_query($insertSQL2, $sentinel) or die(mysql_error());
					}
				}
			}
		break;
		case '12': // wholesaler territory parse
			$cleanTable = "TRUNCATE TABLE `territory`";
			$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error());
			$cleanTable2 = "TRUNCATE TABLE `territory_zip`";
			$cleanResult2 = mysql_query($cleanTable2, $sentinel) or die(mysql_error());
			
			foreach($xml->Territory as $territory) {
				$insertSQL = sprintf("INSERT INTO `territory` (`territory_id`, `territory_name`) VALUES ('%s', '%s')", $territory->territory_code, $territory->territory_name);				
				$result = mysql_query($insertSQL, $sentinel) or die(mysql_error());
				
				if(!empty($territory->ZipCodes->ZipCode)) {
					foreach($territory->ZipCodes->ZipCode as $zip) {
						$insertSQL2 = sprintf("INSERT INTO `territory_zip` (`territory_id`, `territory_zip`) VALUES ('%s', '%s')", $territory->territory_code, $zip);
						$result2 = mysql_query($insertSQL2, $sentinel) or die(mysql_error());
					}
				}
			}
		break;
		case '13': // personnel parse
			$cleanTable = "TRUNCATE TABLE `management`";
			$cleanResult = mysql_query($cleanTable, $sentinel) or die(mysql_error()."<br>".$cleanTable."<br>");
			$cleanTable2 = "TRUNCATE TABLE `portfolio_management`";
			$cleanResult2 = mysql_query($cleanTable2, $sentinel) or die(mysql_error()."<br>".$cleanTable2."<br>");
			$cleanTable3 = "TRUNCATE TABLE `fund_management`";
			$cleanResult3 = mysql_query($cleanTable3, $sentinel) or die(mysql_error()."<br>".$cleanTable3."<br>");
			
			foreach($xml->PortfolioManager as $manager) {
				$managerID = $manager->Person_ID;
				$picture = strtolower(str_replace(array(' ', '.', ','), '', $manager->Last_Name)).'.jpg';
				
				$desigArr = (array) $manager->IndustryDesignations;
				if(is_array($desigArr['IndustryDesignation'])) {
					$designation = implode(', ', $desigArr['IndustryDesignation']);
				} else {
					$designation = implode('', $desigArr);
				}
				
				$query_management = sprintf("INSERT INTO `management` (
												`manager_id`,
												`fname`,
												`middle`,
												`lname`,
												`designation`,
												`picture`,
												`start_year`,
												`financial_tenure`
											) VALUES (%s, '%s', '%s', '%s', '%s', '%s', %s, %s)",
												$managerID,
												$manager->First_Name,
												$manager->middle_initial,
												$manager->Last_Name,
												$designation,
												$picture,
												$manager->SentinelTenure,
												(!empty($manager->IndustryTenure) ? $manager->IndustryTenure : 0));
												
				$managementResult = mysql_query($query_management, $sentinel) or die(mysql_error()."<br>".$query_management."<br>");
				//echo $query_management."<br>";
				
				if(!empty($manager->TeamsAndRoles->Team)) {
					foreach($manager->TeamsAndRoles->Team as $team) {
						if(!empty($manager->company_name)) {
							$business = $manager->company_name;
						} else {
							$business = NULL;
						}
						
						$bio = array();
						foreach($manager->Biography->Paragraph as $paragraph) {
							$bio[] = $paragraph;
						}
						$bio = utf8_decode(implode("<br /><br />", $bio));
					
						$query_portfolio = sprintf("INSERT INTO `portfolio_management` (
														`portfolio_team`,
														`management_id`,
														`title`,
														`officer`,
														`business`,
														`bio`,
														`role`,
														`position`
													) VALUES (%s, %s, '%s', '%s', '%s', '%s', '%s', %s)",
														$team->TeamID,
														$managerID,
														$manager->Title,
														$manager->officer_level_description,
														$business,
														mysql_real_escape_string($bio),
														$team->TeamRole,
														$team->TeamDisplayOrder);
														
						$portfolioResult = mysql_query($query_portfolio, $sentinel) or die(mysql_error()."<br>".$query_portfolio."<br>");
						//echo $query_portfolio."<br>";
					}
				}
				
				if(!empty($manager->FundsAndRoles->Fund)) {
					foreach($manager->FundsAndRoles->Fund as $fund) {
						$query_fund = sprintf("INSERT INTO `fund_management` (
														`manager_id`,
														`fund_id`,
														`position`,
														`role`
													) VALUES (%s, %s, %s, '%s')",
														$managerID,
														(int) $fund->FundNumber,
														$fund->FundDisplayOrder,
														$fund->FundRole);
														
						$fundResult = mysql_query($query_fund, $sentinel) or die(mysql_error()."<br>".$query_fund."<br>");
						//echo $query_fund."<br>";
					}
				}
				//echo "<br>";
			}
		break;		
		case '14': // marketing text parse
			$cleanTable = "TRUNCATE TABLE `funddetail_widget_texts_fund_link`";
			mysql_query($cleanTable, $sentinel) or die(mysql_error()."<br>".$cleanTable."<br>");
			
			foreach($xml->Portfolio as $marketing) {
				$fundID = (int) $marketing->Portfolio_ID;
				
				foreach($marketing as $key => $item) {
					$shortname = '';
					$shortname2 = '';
					$content = '';
					$content2 = '';
					
					switch($key) {
						case 'Objective':
							$shortname = 'objective';
							$content = $item;
							break;
						case 'Fund_Headline':
							$shortname = 'headline';
							$content = $item;
							break;
						case 'Fund_Detail':
							switch($item->Order) {
								case 1:
									$shortname = 'intro_paragraph_1_headline';
									$shortname2 = 'intro_paragraph_1';
									break;
								case 2:
									$shortname = 'intro_paragraph_2_headline';
									$shortname2 = 'intro_paragraph_2';
									break;
								case 3:
									$shortname = 'intro_paragraph_3_headline';
									$shortname2 = 'intro_paragraph_3';
									break;
							}
							
							$content = $item->Heading;
							$content2 = '<p>'.$item->Content.'</p>';
							break;
						case 'Attribution_Analysis':
							break;
					}
					
					if(!empty($shortname)) {
						$query_marketing = sprintf("INSERT INTO `funddetail_widget_texts_fund_link` (
															`text_shortname`,
															`fund_id`,
															`content`
														) VALUES ('%s', %s, '%s')",
															$shortname,
															$fundID,
															$content);
															
						mysql_query($query_marketing, $sentinel) or die(mysql_error()."<br>".$query_marketing."<br>");
						
						if(!empty($shortname2)) {
							$query_marketing2 = sprintf("INSERT INTO `funddetail_widget_texts_fund_link` (
															`text_shortname`,
															`fund_id`,
															`content`
														) VALUES ('%s', %s, '%s')",
															$shortname2,
															$fundID,
															$content2);	
							
							mysql_query($query_marketing2, $sentinel) or die(mysql_error()."<br>".$query_marketing2."<br>");
						}
					}
				}
				
				$query_objective = sprintf("INSERT INTO `funddetail_widget_texts_fund_link` (
													`text_shortname`,
													`fund_id`,
													`content`
												) VALUES ('objective_headline', %s, 'Objective')",
													$fundID);
													
				mysql_query($query_objective, $sentinel) or die(mysql_error()."<br>".$query_objective."<br>");
				//echo "<br>";
			}
		break;
	}
	
	echo "Data successfully inserted!\n";
}

?>