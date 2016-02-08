<?php

	/**
	 * Class: 				API
	 * Description: 		Class for interacting with the Altervista Thesaurus API
	 *
	 * Coding standards:	http://pear.php.net/manual/en/standards.php
	 *
	 * @author Joseph Eccles
	 * @email joseph.eccles@gmail.com
	 */

	class apiThesaurus
	{ 

		protected $apiKey 		= "kvADKErqMhBz9kqE7xGb";
		protected $endPoint 	= "http://thesaurus.altervista.org/thesaurus/v1";
		protected $language 	= "en_US"; //you can use: en_US, es_ES, de_DE, fr_FR, it_IT

		function __construct(){

		}

		protected function searchThesaurus($searchWord) {

			/*
			* Function: searchThesaurus.
			* Purpose: 	Takes in a word value then connects to the API and brings back relevant results.
			* Returns: 	Array of JSON results.
			*/

			$apiResponseArray 	= array();
			$curlInit 			= curl_init();

			//Note: change this to use setters/getters
			$endPoint 	= $this->endPoint;
			$language 	= $this->language;
			$apiKey 	= $this->apiKey;
			
			curl_setopt($curlInit, CURLOPT_URL, "$endPoint?word=".urlencode($searchWord)."&language=$language&key=$apiKey&output=json"); 
			curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
			
			$info = curl_getinfo($curlInit);

			$apiResponseArray['data'] = curl_exec($curlInit);
			$apiResponseArray['info'] = curl_getinfo($curlInit);
			
			curl_close($curlInit);

			return $apiResponseArray;

		}

		public function resultsThesaurus($searchWord) {

			/*
			* Function: resultsThesaurus.
			* Purpose: 	Calls the searchThesaurus function and loops through the JSON and inserts synonyms into array.
			* Returns: 	Array of results which can then be outputted in whichever format is preferred e.g. HTML list, HTML table etc.
			* Sample Results: 
			*	Array
			*	(
			*		[0] => Array
			*			(
			*				[1] => (noun)
			*				[2] => order|war (antonym)
			*			)
			*
			*		[1] => Array
			*			(
			*				[1] => (noun)
			*				[2] => harmony|concord|concordance
			*			)
			*	)
			*/

			$searchResultsArray		= $this->searchThesaurus($searchWord);
			$info 					= $searchResultsArray['info'];
			$data 					= $searchResultsArray['data'];
			$resultsArray 			= array();

			if ( $info['http_code'] == 200 ) { //api call was successful so we look at the results
			
				$result 			= json_decode($data, true);
				$resultsCount 		= count($result["response"]);
				$count 				= 0;

				if ( $resultsCount > 0 ) { //if at least one synonym is returned then insert into array
				
					foreach ( $result["response"] as $value ) {

						$resultsArray[$count][1] = $value["list"]["category"];
						$resultsArray[$count][2] = $value["list"]["synonyms"];

						$count++;
					
					}

				}

			}

			echo json_encode($resultsArray);

		}


	}//end class