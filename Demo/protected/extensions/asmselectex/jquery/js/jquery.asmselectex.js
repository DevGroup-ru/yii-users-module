/*
 * Alternate Select Multiple (asmSelect) 1.0.2 beta - jQuery Plugin
 * http://www.ryancramer.com/projects/asmselect/
 * 
 * Copyright (c) 2008 by Ryan Cramer - http://www.ryancramer.com
 * Updates by Mark Dickson (mark@sitesteaders.com) December 2008
 * 
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * Credits for the OptGroup support goes to: 
 * Google-Code User: http://code.google.com/u/ideaoforder/
 * (details: http://code.google.com/p/jquery-asmselect/issues/detail?id=8)
 *
 */

(function($) {
  $.tinysort.defaults.attr = "id";
	$.fn.asmSelectEx = function(customOptions) {

		var options = {

			listType: 'ol',						// Ordered list 'ol', or unordered list 'ul'
			sortable: false, 					// Should the list be sortable?
			highlight: false,					// Use the highlight feature? 
			animate: false,						// Animate the the adding/removing of items in the list?
			addItemTarget: 'bottom',				// Where to place new selected items in list: top or bottom
			hideWhenAdded: false,					// Hide the option when added to the list? works only in FF
			debugMode: false,					// Debug mode keeps original select visible 

			removeLabel: 'remove',					// Text used in the "remove" link
			highlightAddedLabel: 'Added: ',				// Text that precedes highlight of added item
			highlightRemovedLabel: 'Removed: ',			// Text that precedes highlight of removed item

			containerClass: 'asmContainer',				// Class for container that wraps this widget
			selectClass: 'asmSelect',				// Class for the newly created <select>
			optionDisabledClass: 'asmOptionDisabled',		// Class for items that are already selected / disabled
			listClass: 'asmList',					// Class for the list ($ol)
			listSortableClass: 'asmListSortable',			// Another class given to the list when it is sortable
			listItemClass: 'asmListItem',				// Class for the <li> list items
			listItemLabelClass: 'asmListItemLabel',			// Class for the label text that appears in list items
			removeClass: 'asmListItemRemove',			// Class given to the "remove" link
			highlightClass: 'asmHighlight',				// Class given to the highlight <span>

      originalChangeEvent: 'add' // Which function to call when the original <select> is changed
			};

		$.extend(options, customOptions); 

		return this.each(function(index) {
      index = $('.' + options.containerClass).length // This plays more nicely with livequry
			var $original = $(this); 				// the original select multiple
			var $container; 					// a container that is wrapped around our widget
			var $select; 						// the new select we have created
			var $ol; 						// the list that we are manipulating
			var buildingSelect = false; 				// is the new select being constructed right now?
      var doSort = false;    // should we sort?
			var ieClick = false;					// in IE, has a click event occurred? ignore if not
      var list_items = new Array(); // This records which items go in the list--to avoid duplicates if using optgroups

			function init() {
        if (options.sortable == true) doSort = true;

				// initialize the alternate select multiple

				$select = $("<select></select>")
					.addClass(options.selectClass)
					.attr('id', options.selectClass + index); 

				$selectRemoved = $("<select></select>"); 

				$ol = $("<" + options.listType + "></" + options.listType + ">")
					.addClass(options.listClass)
					.attr('id', options.listClass + index); 

				$container = $("<div></div>")
					.addClass(options.containerClass) 
					.attr('id', options.containerClass + index); 

				buildSelect();

				$select.change(selectChangeEvent)
					.click(selectClickEvent); 

        if (options.originalChangeEvent == 'reload') {
          $original.change(originalReload)
            .wrap($container).before($select).before($ol);
        } else {
          $original.change(originalAddItem)
            .wrap($container).before($select).before($ol);
        }
				

				if(options.sortable) makeSortable();

				if($.browser.msie) $ol.css('display', 'inline-block'); 
			}

			function makeSortable() {

				// make any items in the selected list sortable
				// requires jQuery UI sortables, draggables, droppables

        // This initializes the correct order, in the event that it's pre-specified
        $ol.children("li").each(function(n) {
          if($(this).is(".ui-sortable-helper")) return;
          $option = $('#' + $(this).attr('rel')); 
          $original.append($option); 
        }); 

				$ol.sortable({
					items: 'li.' + options.listItemClass,
					handle: '.' + options.listItemLabelClass,
					axis: 'y',
					update: function() {
						$(this).children("li").each(function(n) {
							if($(this).is(".ui-sortable-helper")) return;
							$option = $('#' + $(this).attr('rel')); 
							$original.append($option); 
						}); 
					}
				}).addClass(options.listSortableClass); 
			}

			function selectChangeEvent() {
				
				// an item has been selected on the regular select we created
				// check to make sure it's not an IE screwup, and add it to the list

				if($.browser.msie && $.browser.version < 7 && !ieClick) return;
				var id = $(this).children("option:selected").slice(0,1).attr('rel'); 
				addListItem(id); 	
				ieClick = false; 
			}

			function selectClickEvent() {

				// IE6 lets you scroll around in a select without it being pulled down
				// making sure a click preceded the change() event reduces the chance
				// if unintended items being added. there may be a better solution?

				ieClick = true; 
			}

			function originalReload() {

				// select or option change event manually triggered
				// on the original <select multiple>, so rebuild ours

				$select.empty();
				$ol.empty();
				buildSelect();
			}

      function originalAddItem() {
        buildingSelect = true;
        doSort = false;
        var $t = $original.children("option:last");
        var id;
        var parts = $original.children("option:first").attr("id").split('option');
        var new_id = parts[0] + 'option' + $original.find("option").length;
        if(!$t.attr('id')) $t.attr('id', new_id); 
        id = $t.attr('id'); 

        if (options.addItemTarget == 'top') {
          addListItem(id, position);
        } else {
          addListItem(id);
        }        
        addSelectOption(id, true);
      }

			function buildSelect() {

				// build or rebuild the new select that the user
				// will select items from

				buildingSelect = true; 

				// add a first option to be the home option / default selectLabel
				$select.prepend("<option>" + $original.attr('title') + "</option>"); 
        
        var group = 0;
        buildOptions($original, group);
        
        $original.children("optgroup").each(function(n) {
          group += 1;
          var $t = $(this);
          beginSelectOptgroup($t.attr('label'));
          buildOptions($t, group);
          endSelectOptgroup();
          group += 1;
        })

				if(!options.debugMode) $original.hide(); // IE6 requires this on every buildSelect()
				selectFirstItem();
				buildingSelect = false; 
			}
      
      function buildOptions(el, group) {
        el.children("option").each(function(n) {

          var $t = $(this); 
          var id; 

          if (group > 0) {
            group_text = 'Group' + group
          } else {
            group_text = ''
          }
          if(!$t.attr('id')) $t.attr('id', 'asm' + index + group_text + 'option' + n); 
          id = $t.attr('id'); 

          if($t.is(":selected")) {
            // !!!Not sure what will happen here if there isn't a value set!!!
            if (jQuery.inArray($t.attr('value'), list_items) == -1) {
              list_items.push($t.attr('value'));
              if ($t.attr('rel')) {
                position = $t.attr('rel').split('_')[1];
              } else {
                position = 0;
              }
              addListItem(id, position);
            }
            addSelectOption(id, true);            
          } else {
            addSelectOption(id); 
          }
        });      
      }

			function addSelectOption(optionId, disabled) {

				// add an <option> to the <select>
				// used only by buildOptions()

				if(disabled == undefined) var disabled = false; 

				var $O = $('#' + optionId); 
				var $option = $("<option>" + $O.text() + "</option>")
					.val($O.val())
					.attr('rel', optionId);

				if(disabled) disableSelectOption($option); 

				$select.append($option); 
			}

      function beginSelectOptgroup(label) {

        // add an <optgroup> to the <select>
        var $option = $("<optgroup label=\"" + label + "\">")
        //disableSelectOption($option); 

        $select.append($option); 
      }
      
      function endSelectOptgroup() {
        var $option = $("</optgroup>")
        $select.append($option);
      }

			function selectFirstItem() {

				// select the first item from the regular select that we created

				$select.children(":eq(0)").attr("selected", true); 
			}

			function disableSelectOption($option) {

				// make an option disabled, indicating that it's already been selected
				// because safari is the only browser that makes disabled items look 'disabled'
				// we apply a class that reproduces the disabled look in other browsers

				$option.addClass(options.optionDisabledClass)
					.attr("selected", false)
					.attr("disabled", true);

				if(options.hideWhenAdded) $option.hide();
				if($.browser.msie) $select.hide().show(); // this forces IE to update display
			}

			function enableSelectOption($option) {

				// given an already disabled select option, enable it

				$option.removeClass(options.optionDisabledClass)
					.attr("disabled", false);

				if(options.hideWhenAdded) $option.show();
				if($.browser.msie) $select.hide().show(); // this forces IE to update display
			}

			function addListItem(optionId, position) {

				// add a new item to the html list
				var $O = $('#' + optionId); 

				if(!$O) return; // this is the first item, selectLabel

				var $removeLink = $("<a></a>")
					.attr("href", "#")
					.addClass(options.removeClass)
					.prepend(options.removeLabel)
					.click(function() { 
						dropListItem($(this).parent('li').attr('rel')); 
						return false; 
					}); 

				var $itemLabel = $("<span></span>")
					.addClass(options.listItemLabelClass)
					.html($O.html()); 

				var $item = $("<li></li>")
					.attr('rel', optionId)
          .attr('id', $ol.attr('id') + '_' + position)
					.addClass(options.listItemClass)
					.append($itemLabel)
					.append($removeLink)
					.hide();

				if(!buildingSelect) {
					if($O.is(":selected")) return; // already have it
					$O.attr('selected', true); 
				}

        if((position == 0) || (options.addItemTarget == 'top' && !buildingSelect)) {
          $ol.prepend($item); 
          if(options.sortable) $original.prepend($O); 
        } else {
          $ol.append($item); 
          if(options.sortable) $original.append($O); 
        }

				addListItemShow($item); 

				disableSelectOption($("[rel=" + optionId + "]", $select));

				if(!buildingSelect) {
					setHighlight($item, options.highlightAddedLabel); 
					selectFirstItem();
					if(options.sortable) $ol.sortable("refresh");
				}
        if (doSort) $ol.children("li").tsort();
			}

			function addListItemShow($item) {

				// reveal the currently hidden item with optional animation
				// used only by addListItem()

				if(options.animate && !buildingSelect) {
					$item.animate({
						opacity: "show",
						height: "show"
					}, 100, "swing", function() { 
						$item.animate({
							height: "+=2px"
						}, 50, "swing", function() {
							$item.animate({
								height: "-=2px"
							}, 25, "swing"); 
						}); 
					}); 
				} else {
					$item.show();
				}
			}

			function dropListItem(optionId, highlightItem) {

				// remove an item from the html list

				if(highlightItem == undefined) var highlightItem = true; 
				var $O = $('#' + optionId); 

				$O.attr('selected', false); 
				$item = $ol.children("li[rel=" + optionId + "]");

				dropListItemHide($item); 
				enableSelectOption($("[rel=" + optionId + "]", options.removeWhenAdded ? $selectRemoved : $select));

				if(highlightItem) setHighlight($item, options.highlightRemovedLabel); 
				
			}

			function dropListItemHide($item) {

				// remove the currently visible item with optional animation
				// used only by dropListItem()

				if(options.animate && !buildingSelect) {

					$prevItem = $item.prev("li");

					$item.animate({
						opacity: "hide",
						height: "hide"
					}, 100, "linear", function() {
						$prevItem.animate({
							height: "-=2px"
						}, 50, "swing", function() {
							$prevItem.animate({
								height: "+=2px"
							}, 100, "swing"); 
						}); 
						$item.remove(); 
					}); 
					
				} else {
					$item.remove(); 
				}
			}

			function setHighlight($item, label) {

				// set the contents of the highlight area that appears
				// directly after the <select> single
				// fade it in quickly, then fade it out

				if(!options.highlight) return; 

				$select.next("#" + options.highlightClass + index).remove();

				var $highlight = $("<span></span>")
					.hide()
					.addClass(options.highlightClass)
					.attr('id', options.highlightClass + index)
					.html(label + $item.children("." + options.listItemLabelClass).slice(0,1).text()); 
					
				$select.after($highlight); 

				$highlight.fadeIn("fast", function() {
					setTimeout(function() { $highlight.fadeOut(2000); }, 50); 
				}); 
			}

			init();
		});
	};

})(jQuery); 
