<?php
/**
 * @package Unlimited Elements
 * @author unlimited-elements.com
 * @copyright (C) 2021 Unlimited Elements, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorFiltersProcess{

	private static $filters = null;
	private static $arrInputFiltersCache = null;
	private static $arrFiltersAssocCache = null;
		
	private static $isFilesAdded = false;
	private static $showDebug = false;
	
	const TYPE_TERMS = "terms";
	
	
	/**
	 * get request array
	 */
	private function getArrRequest(){
		
		$request = $_GET;
		if(!empty($_POST))
			$request = array_merge($request, $_POST);
		
		return($request);
	}
	
	
	
	/**
	 * get filters array from input
	 */
	private function getArrInputFilters(){
		
		if(!empty(self::$arrInputFiltersCache))
			return(self::$arrInputFiltersCache);
		
		$request = $this->getArrRequest();
		
		$strFilters = UniteFunctionsUC::getVal($request, "ucfilters");
		
		if(empty($strFilters))
			return(null);
		
		if(self::$showDebug == true){
			
			dmp("input filters found: $strFilters");
		}
		
		
		$strFilters = trim($strFilters);
			
		$arrFilters = explode(";", $strFilters);
		
		//fill the terms
		$arrTerms = array();
		
		foreach($arrFilters as $strFilter){
			
			$arrFilter = explode("~", $strFilter);
			
			if(count($arrFilter) != 2)
				continue;
			
			$key = $arrFilter[0];
			$strValues = $arrFilter[1];
			
			$arrVales = explode(".", $strValues);
			
			$type = self::TYPE_TERMS;
			
			switch($type){
				case self::TYPE_TERMS:
					$arrTerms[$key] = $arrVales;
				break;
			}
			
		}
		
		$arrOutput = array();
		
		if(!empty($arrTerms))
			$arrOutput[self::TYPE_TERMS] = $arrTerms;
		
		self::$arrInputFiltersCache = $arrOutput;
		
		return($arrOutput);
	}
	
	/**
	 * get input filters in assoc mode
	 */
	private function getInputFiltersAssoc(){
		
		if(!empty(self::$arrFiltersAssocCache))
			return(self::$arrFiltersAssocCache);
		
		$arrFilters = $this->getArrInputFilters();
		
		$output = array();
		
		$terms = UniteFunctionsUC::getVal($arrFilters, "terms");
		
		if(empty($terms))
			$terms = array();
		
		foreach($terms as $taxonomy=>$arrTermSlugs){
				
			foreach($arrTermSlugs as $slug){
				
				$key = "term_{$taxonomy}_{$slug}";
				
				$output[$key] = true;
				
			}
		}
		
		self::$arrFiltersAssocCache = $output;
		
		return($output);
	}
	
	
	/**
	 * get filters arguments
	 */
	public function getRequestFilters(){
		
		if(self::$filters !== null)
			return(self::$filters);
		
		self::$filters = array();
				
		$arrInputFilters = $this->getArrInputFilters();
				
		if(empty($arrInputFilters))
			return(self::$filters);
		
		$arrTerms = UniteFunctionsUC::getVal($arrInputFilters, self::TYPE_TERMS);
				
		if(empty($arrTerms))
			$arrTerms = array();

		//collect term filters
			
		$arrFilterTerms = array();
		
		foreach($arrTerms as $taxonomy=>$arrTerms){
			
			$prefix = "";
			if($taxonomy != "category")
				$prefix = $taxonomy."--";
			
			foreach($arrTerms as $term)
				$arrFilterTerms[] = $prefix.$term;
		}

		//put to output
		
		if(!empty($arrFilterTerms)){
			self::$filters["category"] = $arrFilterTerms;
			self::$filters["category_relation"] = "OR";
			
		}
		
		return(self::$filters);
	}
	
	
	/**
	 * get fitler url from the given slugs
	 */
	private function getUrlFilter_term($term, $taxonomyName){
		
		$key = "filter-term";
		
		$taxPrefix = $taxonomyName."--";
		
		if($taxonomyName == "category"){
			$taxPrefix = "";
			$key="filter-category";
		}
				
		$slug = $term->slug;

		$value = $taxPrefix.$slug;
		
		$urlAddition = "{$key}=".urlencode($value);
				
		$urlCurrent = GlobalsUC::$current_page_url;
				
		$url = UniteFunctionsUC::addUrlParams($urlCurrent, $urlAddition);
		
		return($url);
	}
	
	/**
	 * check if the term is acrive
	 */
	private function isTermActive($term, $arrActiveFilters = null){
		
		if(empty($term))
			return(false);
		
		if($arrActiveFilters === null)
			$arrActiveFilters = $this->getRequestFilters();
					
		if(empty($arrActiveFilters))
			return(false);
		
		$taxonomy = $term->taxonomy;
		
		$selectedTermID = UniteFunctionsUC::getVal($arrActiveFilters, $taxonomy);
		
		if(empty($selectedTermID))
			return(false);
			
		if($selectedTermID === $term->term_id)
			return(true);
			
		return(false);
	}
	
	
	private function _______WIDGET__________(){}
	
	
	/**
	 * include the filters js files
	 */
	private function includeJSFiles(){
		
		if(self::$isFilesAdded == true)
			return(false);
		
		$urlFiltersJS = GlobalsUC::$url_assets_libraries."filters/ue_filters.js";
		HelperUC::addScriptAbsoluteUrl($urlFiltersJS, "ue_filters");		
		
		
		self::$isFilesAdded = true;
	}
	
	
	/**
	 * put checkbox filters test
	 */
	public function putCheckboxFiltersTest($data){
		
		$arrActiveFilters = $this->getInputFiltersAssoc();
				
		$this->includeJSFiles();
		
		$taxonomy = UniteFunctionsUC::getVal($data, "taxonomy", "category");
		
		$terms = get_terms($taxonomy);
		
		if(empty($terms))
			return(false);
		
		$html = "";
		foreach($terms as $term){
			
			$arrTerm = (array)$term;
			
			$termID = UniteFunctionsUC::getVal($arrTerm, "term_id");
			$name = UniteFunctionsUC::getVal($arrTerm, "name");
			$slug = UniteFunctionsUC::getVal($arrTerm, "slug");
			$count = UniteFunctionsUC::getVal($arrTerm, "count");
			
			$activeKey = "term_{$taxonomy}_{$slug}";
			
			$addAttr = "";
			
			if(isset($arrActiveFilters[$activeKey])){
				$addAttr = " checked='checked' data-active='yes'";
			}
				
			$slug = htmlspecialchars($slug);
			
			$checkboxName = "ucfilter_term__{$taxonomy}--{$slug}";
			
			$html .= "<label class='ucfilters-label-checkbox'>$name ($count)
				<input type='checkbox' class='uc-listing-filter uc-filter-checkbox' 
					name='{$checkboxName}' 
					data-type='term' 
					data-taxonomy='{$taxonomy}' 
					data-term='{$slug}'
					{$addAttr}
				 >
			</label>";
			
		}
		
		echo $html;
	}
	
	/**
	 * get base page url
	 */
	private function getBasePageUrl(){
			
		$url = UniteFunctionsUC::getBaseUrl(GlobalsUC::$current_page_url);

		//strip pagination
		if(strpos($url, "/page/") !== false){
			$numPage = (get_query_var('paged')) ? get_query_var('paged') : 1;				
			$url = str_replace("/page/$numPage/", "/", $url);
			
		}
		
		return($url);
	}
	
	/**
	 * add widget variables
	 * uc_listing_addclass, uc_listing_attributes
	 *
	 */
	public function addWidgetFilterableVariables($data){
		
		$urlBase = $this->getBasePageUrl();
		
		$arrData = array();
		$arrData["urlbase"] = $urlBase;
		
		$strAttributes = UniteFunctionsUC::jsonEncodeForHtmlData($arrData,"ucfilters");
		
		$data["uc_listing_attributes"] = $strAttributes;
		$data["uc_listing_addclass"] = " uc-filterable-listing";
		
		return($data);
	}
	
	private function _______ARCHIVE_QUERY__________(){}
	
	
	/**
	 * modify post query
	 * Enter description here ...
	 */
	public function checkModifyMainQuery($query){
		
		if(is_single())
			return(false);
		
		$arrFilters = $this->getRequestFilters();
				
		if(empty($arrFilters))
			return(true);
		
		$args = UniteFunctionsWPUC::getPostsArgs($arrFilters, true);
		
		if(empty($args))
			return(false);
		
		$query->query_vars = array_merge($query->query_vars, $args);
		
	}
	
	
	/**
	 * init wordpress front filters
	 */
	public function initWPFrontFilters(){
		
		if(GlobalsUC::$inDev == false)
			return(false);
		
		add_action("parse_request", array($this, "checkModifyMainQuery"));
		
	}
	
	
