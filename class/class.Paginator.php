<?php

// Class from http://www.catchmyfame.com/2007/07/28/finally-the-simple-pagination-class/

class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $querystring;
	var $orderby;
	var $sortorder;
	var $order;

	function Paginator($orderby = 'title', $sortorder = 'ASC', $ipp = 100) {
		$this->current_page = 1;
		$this->mid_range = 7;
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp'] : $ipp;
		$this->orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : $orderby;
		$this->sortorder = (!empty($_GET['sortorder'])) ? $_GET['sortorder'] : $sortorder;
	}

	function paginate() {
		if (@$_GET['ipp'] == 'All') {
			$this->num_pages = ceil($this->items_total/$this->default_ipp);
			$this->items_per_page = $this->default_ipp;
		} else {
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total/$this->items_per_page);
		}
		$this->current_page = (int) @$_GET['page']; // must be numeric > 0
		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

		if ($_GET) {
			$args = explode("&",$_SERVER['QUERY_STRING']);
			foreach($args as $arg) {
				$keyval = explode("=",$arg);
				if($keyval[0] != "page" And $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
			}
		}

		if ($_POST) {
			foreach($_POST as $key=>$val) {
				if($key != "page" And $key != "ipp") $this->querystring .= "&$key=$val";
			}
		}

		if ($this->num_pages > 10) {
			$this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"?page=$prev_page&ipp=$this->items_per_page$this->querystring\">&laquo; Previous</a> ":"<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if ($this->start_range <= 0) {
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if ($this->end_range > $this->num_pages) {
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for ($i=1;$i<=$this->num_pages;$i++) {
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
				// loop through all pages. if first, last, or in range, display
				if ($i==1 Or $i==$this->num_pages Or in_array($i,$this->range)) {
					$this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
				}
				if ($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
			}
			$this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"?page=$next_page&ipp=$this->items_per_page$this->querystring\">Next &raquo;</a>\n":"<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
			//$this->return .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
		} elseif ($this->num_pages > 1) {
			for($i=1;$i<=$this->num_pages;$i++) {
				$this->return .= ($i == $this->current_page) ? "<span class=\"current\" href=\"#\">$i</span> ":"<a class=\"paginate\" href=\"?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
			}
			//$this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
		}
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = (@$_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = (@$_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
		$this->order = " ORDER BY $this->orderby $this->sortorder ";
	}

	function display_items_per_page() {
		$items = '';
		$ipp_array = array(10,25,50,100,'All');
		foreach($ipp_array as $ipp_opt)	$items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
	}

	function display_jump_menu() {
		for($i=1;$i<=$this->num_pages;$i++) {
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
	}

	function display_pages() {
		return $this->return;
	}
	
	function get_href($orderby) {									
		$href  = "?page=$this->current_page&ipp=$this->items_per_page&orderby=" . $orderby;
		if ($orderby == $this->orderby) {
			if (!isset($_GET['sortorder'])) { 
				$href .= "&sortorder=DESC";	
			} else {
				if ($_GET['sortorder'] == 'ASC')	{
					$href .= "&sortorder=DESC";	
				} else {
					$href .= "&sortorder=ASC";
				}
			}
		} else {
			$href .= "&sortorder=ASC";
		}
		
		return $href;
	}
	
	function get_arrow($orderby) {
		if (isset($_GET['orderby']) && $_GET['orderby'] == $orderby) { 
			if ($this->sortorder == "ASC") { 
				return '<img src="' . CMS_ROOT . 'images/arrow-down.gif" width="7" height="7" hspace="2" />';
			} else { 
				return '<img src="' . CMS_ROOT . 'images/arrow-up.gif" width="7" height="7" hspace="2" />';
			} 
		} 
	}
}