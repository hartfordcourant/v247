<?php
/* 
P2P CONTENT SERVICES WRAPPER FOR TRONC
AUTHOR: Danny Sanchez, dsanchez@sun-sentinel.com, 954-356-4818
This library requires an API key for Content Services.
Features include creating/deleting/updating content items and collections, as well as searching Tronc content.
*/
class P2PAPI {
	
	private $APIKey; //API Key for P2P/Content Services	
	private $P2PBaseURL = "https://content-api.p2p.edge.tribuneinteractive.com"; //Base URL for the Content Services API.
	private $APIDataFormat = "json";
	//Options for common include parameters: https://content-api.p2p.tribuneinteractive.com/docs/common_parameters
	private $option_GetGeocodes = 1;
	private $option_GetRelatedItems = 1;
	private $option_GetEmbeddedItems = 1;
	private $option_GetTopics = 1;
	private $option_GetPath = 1;
	private $option_GetPremiumSettings = 1;
	private $option_GetWebURL = 1;
	private $option_PlainText = 0; //Removes HTML from all text attributes that may contain HTML formatting in them.
	private $option_XHTML = 0; //Ensures all fields that contain HTML are formatted containing valid XHTML. This argument will also remove any tags that contain relative URLs.
	private $option_Compact = 0; //Removes nodes without values from the response data.
	private $option_MinimalReferences = 0; //Returns only minimal references to content items including the id, slug, title, last_modified_time, content_item_state_code, and content_item_type_code. Using this option will make results return much quicker and greatly reduce load on the system. Note, this parameter is ignored for all but the content items multi call.
	
	//End Options
	
	//##############################################################################################
	//BEGIN P2PAPI_SetDataFormat
	//Designates whether the API returns data in JSON or XML.
	public function P2PAPI_Option_Data_Format($dataFormat) {
		$dataFormat = strtolower($dataFormat);
		if ($dataFormat !== "json" && $dataFormat !== "xml") {
			throw new Exception("The data return format must be either \"JSON\" or \"XML\".");	
		}
		else {
			$this->APIDataFormat = $dataFormat;
		};		
	}
		
	//##############################################################################################
	//P2PAPI Constructor
	public function __construct($key, $dataFormat="json") {
		$this->APIKey = $key;
		$this->P2PAPI_Option_Data_Format($dataFormat);
	}
	//End constructor
	
