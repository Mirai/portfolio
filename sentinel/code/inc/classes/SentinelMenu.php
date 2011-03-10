<?php 

/**
 * CLASS FILE FOR SENTINEL INVESTMENTS
 * Note that menu.minimum_permission should be 0 when no minimum and
 * menu.maximum_permission should be NULL.
 */

class SentinelMenu
{
	public static $table = 'menu';	
	public static $idcol = 'id';
	
	/**
	 * Constructor sets $this->id=FALSE if record not found
	 * @param int $id Record ID
	 */
	function __construct($id)
	{
		$mdb2 =& MDB2::singleton();		
		if(!is_numeric($id)) die(__LINE__.' '.__FILE__);
		
		$q = 	"SELECT * FROM ".self::$table
					." WHERE ".self::$idcol." = ".$mdb2->quote($id, 'integer');
		
		# TODO: drop 'view' column from menu table 
		#				(restrictions on viewability are done at the item level)
		
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
	 * Given a path, get array of its parent IDs
	 * Note that this assumes there is only one instance of $path per menu.
	 * @param string $path_or_id See arg 4
	 * @param int $menu_id ID of menu to check for.
	 * @param bool $include_self Whether to include the ID of $path itself
	 * @param bool $arg1_is_path Whether arg one is path (TRUE) or menuitem ID (FALSE)
	 * @return array 
	 */
	public static function getParentItems(	$path_or_id, 
																					$menu_id, 
																					$include_self=TRUE, 
																					$arg1_is_path=TRUE )
	{
		$mdb2 =& MDB2::singleton();		
		if(!is_numeric($menu_id)) die(__LINE__.' '.__FILE__);
		
		$return = array(); 
		
		# Get id for $path_or_id and its immediate parent
		$q = "SELECT id, parent_menuitem_id FROM menuitem "
					." WHERE menu_id = ".$mdb2->quote($menu_id, 'integer');
					
		if($arg1_is_path) 
			$q .= " AND value = ".$mdb2->quote($path_or_id, 'text', TRUE);
		else
			$q .= " AND id = ".$mdb2->quote($path_or_id, 'integer');

		$r = $mdb2->query($q);
		if(PEAR::isError($r)) 
			die($r->getDebugInfo().__LINE__." ".__FILE__);

		while($row = $r->fetchRow())
		{	
			# --- add self...
			if($include_self && !in_array($row['id'], $return)) {
				$return[] = $row['id']; 
			}
			# --- get parents of parents...
			if($row['parent_menuitem_id']) {
				$return[] = $row['parent_menuitem_id'];
				$return =  array_merge(	$return, 
																self::getParentItems(	$row['parent_menuitem_id'], 
																											$menu_id, 
																											FALSE, 
																											FALSE )
															);
			} 
		}
		return $return;
	}
	
	/** 
	 * Get menu as raw HTML unordered list
	 * @param integer $menu_id Menu ID
	 * @param integer $viewmode_id Current viewmode ID 
	 * @param string $separator Character to use as separator between items (no HTML)
	 * @param string $root_css_class Class to apply to main UL
	 * @param string $root_css_id ID to apply to main UL
	 * @param string $active_path_css_class 
	 * @param string $active_item_href 
	 * @return string
	 */
	public static function renderMenu(	$menu_id, 
																			$viewmode_id, 
																			$options = array() )
	{
		if(!is_numeric($menu_id))			die(__LINE__.' '.__FILE__);
		if(!is_numeric($viewmode_id)) die(__LINE__.' '.__FILE__);	
		
		/* Description of $options array:
				-------------------------
				$options['separator']
				$options['root_css_class']
				$options['root_css_id']
				$options['active_path_css_class']
				$options['active_item_href'] 				*/
		
		# Set defaults for $options
		if(!isset($options['separator'])) $options['separator'] = '|';
		if(!isset($options['root_css_class'])) $options['root_css_class'] = '';
		if(!isset($options['root_css_id'])) $options['root_css_id'] = '';
		if(!isset($options['active_path_css_class'])) $options['active_path_css_class'] = '';		
		if(!isset($options['active_item_href'])) $options['active_item_href'] = '';
			
		# Array of menuitem IDs leading up to active item
		if(!empty($options['active_item_href']))
			$active_path_ids = self::getParentItems($options['active_item_href'], $menu_id, TRUE);
		else $active_path_ids = array();
					
		# Get entire menu as nested associative arrays
		$m = self::buildMenuArray($menu_id, $viewmode_id, NULL);
		
		# Build array of HTML (we'll implode below as return value) 
		$html = array();
		$html[] = "<ul class=\"{$options['root_css_class']}\" ";
		if(!empty($options['root_css_id'])) $html[] = " id=\"{$options['root_css_id']}\" ";
		$html[] = ">";
		
		$c = count($m);
		$i = 1;
		foreach($m as $item) 
		{
			# Mark as in current path if necessary
			if(in_array($item['id'], $active_path_ids))
				$html[] = "<li class=\"{$options['active_path_css_class']}\">";
			else $html[] = "<li>";
			
			$html[] = "<a href=\"".$item['url']."\">".htmlentities($item['menu'])."</a>";
			
			if(!empty($item['sub'])) {
				$html[] = "<ul>";
				$html = self::renderSubMenu(	$html, 
																			$item['sub'], 
																			$options['separator'], 
																			$options['active_path_css_class'], 
																			$active_path_ids );				
				$html[] = "</ul>";				
			}
			$html[] = "</li>";
			
			# Add separator
			if($i++ < $c) $html[] = "<li class=\"separator\">{$options['separator']}</li>";
		}
		
		$html[] = "</ul>";
		
		return implode("\n", $html);;
	}
	
	/** 
	 * Build raw submenu HTML recursively
	 * @param mixed $parentArr The array as built so far, to be added on to
	 * @param mixed $subArr The sub menu array currently being looped through
	 * @param string $separator
	 * @param string $active_path_css_class 
	 * @param array $active_path_ids Array of menuitem ids leading up to current page
	 * @return array
	 */
	public static function renderSubMenu(	$parentArr, 
																				$subArr, 
																				$separator='|',
																				$active_path_css_class='',
																				$active_path_ids=array() )
	{
		if(!empty($subArr)) {
			$c = count($subArr);
			$i = 1;
			foreach($subArr as $item) 
			{
				# Mark as in current path if necessary
				if(in_array($item['id'], $active_path_ids))
					$parentArr[] = "<li class=\"{$active_path_css_class}\">";
				else $parentArr[] = "<li>";
				
				$parentArr[] = "<a href=\"".$item['url']."\">".htmlentities($item['menu'])."</a>";
				
				if(!empty($item['sub'])) {
					$parentArr[] = "<ul>";					
					$parentArr = self::renderSubMenu(	$parentArr, 
																						$item['sub'], 
																						$active_path_css_class, 
																						$active_path_ids );
					
					$parentArr[] = "</ul>";
				}				
				$parentArr[] = "</li>";
				# Add separator				
				if($i++ < $c) $parentArr[] = "<li class=\"separator\">{$separator}</li>";
			}
		}
		
		return $parentArr;
	}
	
	
 	/** 
 	 * Get menu as nested associative arrays
 	 * @param int $menu_id Menu ID
 	 * @param integer $viewmode_id Current viewmode to restrict results to
 	 * @param int $parent_id Parent id of current menu item
 	 * @return array
 	 */
 	public static function buildMenuArray($menu_id, $viewmode_id, $parent_id)
 	{
 		if(!is_numeric($menu_id)) die(__LINE__.' '.__FILE__);
 		$mdb2 =& MDB2::singleton();
 
 		$menu = array();		
 		
 		$q = "SELECT * FROM menuitem WHERE menu_id = ".$mdb2->quote($menu_id, 'integer')
					." AND minimum_permission <= ".$mdb2->quote($viewmode_id, 'integer')
					." AND ( maximum_permission >= ".$mdb2->quote($viewmode_id, 'integer')
					." 				OR maximum_permission IS NULL ) ";
		if($parent_id == NULL)
			$q .= " AND parent_menuitem_id IS NULL";
		else
			$q .= " AND parent_menuitem_id = ".$mdb2->quote($parent_id, 'integer');
			
		$q .= " ORDER BY weight ASC";
 
 		$r = $mdb2->query($q);
		
		if(PEAR::isError($r)) 
			die(__LINE__." ".__FILE__);
			
		if($r->numRows() == 0)
			$menu = array(); # no matches
		else {
			$i = 0;
			 
			while($row = $r->fetchRow()) {
				$menu[$i]['menu'] 	= $row['name'];
				$menu[$i]['url'] 		= $row['value'];	
				$menu[$i]['id'] 		= $row['id'];					
				$menu[$i]['sub'] = self::buildMenuArray($menu_id, $viewmode_id, $row['id']);	
				$i++;
			}
		}
				
		return $menu;
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


}


?>