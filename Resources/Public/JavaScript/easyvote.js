$(function() {
	var $body = $('body');

	// unwrapInner function
	// http://wowmotty.blogspot.com/2012/07/jquery-unwrapinner.html
	jQuery.fn.extend({
		unwrapInner: function(selector) {
			return this.each(function() {
				var t = this,
					c = $(t).children(selector);
				if (c.length === 1) {
					c.contents().appendTo(t);
					c.remove();
				}
			});
		}
	});

	/* Gegenvorschlag navigation */
	$('a#goToGegenvorschlag').click(function(e) {
		var jumpToId = $(this).closest('div.abstimmungsvorlage').next().attr('id');
		Easyvote.scrollToElement('#' + jumpToId);
		e.stopPropagation();
	});
	$('a#goToHauptvorlage').click(function(e) {
		var jumpToId = $(this).closest('div.abstimmungsvorlage').prev().attr('id');
		Easyvote.scrollToElement('#' + jumpToId);
		e.stopPropagation();
	});

	/* Toggle sections in a voting proposal */
	$body.on('click', '.toggle-trigger', function(e) {
		Easyvote.toggleBlock(this);
		EasyvoteGeneral.bindImageLazyLoading();
		e.stopPropagation();
	});

	/* Initialize votings */
	if ($('#votingsDashboard').length > 0) {
		Easyvote.initializeVotings();
	}

	/* Initialize infinite scrolling for archive */
	$('.searchResults').jscroll({
		autoTrigger: false,
		nextSelector: 'a.votingsearch-next',
		contentSelector: '.searchResults',
		loadingHtml: '<div class="records-loading">laden...</div>',
		refresh: true
	});

	/* Cancel election supporters filter */
	$body.on('click', '#cancelElectionSupportersFilter', function() {
		var $electionSupportersFilter = $('#electionSupportersFilter');
		$electionSupportersFilter.trigger('reset');
		$electionSupportersFilter.find('.citySelection').select2('val', '');
		$electionSupportersFilter.trigger('submit');
	});

	/* Cancel election supporters filter */
	$body.on('mouseenter mouseleave', '.electionsupporter-unfollow', function(e) {
		var $this = $(this);
		if (e.type == 'mouseenter') {
			$this.text($this.data('label-alt'));
		} else {
			$this.text($this.data('label-original'));
		}
	});

	$body.on('click', '.electionsupporter-follow-notAuthenticated', function(e) {
		e.preventDefault();
		$('.login-link').trigger('click');
	});

});

