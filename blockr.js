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
						var suggestionLink = $('<a href="#" data-object-id="'+element._id['$id']+'">'+element.name+'</a>').on("click", function(e) {
							e.preventDefault();
							if (objectContext=="projects") { 
								blockr.makeActive(objectContext, element._id['$id'], element.name);
								blockr.makeActive("clients", element.client._id['$id'], element.client.name);
							} else if (objectContext=="clients") {
								blockr.makeActive("clients", element._id['$id'], element.name);
								blockr.deactivate("projects");
							} else {
								blockr.makeActive("resources", element._id['$id'], element.name);
							}
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
		creator.find("input").val("");
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
			if (searchTrigger.siblings("div.objectDefinition").is(":visible")) {//hide the object builder by triggering a click from its trigger
				searchTrigger.siblings("a.new").trigger("click");
			}
			blockr.getSuggestions(searchTrigger.data("lookupHref"), searchTrigger.closest("div.manager").find("ul.suggestionList"));
		} else if (search.length<3) {
			return true;
		} else {
			if (searchTrigger.siblings("div.objectDefinition").is(":visible")) {
				searchTrigger.siblings("a.new").trigger("click");
			}
			blockr.getSuggestions(searchTrigger.data("lookupHref"), searchTrigger.closest("div.manager").find("ul.suggestionList"), search);
		}
	},

	makeActive : function(context, id, label) {
		window.blockr[context] = id;
		window.blockr[context+"Label"] = label;
		$('div.manager[data-type='+context+'] a.button').text(label).attr("data-context-id", id);

	},

	deactivate : function(context) {
		delete window.blockr[context];
		$('div[data-type='+context+'] a.button').text(context.substr(0, context.length-1));
	},

	saveProject : function(e) {
		e.preventDefault();
		var trigger = $(e.target);
		projectData = {
			"project[name]" : $('input[name="project[name]"]').val(),
			"project[settings][active]" : $('input[name="project[active]"]').is(":checked"),
			"project[client]" : $('input[name="project[client]"]').val()
		};
		$.ajax({
			url : trigger.data("saveHref"),
			dataType : "json",
			type : "post",
			data : $.param(projectData),
			success : function(output) {
				blockr.makeActive("projects", output._id['$id'], output.name);
				blockr.makeActive("clients", output.client._id['$id'], output.client.name);
				$('#projectManager a.new').trigger("click");
				$('#projectManager a.button').trigger("click");
			},
			error : function() {
				alert("Error saving project");
			}
		});
	},

	saveClient : function(e) {
		e.preventDefault();
		var trigger = $(e.target);
		clientData = {
			"client[name]" : $('input[name="client[name]"]').val(),
			"client[settings][active]" : $('input[name="client[settings][active]"]').is(":checked")
		};
		$.ajax({
			url : trigger.data("saveHref"),
			type : "post",
			dataType : "json",
			data : $.param(clientData),
			success : function(output) {
				blockr.makeActive("clients", output._id['$id'], output.name);
				blockr.deactivate("projects");
				$('#clientManager a.new').trigger("click");
				$('#clientManager a.button').trigger("click");
			},	
			error : function() {
				alert("error saving client");
			}
		})	
	},

	saveResource : function(e) {
		e.preventDefault();
		var trigger = $(e.target);
		resourceData = {
			"resource[name]" : $('input[name="resource[name]"]').val(),
			"resource[email]" : $('input[name="resources[email]"]').val(),
			"resource[settings][active]" : $('input[name="resource[settings][active]"]').is(":checked")
		};
		$.ajax({
			url : trigger.data("saveHref"),
			type : "post",
			dataType : "json",
			data : $.param(resourceData),
			success : function(output) {
				blockr.makeActive("resources", output._id['$id'], output.name);
				$('#resourceManager a.new').trigger("click");
				$('#resourceManager a.button').trigger("click");
			},	
			error : function() {
				alert("error saving resource");
			}
		})	
	},

	setBlock : function(e) {
		var block = $(e.target);
		var oldBlock = {};
		if (block.attr("data-id")) oldBlock._id = block.attr("data-id");
		if (block.attr("data-state")) oldBlock.state = block.attr("data-state");
		if (block.attr("data-resource")) oldBlock.resource = block.attr("data-resource");
		if (block.attr("data-client")) oldBlock.client = block.attr("data-client");
		if (block.attr("data-project")) oldBlock.project = block.attr("data-project");
		if (block.attr("data-timestamp")) oldBlock.timestamp = block.attr("data-timestamp");
		if (block.attr("data-session")) oldBlock.session = block.attr("data-session");

		var project = window.blockr.projects;
		var projectLabel = window.blockr.projectsLabel;
		var client = window.blockr.clients;
		var clientLabel = window.blockr.clientsLabel;
		var resource = block.data("resource")==undefined ? window.blockr.resources : block.data("resource");
		var resourceLabel = block.data("resourceLabel")==undefined ? window.blockr.resourcesLabel : block.data("resourceLabel");
		var label = block.find("span.label");

		if (project==undefined) {
			blockr.toast("Choose a project to allocate");
			return false;
		}
		if (resource==undefined) {
			blockr.toast("Select the resource to allocate");
			return false;
		}
		block.attr("data-resource", resource);
		block.attr("data-resource-label", window.block)
		block.attr("data-project", project);
		block.attr("data-project-label", projectLabel);
		block.attr("data-client", client);
		block.attr("data-client-label", clientLabel);
		block.removeClass(block.attr("data-state"));
		block.attr("data-state", "committed");
		block.addClass(block.attr("data-state"));
		label.text(window.blockr["projectsLabel"]);
		var newBlock = block.data();
		newBlock.resource = {
			"id" : resource,
			"label" : resourceLabel
		};		
		newBlock.project = {
			"id" : project,
			"label" : projectLabel
		};
		newBlock.client = {
			"id" : client,
			"label" : clientLabel
		};
		$.ajax({
			url : "/blocks/save",
			dataType : "json",
			type : "post",
			data : {
				"old" : oldBlock,
				"new" : newBlock
			},
			success : function(output) {
				savedBlock = output;
				console.log("before");
				console.log(oldBlock);
				console.log("After");
				console.log(savedBlock);
				block.attr("data-id", savedBlock._id.$id);
			}
		});
	},

	clearBlock : function(e) {
		var block = $(e.target);
		block.removeClass("tentative");
		block.removeClass("committed");
		block.removeAttr("data-resource");
		block.removeAttr("data-project");
		block.removeAttr("data-client");
		block.removeAttr("data-state");
		block.find("span.label").text("");
	},

	getNextState : function(currentState) {
		switch (currentState) {
			case "committed":
				return "tentative";
			break;
			case "tentative":
				return "";
			break;
			case "":
			case undefined:
			case "unassigned":
				return "committed";
		}
	},

	toast : function(error) {
		alert(error);
	}
	
}

//kick it off
$(document).ready(function() {
	$('div.manager>a.button').on("click", blockr.showSelectionPane);
	$('input.search').on("keyup", blockr.objectSearch);
	$('a.new').on("click", blockr.toggleObjectBuilder);
	$('#newProject button').on("click", blockr.saveProject);
	$('#newClient button').on("click", blockr.saveClient);
	$('#newResource button').on("click", blockr.saveResource);
	$('.session').on("click", blockr.setBlock).on("dblclick", blockr.clearBlock);
});