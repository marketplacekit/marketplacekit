$(document).on('click', '[data-toggle-target]', function (e) {
	e.preventDefault();

	handleToggles($(this).data('toggle-target'));
});

function handleToggles(input) {
	if (!$.isArray(input)) {
		return handleToggles(input.split(' '));
	}

	$.each(input, function (i, item) {
		item = item.split(':');

		if (item.length === 1) {
			item.unshift('toggle');
		}

		toggles[item[0]].call(null, getElement(item[1]));
	});
}

function getElement(toggleName) {
	return $('[data-toggle-name~="' + toggleName + '"]');
}

var toggles = {
	toggle: function ($element) {
		$element.toggle();
	},
	hide: function ($element) {
		$element.hide();
	},
	show: function ($element) {
		$element.show();
	}
};

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
    (function ( $ ) {
    // Pass an object of key/vals to override
    $.fn.awesomeFormSerializer = function(overrides) {
        // Get the parameters as an array
        var newParams = this.serializeArray();

        for(var key in overrides) {
            var newVal = overrides[key]
            // Find and replace `content` if there
            for (index = 0; index < newParams.length; ++index) {
                if (newParams[index].name == key) {
                    newParams[index].value = newVal;
                    break;
                }
            }

            // Add it if it wasn't there
            if (index >= newParams.length) {
                newParams.push({
                    name: key,
                    value: newVal
                });
            }
        }

        // Convert to URL-encoded string
        return $.param(newParams);
    }
}( jQuery ));


$(document).on('turbolinks:render', function(){
    console.log("RENDER");
    main();
    Intercooler.processNodes($('body'));
    if($(document).data('ic-init')) return;
    $(document).data('ic-init', true);
});

$(document).on('turbolinks:click', function(){
    $(document).data('ic-init', null);
});

function main() {
    console.log("main");

    $('.pop').webuiPopover();

    $('#postModal').on('show.bs.modal', function (e) {
        Intercooler.triggerRequest('#post-wrapper');
        Intercooler.processNodes($('#post-wrapper'));
    });

    $(document).on('click','.InboxDirectMessage',function(e){
        e.preventDefault();
        $('#inboxModal').modal('show');

        $.ajax({
            url: $(this).data('url'),
            success: function(data) {
                $("#inbox-wrapper").html($(data).find("#inbox-main-outer").html());
                Intercooler.processNodes($('#inbox-wrapper'));
            },
            complete: function(data) {

            }
        });
    });
    alertify.reset();

    $('[data-toggle="tooltip"]').tooltip()
}

document.addEventListener("turbolinks:load", function() {
    delete lastCheck;
    console.log("turbolinks:load");
    main();

    if (typeof ga !== 'undefined' && jQuery.isFunction(ga)) {
        ga('send', 'pageview', window.location.pathname);
    }

    if($('div#review-rating').length)
        $('div#review-rating').raty({path: '/images/'});
});
