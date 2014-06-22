var blockr = {
	
	showSelectionPane : function(e) {
		e.preventDefault();
		var trigger = $(e.target);
		var objectType = trigger.closest("div.manager").data("type");
		var selectionPane = trigger.closest("div.manager").find("div.selection:first");
		selectionPane.find("input.search").val("");
		$('div.selection:visible').not(selectionPane).slideUp("fast");
		selectionPane.slideToggle("fast", function() {
			if (selectionPane.is(":visible")) {
				blockr.getSuggestions(trigger.attr("href"), selectionPane.find("ul"));
			}
		});
	},

	getSuggestions : function(lookupURL, suggestionList, search) {
		//make sure we have a context for what kind of object we're dealing with
		var objectContext = suggestionList.data("type");
		//clean the list and restore the loading graphic
		suggestionList.find("li").remove();
		suggestionList.append($('<li class="placeholder">getting suggestions...</li>'));
		//go to the server for the suggestions
		searchData = search==undefined ? null : "search="+encodeURIComponent(search);
		$.ajax({
			url : lookupURL, 
			dataType : "json",
			type : "post",
			data : searchData,
			success : function(output) {
				if (output) {
					suggestionList.find("li.placeholder").remove();
					$.each(output, function(index, element) {
						var suggestion = $('<li>');
						var suggestionLink = $('<a href="#" data-object-id="'+index+'">'+element+'</a>').on("click", function(e) {
							e.preventDefault();
							blockr.makeActive(objectContext, index, element);
							suggestionList.closest("div.manager").find("div.selection").slideUp("fast");
						});
						suggestion.append(suggestionLink);
						suggestionList.append(suggestion);
					})
				}
			}
		});
	},

	toggleObjectBuilder : function(e) {
		e.preventDefault();
		var trigger = $(e.target);
		var creator = trigger.closest("div.selection").find("div.objectDefinition");
		creator.slideToggle("fast", function() {
			if ($(this).is(":visible")) {
				trigger.closest("div.selection").find("ul.suggestionList").slideUp("fast");
				trigger.text("-");
			} else {
				trigger.closest("div.selection").find("ul.suggestionList").slideDown("fast");
				trigger.text("+");
			}
		});
	},

	objectSearch : function(e) {
		var searchTrigger = $(e.target);
		var search = searchTrigger.val();
		if (search.length==0) {
			blockr.getSuggestions(searchTrigger.data("lookupHref"), searchTrigger.closest("div.manager").find("ul.suggestionList"));
		} else if (search.length<3) {
			return true;
		} else {
			blockr.getSuggestions(searchTrigger.data("lookupHref"), searchTrigger.closest("div.manager").find("ul.suggestionList"), search);
		}
	},

	makeActive : function(context, id, label) {
		window[context] = id;
		$('div.manager[data-type='+context+'] a.button').text(label).attr("data-context-id", id);

	}
	




}

//kick it off
$(document).ready(function() {
	$('div.manager>a.button').on("click", blockr.showSelectionPane);
	$('input.search').on("keyup", blockr.objectSearch);
	$('a.new').on("click", blockr.toggleObjectBuilder);
});