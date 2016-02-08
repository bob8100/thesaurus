$(document).ready(function() {

	function searchThesaurus(event){

		//cache selectors
		var $searchWord 	= $("input[type='text']"),
			$resultsPanel 	= $("div.panel");

		if ( $searchWord.val() != '' ) {

			var searchWord = 'searchWord=' + $searchWord.val();

			//make an ajax call to our php file 'ajax.php' which will query the Thesaurus API and return the results in JSON format

			$.ajax({
	  			
	  			dataType: "GET",
	  			data: searchWord,
	  			dataType: "json",
	  			url: "/thesaurus/app/ajax/ajax.php",

	  			beforeSend : function() {

	  				//disable the submit button
					$(this).attr("disabled","disabled");
			
					//disable all inputs while processing request
					$(":input").attr("disabled","disabled");

				},
				
				success: function(response) {

					//response is returned in JSON format so need to loop over it and format it for outputting as a list

					var synonymList = '';

					for( var index in response ) {
						
						var category 	= response[index][1];
						var synonym 	= response[index][2];

						synonymList = synonymList + '<li><em>'+ category + '.</em> Synonyms: <strong>' + synonym + '</strong></li>';

					}

					var outputResults = '<ul>' + synonymList + '</ul>';

					$resultsPanel.html(outputResults);
					$resultsPanel.show();

				},
				
				complete: function(response) {

					//enable the submit button
					$(this).removeAttr("disabled");
			
					//enable all inputs while processing request
					$(":input").removeAttr("disabled");

				},
				
				error: function(response) {
					
				}
	  
	  		});

	  	}
		
	}

	//event listeners
	$("#search-button").on("click", searchThesaurus);

 }); // end document ready