var Easyvote = {
	/* tooltips */
	bindToolTips: function() {
		if (Modernizr.touch) {
			return true;
		};
		$('.hasTooltip').each(function() {
			$(this).qtip({
				content: {
					text: $(this).next('div')
				},
				hide: {
					delay: 200,
					fixed: true
				},
				show: {
					solo: true
				},
				style: {
					classes: 'qtip-easyvote qtip-shadow qtip-easyvote-narrow'
				}
			});
		});
	},

	/* Modals */
	bindModals: function() {
		$body.on('click', '.hasModal', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var requestedLink = $(e.target).attr('href');
			var $modalContentContainer = $(this).next('div');
			$modalContentContainer.addClass('modal').css('visibility', 'visible').wrapInner('<div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body"></div></div></div>');
			$modalContentContainer.modal('show');

			$body.on('click', 'button.button-confirm', function(e) {
				e.preventDefault();
				window.location = requestedLink;
			});

			$('button.button-returntrue', $modalContentContainer).click(function (e) {
				var $button = $(e.target);
				var selection = $button.attr('data-selection');
				// return to the callback function, pass possible selection
				callback(selection);
				$modalContentContainer.modal('hide').css('visibility', 'hidden').unwrapInner().unwrapInner().unwrapInner();
			});

			$body.on('click', 'a.modal-close', function(e) {
				$modalContentContainer.modal('hide').css('visibility', 'hidden').unwrapInner().unwrapInner().unwrapInner();
			});

			$body.on('click', 'button.button-cancel', function(e) {
				e.preventDefault();
				$modalContentContainer.modal('hide').css('visibility', 'hidden').unwrapInner().unwrapInner().unwrapInner();
			});

			$body.on('hidden.bs.modal', $modalContentContainer, function (e) {
				$modalContentContainer.modal('hide').css('visibility', 'hidden').unwrapInner().unwrapInner().unwrapInner();
			})

		});
	},

	/* File Reader */
	readFile: function(input, targetSelector) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(targetSelector).attr('src', e.target.result);
			};
			reader.readAsDataURL(input.files[0]);
		}
	},
	/* Display a passed modal */
	displayModal: function(message, callback) {
		var $flashMessageContainer = $('#flashMessageContainer');
		$flashMessageContainer.html('<div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-body"><a class="pull-right modal-close" aria-label="schliessen"><i class="evicon-cancel"></i></a>' + message + '</div></div></div>').addClass('modal');

		$flashMessageContainer.modal('show');

		$('button.button-confirm', $flashMessageContainer).click(function(e) {
			window.location = requestedLink;
		});
		$('button.button-returntrue', $flashMessageContainer).click(function(e) {
			var $button = $(e.target);
			var selection = $button.attr('data-selection');
			// return to the callback function, pass possible selection
			callback(selection);
			$flashMessageContainer.modal('hide');
		});
		$('a.modal-close', $flashMessageContainer).click(function(e) {
			$flashMessageContainer.modal('hide');
		});
		$('button.button-cancel', $flashMessageContainer).click(function(e) {
			$flashMessageContainer.modal('hide');
		});
	},

	/* Postal code selection for forms */
	bindPostalCodeSelection: function() {
		var $postalCodeSelector = $(".citySelection");
		$postalCodeSelector.select2({
			placeholder: EasyvoteLabels.enterZipOrCity,
			minimumInputLength: 2,
			ajax: {
				url: '/?eID=easyvote_cityselection',
				dataType: 'json',
				data: function (term, page) {
					return {
						q: term // search term
					};
				},
				results: function (data, page) {
					return {results: data.results};
				}
			},
			initSelection: function (element, callback) {
				//callback({ id: initialValue, text: initialValue });
			},
			dropdownCssClass: "bigdrop",
			escapeMarkup: function (m) { return m; }
		}).on('change', function(e) {
			var $this = $(this);
			var data = $this.select2('data');
			var selectedCityName = data.postalCode + ' ' + data.city;
			$this.closest('.form-group').find('.cityOutput').val(selectedCityName);
		});
	},

	scrollToElement: function(elementId) {
		$('html, body').animate({
			scrollTop: $(elementId).offset().top
		}, 1000);
	},

	initializeVotings: function() {
		if (typeof votingProposalId != 'undefined') {
			Easyvote.openVoting(votingProposalId);
		} else {
			var firstVoting = $('.meta-abstimmungsvorlage').first().find('.abstimmungsvorlage').first().attr('id').split('-')[1];
			Easyvote.openVoting(firstVoting);
		}
	},

	openVoting: function(votingId) {
		$('#abstimmungsvorlage-' + votingId).find('.toggle i').trigger('click');
		Easyvote.scrollToElement('#abstimmungsvorlage-' + votingId);
	},


	toggleBlock: function(trigger) {
		$('.toggle-handle', trigger).toggleClass('toggle-handle-plus').promise().done(function() {
			$(trigger).next().slideToggle('fast').toggleClass('toggle-closed');
		})
	},

	resetForm: function(formSelector) {
		var $form = $(formSelector);
		$form.find("input[type=text], textarea, select").val("");
		$form.find("input[type=checkbox]").attr('checked', false);
	},

	/* Display a passed flash message */
	displayFlashMessage: function(message) {
		var $flashMessageContainer = $('#flashMessageContainer');
		var $contentWrap = $('.container-fluid');
		$flashMessageContainer.html('<a class="pull-right qtip-close" aria-label="schliessen"><i class="evicon-cancel"></i></a>' + message);
		var flashMessage = $contentWrap.qtip({
			show: '',
			//hide: {
			//	fixed: true,
			//	inactive: 2000,
			//	delay: 4000
			//},
			content: {
				prerender: true, // important
				text: $flashMessageContainer.html()
			},
			style: {
				classes: 'qtip-easyvote qtip-rounded qtip-modal qtip-shadow'
			},
			position: {
				my: 'center', at: 'center',
				target: $(window)
			},
			events: {
				render: function(event, api) {
					$closeButton = $('a.qtip-close', api.elements.content);
					$body.on('click', $closeButton, function(e) {
						api.hide(e);
					});
				}
			}
		}).qtip('show');
	},

	/**
	 * Get members of a party and open if a single member was requested
	 *
	 * @param openPartyMember
	 */
	getPartyMembers: function(openPartyMember) {
		EasyvoteGeneral.getData('/routing/partymembers').done(function(data) {
			$('.party-members').html(data);
			if (openPartyMember) {
				var elementId = '#member-item-' + openPartyMember;
				$(elementId).find('.toggle i').trigger('click');
				Easyvote.scrollToElement(elementId);
			}
			Easyvote.bindToolTips();
		});
	},

	/**
	 * Get all election supporters, scroll to top if requested
	 *
	 * @param scrollTop Whether or not to scroll to top after executing
	 */
	getElectionSupporters: function(command) {
		var topDisplay = '';
		if ($.isNumeric(command)) {
			var topDisplay = '/' + command;
		}
		EasyvoteGeneral.getData('/routing/electionsupporters' + topDisplay + '?L=' + EasyvoteLanguage + '&id=' + EasyvoteHomePid).done(function(data) {
			var $electionSupporters = $('.election-supporters');
			$electionSupporters.html(data);
			if (command === 'scrollTop') {
				$('html, body').animate({ scrollTop: 0 });
			}
			Easyvote.bindToolTips();

			/* Lazy load images */
			EasyvoteGeneral.bindImageLazyLoading();

			/* Postal code selection for filter */
			Easyvote.bindPostalCodeSelection();

			/* Initialize infinite scrolling for election supporter directory */
			$electionSupporters.jscroll({
				autoTrigger: false,
				nextSelector: '.election-supporters-next',
				//contentSelector: '.election-supporters',
				loadingHtml: '<div class="records-loading">laden...</div>',
				refresh: true,
				callback: EasyvoteGeneral.bindImageLazyLoading()
			});
		});
	},

	/**
	 * Set a (new) location for an event
	 * Hides the map, displays the event form
	 *
	 * @param button
	 * @returns {boolean}
	 */
	setEventLocation: function(button) {
		var $this = $(button);
		$('#event-location-text').val($this.data('location-text'));
		$('#event-location-uid').val($this.data('location-uid'));
		$('.tx-easyvote-location').slideUp();
		$('#event-form').slideDown();
		$('#map-container').closest('.col-md-6').find('.csc').first().slideUp();
		$('#change-location-trigger').show();
		return false;
	},

	/**
	 * Change the location for an event
	 * Displays the map, hides the event share partial (if visible)
	 */
	changeEventLocation: function() {
		$('#event-share').slideUp(500);
		$('.tx-easyvote-location').slideDown(500, function() {
			var mapCanvas = document.getElementById('map-canvas');
			google.maps.event.trigger(mapCanvas, 'resize');
		});
	},

	/**
	 * Change other data of an event
	 * Displays the event form, hides the event share partial (if visible)
	 */
	changeEvent: function() {
		$('#event-share').slideUp(500);
		$('#event-form').slideDown(500);
	}

};

