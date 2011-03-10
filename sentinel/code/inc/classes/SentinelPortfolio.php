<?php 

/**
 * CLASS FILE FOR SENTINEL INVESTMENTS
 */

class SentinelPortfolio
{
	public static $table = 'TODO';	
	public static $idcol = 'TODO';
	
	/**
	 * Constructor sets $this->id=FALSE if record not found
	 * @param int $id Record ID
	 */
	function __construct($id)
	{
		$mdb2 =& MDB2::singleton();		
		if(!is_numeric($id)) die(__LINE__.' '.__FILE__);
		
		$q = 	"SELECT * FROM ".self::$table." WHERE "
					.self::$idcol." = ".$mdb2->quote($id, 'integer');
		
		$r = $mdb2->query($q);
		if(PEAR::isError($r)) 
			die(__LINE__." ".__FILE__);		
		
		# POPULATE CURRENT
		if($r->numRows() == 0)
			$this->id = FALSE; # no matches
		else { 
			$properties = $r->fetchRow(); 
			foreach($properties as $col=>$val) {
				$this->$col = $val;
			}	
		}		
	}
	
	/** 
	 * Get an array of records
	 * @param mixed $limit Integer for query limit || FALSE to ignore  
	 * @param mixed $offset Integer for query offset || FALSE to ignore
	 * @param array $args Associative array of args for you to use
	 * @param bool $count_only Whether to return just the count (ignores limit, offset)
	 * @return mixed Array || Integer (if $count_only=TRUE)
	 */
	public static function getList(	$limit=FALSE, 
																	$offset=FALSE, 
																	$args=array(), 
																	$count_only=FALSE )
	{
		$mdb2 =& MDB2::singleton();

		# --- Select for count_only = TRUE
		if($count_only)
			$q = "SELECT COUNT(".self::$idcol.") AS count ";
		# --- Select for standard lookups
		else
			$q = "SELECT * ";
		
		# Rest of query (shared by both of the above)
		$q .= " WHERE ".self::$idcol." = ".self::$idcol." ";
		
		# Use limit/offset if requested
		if(!$count_only && is_numeric($limit) && is_numeric($offset))  
			$mdb2->setLimit($limit, $offset);			
		$r = $mdb2->query($q);
		if(PEAR::isError($r)) 
			die(__LINE__.' '.__FILE__);
			
		# Return for count_only=TRUE
		if($count_only) {
			$row = $r->fetchRow();
			return $row['count'];
		}	else {
			$return = array();
			while($row = $r->fetchRow()) { 
				$return[$row[self::$idcol]] = $row;
			}
			return $return;
		}
	}
	
	
	
	/**
	 * Permanently delete a record
	 * Must be called statically.
	 * @param int $id ID of record being deleted
	 * @return void
	 */	
	public static function deleteRecord($id)
	{
		$mdb2 =& MDB2::singleton();
		if(empty($id) || !is_numeric($id)) 
			die(__LINE__.' '.__FILE);
		$q = "DELETE FROM ".self::$table." WHERE "
					.self::$idcol." = ".$mdb2->quote($id, 'integer');
		$r = $mdb2->query($q);
		if(PEAR::isError($r)) 
			die("Error at ".__LINE__." ".__FILE__);					
	}
	
	
	/** 
	 * Add a new record
	 * @param array $args Associative array with args for function, query
	 * @return int ID of new record
	 */
	public static function addNew($args) 
	{
		$mdb2 =& MDB2::singleton();
		# todo
	}

	/** 
	 * Update existing record
	 * @param int $id ID of record to update
	 * @param array $args Associative array with args for function, query
	 * @return void
	 */
	public static function updateRecord($args) 
	{
		$mdb2 =& MDB2::singleton();
		# todo
	}
	
