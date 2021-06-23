
function UEListingFilters(){
	
	var g_objFilters, g_objListing, g_listingData, g_urlBase;
	 
	var g_types = {
		CHECKBOX:"checkbox"
	};
	
	
	/**
	 * console log some string
	 */
	function trace(str){
		console.log(str);
	}
	
	/**
	 * get object property
	 */
	function getVal(obj, name, defaultValue){
		
		if(!defaultValue)
			var defaultValue = "";
		
		var val = "";
		
		if(!obj || typeof obj != "object")
			val = defaultValue;
		else if(obj.hasOwnProperty(name) == false){
			val = defaultValue;
		}else{
			val = obj[name];			
		}
		
		return(val);
	}
	
	
	/**
	 * get filter type
	 */
	function getFilterType(objFilter){
		
		if(objFilter.is(":checkbox"))
			return(g_types.CHECKBOX);
		
		return(null);
	}
	
	
	/**
	 * clear filter
	 */
	function clearFilter(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				objFilter.prop("checked", false);
			break;
		}
		
	}
	
	
	/**
	 * clear filters
	 */
	function clearFilters(checkActive){
		
		jQuery.each(g_objFilters,function(index, filter){
			
			var objFilter = jQuery(filter);
			
			if(checkActive == true){
				
				var isActive = objFilter.data("active");
				if(isActive == "yes")
					return(true);
			}
			
			clearFilter(objFilter);
			
		});
		
	}
	
	/**
	 * get filter data
	 */
	function getFilterData(objFilter){
		
		var objData = {};
		
		var type = getFilterType(objFilter);
		
		var type = objFilter.data("type");
		
		objData["type"] = type;
		
		switch(type){
			case "term":
				var taxonomy = objFilter.data("taxonomy");
				var term = objFilter.data("term");
				
				objData["taxonomy"] = taxonomy;
				objData["term"] = term;
			break;
			default:
				throw new Error("getFilterData error: wrong data type: "+type);
			break;
		}
		
		return(objData);
	}
	
	/**
	 * check if the filter selected
	 */
	function isFilterSelected(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				
				var isSelected = objFilter.is(":checked");
				
				return(isSelected);
				
			break;
			default:
				throw new Error("isFilterSelected error. wrong type: "+type);
			break;
		}
		
		
		return(false);
	}
	
	
	/**
	 * get all selected filters
	 */
	function getSelectedFilters(){
		
		var objSelected = [];
		
		jQuery.each(g_objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var isSelected = isFilterSelected(objFilter);
			
			if(isSelected == true)
				objSelected.push(objFilter);
			
		});
		
		
		return(objSelected);
	}
	
	 
	/**
	 * get filters data array
	 */
	function getArrFilterData(){
		
		var objFilters = getSelectedFilters();
		
		if(objFilters.length == 0)
			return([]);
		
		var arrData = [];
		
		jQuery.each(objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var objFilterData = getFilterData(objFilter);
			
			arrData.push(objFilterData);
		});
		
		return(arrData);
	}
	
	/**
	 * consolidate filters data
	 */
	function consolidateFiltersData(arrData){
		
		if(arrData.length == 0)
			return([]);
		
		//consolidate by taxonomies
		
		var objTax = {};
		
		jQuery.each(arrData, function(index, item){
			
			switch(item.type){
				case "term":
					
					var taxonomy = item.taxonomy;
					var term = item.term;
					
					if(objTax.hasOwnProperty(taxonomy) == false)
						objTax[taxonomy] = [];
					
					objTax[taxonomy].push(term);
					
				break;
				default:
					throw new Error("consolidateFiltersData error: wrong type: "+item.type);
				break;
			}
			
		});
		
		var arrConsolidated = {};
		arrConsolidated["terms"] = objTax;
		
		return(arrConsolidated);
	}
	
	/**
	 * build terms query
	 */
	function buildQuery_terms(objTax){
		
		var query = "";
		
		jQuery.each(objTax, function(taxonomy, arrTerms){
			
			var strTerms = arrTerms.join(".");
			if(!strTerms)
				return(true);

			//separator
			
			if(query)
				taxonomy += ";";
			
			//query
			
			query += taxonomy + "~" + strTerms;
		});
		
		return(query);
	}
	
	
	/**
	 * build url query from the filters
	 * example:
	 * ucfilters=product_cat~shoes,dress;cat~123,43;
	 */
	function buildUrlQuery(){
				
		var arrData = getArrFilterData();
		
		if(arrData.length == 0)
			return("");
		
		var queryFilters = "";
		
		var arrConsolidated = consolidateFiltersData(arrData);
		
		jQuery.each(arrConsolidated, function(type, objItem){
			
			switch(type){
				case "terms":
					var queryTerms = buildQuery_terms(objItem);
					
					if(queryFilters)
						queryFilters += ";";
					
					queryFilters += queryTerms;
				break;
			}
			
		});
		
		//return query
		
		var query = "ucfilters=" + queryFilters;
		
		return(query);
	}
	
	/**
	 * get redirect url
	 */
	function getRedirectUrl(query){
		
		if(!g_urlBase)
			throw new Error("getRedirectUrl error - empty url");
		
		var url = g_urlBase;
		
		if(!query)
			return(url);
		
		var posQ = url.indexOf("?");
		
		if(posQ == -1)
			url += "?";
		else
			url += "&";
		
		url += query;
		
		return(url);
	}
	
	
	/**
	 * on filters change - refresh the page with the new query
	 */
	function onFiltersChange(){
		
		var query = buildUrlQuery();
		
		var url = getRedirectUrl(query);
		
		if(!url)
			throw new error("onFiltersChange error - empty redirect url");
		
		location.href = url;
				
	}
	
	
	/**
	 * init events
	 */
	function initEvents(){
		
		var objCheckboxes = g_objFilters.filter("input[type=checkbox]");
		
		objCheckboxes.on("click", onFiltersChange);
		
	}
	
	
	/**
	 * init
	 */
	function init(){
		
		g_objFilters = jQuery(".uc-listing-filter");
		
		if(g_objFilters.length == 0){
			return(false);
		}
		
		//init the listing
		
		g_objListing = jQuery(".uc-filterable-listing");
		
		if(g_objListing.length == 0){
			trace("fitlers not loaded, no listing available on page");
			return(false);
		}
		
		//get first listing
		if(g_objListing.length > 1)
			g_objListing = jQuery(g_objListing[0]);
		
		g_listingData = g_objListing.data("ucfilters");
		if(!g_listingData)
			g_listingData = {};
		
		g_urlBase = getVal(g_listingData, "urlbase");
		
		if(!g_urlBase){
			trace("ue filters error - base url not inited");
			return(false);
		}
		
		trace("filters are active!");
		
		clearFilters(true);
		
		initEvents();
		
	}
	
	
	/**
	 * init the class
	 */
	function construct(){
		
		if(!jQuery){
			trace("Filters not loaded, jQuery not loaded");
			return(false);
		}
				
		jQuery("document").ready(init);
		
	}
	
	construct();
}

new UEListingFilters();