// The functions in namespace EasyvoteApp are invoked by the easyvote App
// TODO no longer needed because functionality was removed, but stays here as an example of a phonebook integration
var EasyvoteApp = {
	insertAddress: function(id, lastName, firstName, email, phone) {
		// phone not yet implemented
	}
};

/* Load poll result */
function loadPollResult(votingProposal) {
	var ajaxPollUri = ajaxUri + '&tx_easyvote_communityajax[action]=showPollForVotingProposal&tx_easyvote_communityajax[votingProposal]=' + votingProposal;
	$.ajax({
		url: ajaxPollUri,
		success: function(data) {
			$('.vote-result-up', '#abstimmungsvorlage-' + votingProposal).text(data['results'][0] + '%');
			$('.vote-result-down', '#abstimmungsvorlage-' + votingProposal).text(data['results'][1] + '%');
			$voteUpHandle = $('#voteUp-' + votingProposal);
			$voteDownHandle = $('#voteDown-' + votingProposal);

			$voteUpHandle.after('<div class="hidden">' + data['voteUpText'] + '</div>');
			$voteDownHandle.after('<div class="hidden">' + data['voteDownText'] + '</div>');
			Easyvote.bindToolTips();
			if (typeof data['voteValue'] != 'undefined') {
				if (data['voteValue'] > 0) {
					/* classes documentation:
					   vote-active: Indicates that the vote was cast
					   vote-disabled: No vote can be cast because it was voted the other way
					   vote-enabled: Voting is possible
					   vote-notAuthenticated: no user is authenticated, so voting is not possible
					 */
					/* we already have a vote */
					if (data['voteValue'] === 1) {
						$voteUpHandle.addClass('vote-active').removeClass('vote-enabled').removeClass('vote-notAuthenticated');
						$voteDownHandle.addClass('vote-disabled').removeClass('vote-enabled').removeClass('vote-notAuthenticated');
					} else {
						$voteDownHandle.addClass('vote-active').removeClass('vote-enabled').removeClass('vote-notAuthenticated');
						$voteUpHandle.addClass('vote-disabled').removeClass('vote-enabled').removeClass('vote-notAuthenticated');
					}
				} else {
					/* we don't have a vote yet */
					$voteUpHandle.addClass('vote-up').addClass('vote-enabled').removeClass('vote-active').removeClass('vote-disabled').removeClass('vote-notAuthenticated');
					$voteDownHandle.addClass('vote-down').addClass('vote-enabled').removeClass('vote-active').removeClass('vote-disabled').removeClass('vote-notAuthenticated');
				}
			} else {
				$voteUpHandle.addClass('vote-notAuthenticated');
				$voteDownHandle.addClass('vote-notAuthenticated');
			}
		}
	});
}