	public static function get_funddetail_products()
	{
		$factsheetArr = array();
		
		//$factsheetArr[500777]['institutional'] = '51117.pdf';
		$factsheetArr[500777]['institutional'] = '51117_Q209_i-fs-Cg.pdf';
		$factsheetArr[500777]['retail'] = '50142.pdf';
		//$factsheetArr[1012]['institutional'] = '50950.pdf';
		$factsheetArr[1012]['institutional'] = '50950_Q209_i-fs-Cs.pdf';
		$factsheetArr[1012]['retail'] = '44058.pdf';
		//$factsheetArr[500888]['institutional'] = '51119.pdf';
		$factsheetArr[500888]['institutional'] = '51119_Q209_i-fs-Gl.pdf';
		$factsheetArr[500888]['retail'] = '50141.pdf';
		$factsheetArr[1011]['institutional'] = '';
		$factsheetArr[1011]['retail'] = '44056.pdf';
		//$factsheetArr[600111]['institutional'] = '50978.pdf';
		$factsheetArr[600111]['institutional'] = '50978_Q209_i-fs-Mv.pdf';
		$factsheetArr[600111]['retail'] = '50577.pdf';
		//$factsheetArr[500012]['institutional'] = '50975.pdf';
		$factsheetArr[500012]['institutional'] = '50975_Q209_i-fs-Sc.pdf';
		$factsheetArr[500012]['retail'] = '44055.pdf';
		$factsheetArr[600555]['institutional'] = '';
		$factsheetArr[600555]['retail'] = '50806.pdf';
		$factsheetArr[1006]['institutional'] = '';
		$factsheetArr[1006]['retail'] = '44064.pdf';
		$factsheetArr[1008]['institutional'] = '';
		$factsheetArr[1008]['retail'] = '44065.pdf';
		//$factsheetArr[500111]['institutional'] = '51150.pdf';
		$factsheetArr[500111]['institutional'] = '51150_Q209_i-fs-Ie.pdf';
		$factsheetArr[500111]['retail'] = '44057.pdf';
		$factsheetArr[1013]['institutional'] = '';
		$factsheetArr[1013]['retail'] = '44059.pdf';
		$factsheetArr[500112]['institutional'] = '';
		$factsheetArr[500112]['retail'] = '48801.pdf';
		//$factsheetArr[700111]['institutional'] = '51120.pdf';
		$factsheetArr[700111]['institutional'] = '51120_Q209_i-fs-Co.pdf';
		$factsheetArr[700111]['retail'] = '50860.pdf';
		//$factsheetArr[700222]['institutional'] = '51149.pdf';
		$factsheetArr[700222]['institutional'] = '51149_Q209_i-fs-Go.pdf';
		$factsheetArr[700222]['retail'] = '50861.pdf';
	
		$mdb2 =& MDB2::singleton();
		
		$q = "SELECT asset_class, shortname FROM asset_class ORDER BY `order` ASC";
		$r = $mdb2->query($q);
		if(PEAR::isError($r))
			die("Error at ".__LINE__." ".__FILE__);
		
		$products = array();
			
		while($assetClass = $r->fetchRow()) {
			$q2 = "SELECT sentinel_fund_id, fund_name, funddetail_vanity_url FROM funds WHERE asset_class_shortname = ".$mdb2->quote($assetClass['shortname'], 'text', TRUE)
								." AND funddetail_visible = 1 ORDER BY fund_name ASC";
			$r2 = $mdb2->query($q2);
			if(PEAR::isError($r2))
				die("Error at ".__LINE__." ".__FILE__);
				
			while($fund = $r2->fetchRow()) {
				$fundID = (int) $fund['sentinel_fund_id'];
				
				$q3 = "SELECT symbol, shareClass, content FROM fund_data, funddetail_widget_texts_fund_link"
								." WHERE portfolioID = ".$fund['sentinel_fund_id']
								." AND fund_id = ".$fundID
								." AND text_shortname = 'headline'"
								." AND shareClass NOT IN ('B', 'D')"
								." ORDER BY shareClass ASC";
				$r3 = $mdb2->query($q3);
				if(PEAR::isError($r3))
					die("Error at ".__LINE__." ".__FILE__);
					
				$products[$assetClass['asset_class']][$fundID]['name'] = $fund['fund_name'];
				$products[$assetClass['asset_class']][$fundID]['url'] = $fund['funddetail_vanity_url'];
				
				$i = 0;
				while($share = $r3->fetchRow($q3)) {
					$products[$assetClass['asset_class']][$fundID]['tag'] = $share['content'];
					$products[$assetClass['asset_class']][$fundID]['share'][$i]['class'] = $share['shareclass'];
					$products[$assetClass['asset_class']][$fundID]['share'][$i]['symbol'] = $share['symbol'];
					$products[$assetClass['asset_class']][$fundID]['retail'] = $factsheetArr[$fundID]['retail'];
					$products[$assetClass['asset_class']][$fundID]['institutional'] = $factsheetArr[$fundID]['institutional'];
					$i++;
				}
			}
		}
		
		return $products;
	}

}


?>