	//##############################################################################################	
	//BEGIN callAPI() - Base function for calling the P2P API. Used by every method that interacts with the API.
	//Requires a subsection to the base P2P URL, a request method (i.e. POST/GET/DELETE). When POSTing to the API, it requires some kind of data. These parameters change based on the type of API call.
	private function CallAPI($P2PURLParameters, $requestMethod, $postData = NULL) {
		
		//Build the URL for call to the P2P API.
		$P2PURL = $this->P2PBaseURL . $P2PURLParameters;

		//Build the authentication array for CURLOPT_HTTPHEADER.
		$headr = array();
		$headr[] = 'Authorization: Bearer ' . $this->APIKey;
		$headr[] = 'Content-type: application/json';
		//$headr[] = 'Content-length: ' . strlen($data);
		//End authentication.

		//Initiate cURL.
		$ch = curl_init($P2PURL);
		if ($requestMethod == "POST" || $requestMethod == "PUT" ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_VERBOSE, 1);
		 		 
		$response = curl_exec($ch);	
	
		//DEBUGGING
		//echo $P2PURL . "<br><br>";
		
		return $response;
	}
	//END function CallAPI()
	
	
	//##############################################################################################	
	//BEGIN buildURLParameters() - Function that adds filter and common parameters to a URL that calls the API.
	//Call type changes the URL building logic to accommodate some custom calls.
	private function buildURLParameters($callType="none") {
		//Example URL parameters: "?include[]=related_items&include[]=premium_settings&include[]=embedded_items&include[]=content_topics&include[]=web_url&include[]=content_item_paths&include[]=geocodes_attributes";
		
		$urlParameters = "?";
				
		//Check each API option and add the URL parameter if set to 1 (true)
		if ($this->option_GetGeocodes == 1) {
			$urlParameters .= "include[]=geocodes&";	
		}
		if ($this->option_GetRelatedItems == 1) {
			$urlParameters .= "include[]=related_items&";		
		}
		if ($this->option_GetEmbeddedItems == 1) {
			$urlParameters .= "include[]=embedded_items&";		
		}				
		if ($this->option_GetPremiumSettings == 1) {
			$urlParameters .= "include[]=premium_settings&";		
		}
		if ($this->option_GetTopics == 1) {
			$urlParameters .= "include[]=content_topics&";		
		}
		if ($this->option_GetPath == 1) {
			$urlParameters .= "include[]=content_item_paths&";		
		}
		if ($this->option_GetWebURL == 1) {
			$urlParameters .= "include[]=web_url&";		
		}
		if ($this->option_PlainText == 1) {
			$urlParameters .= "plaintext=true&";		
		}
		if ($this->option_XHTML == 1) {
			$urlParameters .= "xhtml=true&";
		}
		if ($this->option_Compact == 1) {
			$urlParameters .= "compact=true&";
		}
		
		//Check that the kind of API call is a multiple content item call.
		if($callType == "multiple-content-items" && $this->option_MinimalReferences == 1) {
			$urlParameters .= "compact=true&";					
		}
			
		//Strip off trailing apostrophes or question marks
		$urlParameters = rtrim(rtrim($urlParameters, "&"), "?");
		
		//DEBUGGING
		//echo $urlParameters;
		
		return $urlParameters;	
	}	
	//END function CallAPI()
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetGeocodes(int 1/0)
	//Set whether to get geocodes or not. 1 == yes, 0 == no.
	public function P2PAPI_Option_GetGeocodes($getGeos) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getGeos == 1 || $getGeos == 0) { 
			$this->option_GetGeocodes = $getGeos;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetGeocodes() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetGeocodes
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetRelatedItems(int 1 or 0)
	//Set whether to get related items or not.
	public function P2PAPI_Option_GetRelatedItems($getRelates) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getRelates == 1 || $getRelates == 0) { 
			$this->option_GetRelatedItems = $getRelates;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetRelatedItems() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetRelatedItems
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetEmbeddedItems(int 1 or 0)
	//Set whether to get embedded items or not.
	public function P2PAPI_Option_GetEmbeddedItems($getEmbeds) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getEmbeds == 1 || $getEmbeds == 0) { 
			$this->option_GetEmbeddedItems = $getEmbeds;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetEmbeddedItems() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetEmbeddedItems
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetTopics(int 1 or 0)
	//Set whether to get topics or not.
	public function P2PAPI_Option_GetTopics($getTopics) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getTopics == 1 || $getTopics == 0) { 
			$this->option_GetTopics = $getTopics;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetTopics() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetTopics
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetPaths(int 1 or 0)
	//Set whether to get geocodes or not.
	public function P2PAPI_Option_GetPaths($getPaths) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getPaths == 1 || $getPaths == 0) { 
			$this->option_GetTopics = $getPaths;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetPaths() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetPaths
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetPremiumSettings(int 1 or 0)
	//Set whether to get geocodes or not.
	public function P2PAPI_Option_GetPremiumSettings($getPremiums) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getPremiums == 1 || $getPremiums == 0) { 
			$this->option_GetPremiumSettings = $getPremiums;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetPaths() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetPremiumSettings
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetWebURL(int 1/0)
	//Set whether to get the full live URL of a content item or not. 1 == yes, 0 == no.
	public function P2PAPI_Option_GetWebURL($getWebLink) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($getWebLink == 1 || $getWebLink == 0) { 
			$this->option_GetWebURL = $getWebLink;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_GetWebURL() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetWebURL
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_GetCompact(int 1/0)
	//Set whether to get the compact version of the content item details or not. 1 == yes, 0 == no.
	public function P2PAPI_Option_Compact($compact) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($compact == 1 || $compact == 0) { 
			$this->option_Compact = $compact;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_Compact() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_GetCompact
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_XHTML(int 1/0)
	//Set whether to get ensure fields contain valid XHTML and strip relative URLs. 1 == yes, 0 == no.
	public function P2PAPI_Option_XHTML($xhtml) {
		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($xhtml == 1 || $xhtml == 0) { 
			$this->option_XHTML = $xhtml;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_XHTML() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_XHTML
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_MinimalReferences(int 1/0)
	//Set whether to get minimal references to content items or not. 1 == yes, 0 == no.
	public function P2PAPI_Option_MinimalReferences($refs) {
		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($refs == 1 || $refs == 0) { 
			$this->option_MinimalReferences = $refs;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_MinimalReferences() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_MinimalReferences
	
	//##############################################################################################
	//BEGIN - P2PAPI_Option_PlainText(int 1/0)
	//Set whether to get minimal references to content items or not. 1 == yes, 0 == no.
	public function P2PAPI_Option_PlainText($plainText) {
		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if($plainText == 1 || $plainText == 0) { 
			$this->option_PlainText = $plainText;
		}	
		else {
			throw new Exception("Value for P2PAPI_Option_PlainText() must be 1 for true or 0 for false."); 
		};	
	}
	//END function P2PAPI_Option_PlainText
	

	//##############################################################################################
	//BEGIN - P2PAPI_ShowContentItem($slug)
	//Show the details of an individual content item.
	public function P2PAPI_ShowContentItem($slug) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if(!is_string($slug) && !is_int($slug)) { 
			throw new Exception("Value for P2PAPI_ShowContentItem() must be a slug string or an item ID integer."); 
		}	
		else {
			$P2PRequestURL = "/content_items/" . $slug . "." . $this->APIDataFormat . $this->buildURLParameters();

			$item = $this->CallAPI($P2PRequestURL, "GET");

			//return $this->CallAPI($P2PRequestURL, "GET"); //Call the API
			return json_decode($item,true);
		};	
	}
	//END function P2PAPI_ShowContentItem
	
	//##############################################################################################
	//BEGIN - P2PAPI_ShowMultipleContentItems($contentItems)
	//Shows the details of multiple content item. Accepts either an array that will be converted to JSON or a string of raw JSON/XML.
	public function P2PAPI_ShowMultipleContentItems($contentItems) {
		
		//If it's an array, change it to JSON.
		if(is_array($contentItems)) {
			$contentItems = json_encode($contentItems, JSON_UNESCAPED_SLASHES);
		};
		
		$P2PRequestURL = "/content_items/multi." . $this->APIDataFormat . $this->buildURLParameters("multiple-content-items");
		
		return $this->CallAPI($P2PRequestURL, "POST", $contentItems); //Call the API
	}
	//END function P2PAPI_ShowMultipleContentItems
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_ShowColumns($slug)
	//Show the indiivdual columns associated with a columnist content item.
	public function P2PAPI_ShowColumns($slug) {

		//Check that a non-string or non-integer -- like an array -- wasn't sent.
		if(!is_string($slug) && !is_int($slug)) { 
			throw new Exception("Value for P2PAPI_ShowColumns() must be a slug string or an item ID integer."); 
		}	
		else {
			$P2PRequestURL = "/content_items/" . $slug ."/columns";
			if ($this->APIDataFormat == "xml") {
				$P2PRequestURL .= ".xml";				
			};
			$P2PRequestURL .= $this->buildURLParameters();

			return $this->CallAPI($P2PRequestURL, "GET"); //Call the API
		};	
	}
	//END function P2PAPI_ShowColumns
	
		//##############################################################################################
	//BEGIN - P2PAPI_ShowExternalBlogPosts($blogCode)
	//Show the indiivdual external blog posts associated with an externally hosted blog.
	public function P2PAPI_ShowExternalBlogPosts($blogCode) {

		//Check that a non-integer -- like an array or slug -- wasn't sent.
		if(!is_int($blogCode)) { 
			throw new Exception("Value for P2PAPI_ShowColumns() must be a slug string or an item ID integer."); 
		}
		else {
			$P2PRequestURL = "/content_items/" . $blogCode ."/external_blog_posts";
			if ($this->APIDataFormat== "xml") {
				$P2PRequestURL .= ".xml";				
			};
			$P2PRequestURL .= $this->buildURLParameters();

			return $this->CallAPI($P2PRequestURL, "GET"); //Call the API
		};	
	}
	//END function P2PAPI_ShowColumns
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_CreateContentItem($slug)
	//Delete an individual content item.
	public function P2PAPI_CreateContentItem($createData) {
		
		//If it's an array, change it to JSON.
		if(is_array($createData)) {
			$createData = json_encode($createData, JSON_UNESCAPED_SLASHES);
		};

		//Check that a non-string, like an array, wasn't sent.
		$P2PRequestURL = "/content_items." . $this->APIDataFormat;

		return $this->CallAPI($P2PRequestURL, "POST", $createData); //Call the API			
	}
	//END function P2PAPI_CreateContentItem
	
	//##############################################################################################
	//BEGIN - P2PAPI_UpdateContentItem($slug)
	//Update an individual content item.
	public function P2PAPI_UpdateContentItem($slug, $updateData) {

		//If it's an array, change it to JSON.
		if(is_array($updateData)) {
			$updateData = json_encode($updateData, JSON_UNESCAPED_SLASHES);
		};
	
		//Check that a non-string or item id -- like an array -- wasn't sent.
		if(!is_string($slug) && !is_int($slug)) { 
			throw new Exception("First value for P2PAPI_UpdateContentItem() must be a slug string or an item ID integer."); 
		}
		else {
			$P2PRequestURL = "/content_items/" . $slug . "." . $this->APIDataFormat;

			return $this->CallAPI($P2PRequestURL, "PUT", $updateData); //Call the API.
						
			echo $updateData;
		};	
	}
	//END function P2PAPI_UpdateContentItem
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_Search($urlParameters)
	//Delete an individual content item.
	public function P2PAPI_Search($userUrlParameters) {
		
		//Check that a non-string or item ID -- like an array -- wasn't sent.
		if(!is_string($userUrlParameters) && !is_array($userUrlParameters)) { 
			throw new Exception("Value for P2PAPI_Search() must be a string of URL parameters or an array."); 
		}
		else {	
			//Change the API request type based on if user is sending a string to tack onto the call URL or is POSTing an array with the parameters.
			if(is_string($userUrlParameters)) {
				$requestType = "GET";			
						
				$userUrlParameters = rtrim(rtrim(ltrim(ltrim($userUrlParameters, "?"), "&"), "?"), "&"); //Strip off left and right question marks or apostrophes so we can just tack it on cleanly no matter how user constructs his query.
					
				$urlParameters = $this->buildURLParameters(); //Get the systemwide parameters.
					
				//If everything is set to false in the system, add a question mark and just use what the user sent.
				if ($urlParameters == "") {
					$urlParameters = "?" . $userUrlParameters;
				}
				else {
					//If options are sent, append the user's URL string.
					$urlParameters .= "&" . $userUrlParameters;
				}
				
				$P2PRequestURL = "/content_items/search." . $this->APIDataFormat . $urlParameters; //Now build the whole URL.
				
				return $this->CallAPI($P2PRequestURL, $requestType); //Call the API		
			}			
			elseif (is_array($userUrlParameters)) {
				$requestType = "POST";
				
				$jsonData = json_encode($userUrlParameters);
				
				$P2PRequestURL = "/content_items/search." . $this->APIDataFormat . $this->buildURLParameters();
				
				return $this->CallAPI($P2PRequestURL, $requestType, $jsonData); //Call the API
			};

			//Now attach the URL parameters. This combines the user's search options with the other options used across the class, such as data type returned, whether to return certain fields or not, etc.
			echo $P2PRequestURL . "<br>";
		};	
	}
	//END function P2PAPI_Search
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_DeleteContentItem($slug)
	//Delete an individual content item.
	public function P2PAPI_DeleteContentItem($slug) {

		//Check that a non-string or item ID -- like an array -- wasn't sent.
		if(!is_string($slug) && !is_int($slug)) { 
			throw new Exception("Value for P2PAPI_DeleteContentItem() must be a slug string or an item ID integer."); 
		}	
		else {
			$P2PRequestURL = "/content_items/" . $slug . "." . $this->APIDataFormat;

			return $this->CallAPI($P2PRequestURL, "DELETE"); //Call the API
		};	
	}
	//END function P2PAPI_DeleteContentItem
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_DeleteCollection($collectionCode)
	//Delete an individual content item.
	public function P2PAPI_DeleteCollection($collectionCode) {

		//Check that a non-string or item ID -- like an array -- wasn't sent.
		if(!is_string($collectionCode)) { 
			throw new Exception("Value for P2PAPI_DeleteCollection() must be a collection code string."); 
		}	
		else {
			$P2PRequestURL = "/collections/" . $collectionCode . "." . $this->APIDataFormat;

			return $this->CallAPI($P2PRequestURL, "DELETE"); //Call the API
		};	
	}
	//END function P2PAPI_DeleteCollection
	
	
	//##############################################################################################
	//BEGIN - P2PAPI_ShowCollectionLayout($collectionCode)
	//Delete an individual content item.
	public function P2PAPI_ShowCollectionLayout($collectionCode) {

		//Check that a non-string or item ID -- like an array -- wasn't sent.
		if(!is_string($collectionCode)) { 
			throw new Exception("Value for P2PAPI_ShowCollectionLayout() must be a collection code string."); 
		}	
		else {
			$P2PRequestURL = "/collections/" . $collectionCode . "/active_layout." . $this->APIDataFormat . $this->buildURLParameters() . "&include[]=items";

			//return $this->CallAPI($P2PRequestURL, "GET"); //Call the API
			$item = $this->CallAPI($P2PRequestURL, "GET");

			//return $this->CallAPI($P2PRequestURL, "GET"); //Call the API
			return json_decode($item,true);
		};	
	}
	//END function P2PAPI_ShowCollectionLayout
	

	//##############################################################################################
	//BEGIN - CheckForError($APIresponse)
	//Parse the response of the API to check whether there was an error code in the header.
	function P2PAPI_CheckForError($APIresponse) {

	}
	/*
	 * Push a p2p content item
	 * @param $item slug of the content item
	 * @return $json json of content item
	 */
	public function pushContentItem($slug, $content){

		// p2p api location of item to update
		$P2Purl = 'https://content-api.p2p.tribuneinteractive.com/content_items/' . $slug . '.json';

		// update body of array 
		$data = array( 'content_item' => array(
			'body' => $content
			)
		);
		$data_string = json_encode($data);

		// Build the authentication array for CURLOPT_HTTPHEADER.
		$headr = array();
		$headr[] = 'Authorization: Bearer ' . P2P_TOKEN;
		$headr[] = 'Content-type: application/json';
		// End authentication.

		// Initiate cURL.
		$ch = curl_init($P2Purl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
		 
		$response = curl_exec($ch);

		if((string)$response == ''){
			echo '<div id="update"><h1>Updated ' . $slug . '</h1>';
			date_default_timezone_set('EST');
			echo '<p>' . date('F j, Y, g:i a') . '</p></div>';
			echo $content;
		}
		else{
			echo 'Error updating' . $slug . 'please try again.';
		}
		// End cURL call.*/
	}
	//END class P2PAPI
}
?>