private function _______TEMP__________(){}

	/**
	 * put ajax scripts
	 */
	private function putScriptsAjax(){
		
		?>
		
		<script>

/**
 * parse response html - get the body
 */
function parseResponseHtml(html){
	
	var objParsed = jQuery.parseHTML(html);
	
	var objBody = [];
	
	jQuery.each(objParsed, function(index){

		var item = objParsed[index];
		var type = typeof item;

		var tagName = item.tagName;
		if(!tagName)
			return(true);

		tagName = tagName.toLowerCase();

		switch(tagName){
			case "header":
			case "main":
			case "footer":
			break;
			default:
				return(true);
			break;
		}

		objBody.push(jQuery(item));
	});
	
	return(objBody);
}
		
/**
 * operate html response from ajax
 */
function operateHTMLResponse(html){

	var objBody = parseResponseHtml(html);

	var objPostList = jQuery(".uc_post_list");

	var objNewPostList = objBody[1].find(".uc_post_list");

	objPostList.html(objNewPostList.html());
	
	//var objBody = objDom.find("body");
	//trace(objDom);
	//trace(objBody);
	
}
		
/**
* on ajax filter click
*/
function onFilterAjaxClick(event){

	event.preventDefault();
	
	var objFilter = jQuery(this);

	var link = objFilter.prop("href");

	var ajaxSettings = {
		dataType:"html",
		complete:function(response){
			var responseText = response.responseText;
			if(responseText)
				operateHTMLResponse(responseText);
		}
	};
	
	jQuery.ajax(link, ajaxSettings);
		
}

jQuery(document).ready(function(){

	var objFilters = jQuery(".uc-filters .uc-filter-ajax");

	objFilters.click(onFilterAjaxClick);
	
});
		
		</script>
		
		<?php 
	}
	
	
}