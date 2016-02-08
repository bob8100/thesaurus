<?php

	//check to make sure a valid word has been entered before attempting to call API
	if ( isset($_GET['searchWord'] ) && !empty( $_GET['searchWord']) && ( ctype_alpha($_GET['searchWord']) ) ) {

		//require the apiThesaurus class so can then call resultsThesaurus function
		require_once('../classes/apiThesaurus.php');

		$apiThesaurus = new apiThesaurus;

		//clean the string of markup tags and unnecessary characters
		$searchWord = trim( strip_tags($_GET['searchWord']) );

		//finally make a call to the API to search for synonyms
		$apiThesaurus->resultsThesaurus($searchWord);

	}