var $body = $('body');

/* Load poll results for all votingProposals on loading the site */
$(function() {
	if (typeof(ajaxUri) !== 'undefined') {

		$('.abstimmungsvorlage').each(function() {
			var votingProposalUid = $(this).attr('id').split('-')[1];
			loadPollResult(votingProposalUid);
		});

		/* Undo vote */
		$body.on('click', '.vote-active', function(e) {
			var $trigger = $(this);
			$trigger.removeClass('vote-active');
			var votingProposalUid = $trigger.closest('.abstimmungsvorlage').attr('id').split('-')[1];
			var ajaxCallUri = ajaxUri + '&tx_easyvote_communityajax[action]=undoUserVoteForVotingProposal&tx_easyvote_communityajax[votingProposal]=' + votingProposalUid;
			$.ajax({
				url: ajaxCallUri,
				success: function(data) {
					loadPollResult(votingProposalUid);
				}
			});
			e.stopPropagation();
		});

		/* upVote */
		$body.on('click', '.vote-up.vote-enabled', function(e) {
			var $trigger = $(this);
			$trigger.removeClass('vote-up');
			var votingProposalUid = $trigger.closest('.abstimmungsvorlage').attr('id').split('-')[1];
			var ajaxCallUri = ajaxUri + '&tx_easyvote_communityajax[action]=voteForVotingProposal&tx_easyvote_communityajax[value]=1&tx_easyvote_communityajax[votingProposal]=' + votingProposalUid;
			$.ajax({
				url: ajaxCallUri,
				success: function(data) {
					Easyvote.displayModal(data['successText']);
					loadPollResult(votingProposalUid);
				}
			});
			e.stopPropagation();
		});

		/* downVote */
		$body.on('click', '.vote-down.vote-enabled', function(e) {
			var $trigger = $(this);
			$trigger.removeClass('vote-down');
			var votingProposalUid = $trigger.closest('.abstimmungsvorlage').attr('id').split('-')[1];
			var ajaxCallUri = ajaxUri + '&tx_easyvote_communityajax[action]=voteForVotingProposal&tx_easyvote_communityajax[value]=2&tx_easyvote_communityajax[votingProposal]=' + votingProposalUid;
			$.ajax({
				url: ajaxCallUri,
				success: function(data) {
					Easyvote.displayModal(data['successText']);
					loadPollResult(votingProposalUid);
				}
			});
			e.stopPropagation();
		});

		/* notAuthenticatedNotification when user clicks voteUp or voteDown without being authenticated*/
		$body.on('click', '.vote-notAuthenticated', function(e) {
			var message = $('#notAuthenticatedNotificationModal').html();
			Easyvote.displayModal(message);
			e.stopPropagation();
		});
	}

	Easyvote.bindToolTips();
	Easyvote.bindModals();

	// AJAX spinner
	var spinnerOptions = {
		lines: 13, // The number of lines to draw
		length: 25, // The length of each line
		width: 5, // The line thickness
		radius: 17, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		direction: 1, // 1: clockwise, -1: counterclockwise
		color: '#000', // #rgb or #rrggbb or array of colors
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};

	$.ajaxPrefilter(function(options, _, jqXHR) {
		var target = document.getElementById('spinner');
		var spinner = new Spinner(spinnerOptions).spin(target);
		jqXHR.complete(function() {
			spinner.stop();
		});
	});

	// Bind postal code selection
	if ($('.editProfileForm').length > 0) {
		Easyvote.bindPostalCodeSelection();
	}

	// re-open login modal on login error
	if ($('.felogin-error').length > 0) {
		$('.login-link').trigger('click');
	}

	// trigger registration modal on clicking the registration link in login modal
	$('.loginModalRegistrationLink').on('click', function(e) {
		e.preventDefault();
		$('.register-link').first().trigger('click');
	});

	var $dataCompletionRequestModal = $('#dataCompletionRequestModal');
	if ($dataCompletionRequestModal.length > 0) {
		var message = $dataCompletionRequestModal.html();
		$dataCompletionRequestModal.empty();
		Easyvote.displayModal(message);
		Easyvote.bindPostalCodeSelection();
	};

	// display selected file in dataCompletionRequestModal
	$body.on('change', '#dataCompletionRequestModalFalImage', function(e) {
		Easyvote.readFile(this, '.dataCompletionRequestModalPreviewImage');
	});

	// display mail registration form on small devices
	$body.on('click', '.displayMailRegistrationForm', function(e) {
		e.preventDefault();
		var $this = $(this);
		var $parent = $this.parent();
		$parent.find('.header-line').hide();
		$parent.find('.facebook-login').hide();
		$parent.find('.mail-registration').slideDown();
		$parent.parent().parent().find('.advantages').hide();
		$this.remove();
	});

	//
	// Initialization of the mobilizations feature based on existing/new event
	//
	var $mobilizations = $('#mobilizations');
	if ($mobilizations.length) {
		if (EasyvoteMobilizations.eventUid === null) {
			// If we don't have a persisted event yet, we display the map
			$mobilizations.css('visibility', 'visible').show();
			$('.tx-easyvote-location').css('visibility', 'visible').show();
		} else {
			// We have an event, so we display the share view
			$mobilizations.css('visibility', 'visible').show();
			$('#event-share').css('visibility', 'visible').show();
		}
	};

	//
	// Disable submit button on editEventForm after submitting to prevent double submitting
	//
	$body.on('submit', '.editEventForm', function() {
		$(this).find('button').prop('disabled', true);
	});

	//
	// Filter for election supporters on small devices
	//
	var jRes = jRespond([
		{
			label: 'handheld',
			enter: 0,
			exit: 991
		},{
			label: 'desktop',
			enter: 992,
			exit: 10000
		}
	]);

	jRes.addFunc({
		breakpoint: 'handheld',
		enter: function() {
			Easyvote.originalWrapperFilter = $('.supporter-filter');
			Easyvote.originalWrapperFilterParentContent = Easyvote.originalWrapperFilter.closest('.csc-default');
			var $box = Easyvote.originalWrapperFilter.detach();
			$('.election-supporters').prepend($box);
		},
		exit: function() {
			var $box = $('.supporter-filter').detach();
			Easyvote.originalWrapperFilterParentContent.append($box);
		}
	});

})