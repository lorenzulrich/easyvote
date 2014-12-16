$(function() {
	var $body = $('body');

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
	$('.toggle-trigger').click(function(e) {
		Easyvote.toggleBlock(this);
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
					classes: 'qtip-easyvote qtip-rounded qtip-shadow qtip-easyvote-narrow'
				}
			});
		});
	},

	/* Modals */
	bindModals: function() {
		$body.on('click', '.hasModal', function(e) {
			e.preventDefault();
			var requestedLink = $(e.target).attr('href');
			var $modalContentContainer = $(this).next('div');
			var modalContent = $modalContentContainer.html();
			$(this).qtip({
				content: {
					text: $modalContentContainer
				},
				hide: false,
				show: {
					ready: true,
					modal: {
						on: true,
						blur: false
					}
				},
				position: {
					my: 'center', at: 'center',
					target: $(window)
				},
				style: {
					classes: 'qtip-easyvote qtip-rounded qtip-modal'
				},
				events: {
					render: function(event, api) {
						event.preventDefault();
						$('button.button-confirm', api.elements.content).click(function(e) {
							window.location = requestedLink;
						});
						$('a.qtip-close', api.elements.content).click(function(e) {
							api.hide(e);
						});
						$('button.button-cancel', api.elements.content).click(function(e) {
							api.hide(e);
						});
					},
					hide: function(event, api) {
						$(e.target).closest('a').after('<div class="hidden">' + modalContent + '</div>');
						api.destroy();
					}
				}
			});
		});
	},

	/* File Reader */
	readFile: function(input, targetSelector) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(targetSelector).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	},
	/* Display a passed modal */
	displayModal: function(message) {
		var $flashMessageContainer = $('#flashMessageContainer');
		var $contentWrap = $('.container-fluid');
		$flashMessageContainer.html('<a class="pull-right qtip-close" aria-label="schliessen"><i class="evicon-cancel"></i></a>' + message);
		$contentWrap.qtip({
			content: {
				prerender: true, // important
				text: $flashMessageContainer.html()
			},
			hide: false,
			show: {
				ready: true,
				modal: {
					on: true,
					blur: false
				}
			},
			position: {
				my: 'center', at: 'center',
				target: $(window)
			},
			style: {
				classes: 'qtip-easyvote qtip-rounded qtip-modal qtip-shadow'
			},
			events: {
				render: function(e, api) {
					e.preventDefault();
					$('button.button-confirm', api.elements.content).click(function(e) {
						window.location = requestedLink;
					});
					$('a.qtip-close', api.elements.content).click(function(e) {
						api.hide(e);
					});
					$('button.button-cancel', api.elements.content).click(function(e) {
						api.hide(e);
					});
					if (typeof(FB) === 'object') {
						FB.XFBML.parse();
					}
					if (typeof(twttr) === 'object') {
						twttr.widgets.load();
					}
				},
				hide: function(e, api) {
					api.destroy();
				}
			}
		}).qtip('show');
	},
	/* Postal code selection for forms */
	bindPostalCodeSelection: function() {
		if (typeof postalCodeServiceUrl === 'string') {
			var $postalCodeSelector = $(".communityUser-citySelection");
			$postalCodeSelector.select2({
				placeholder: "PLZ eingeben...",
				minimumInputLength: 2,
				ajax: {
					url: postalCodeServiceUrl,
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
				var data = $(this).select2('data');
				$('.communityUser-citySelection').val(data.id);
				var selectedCityName = data.postalCode + ' ' + data.city + ' (' + data.kantonName + ')';
				$('.communityUser-cityOutput').val(selectedCityName);
			});
		}
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
					FB.XFBML.parse();
					twttr.widgets.load();
				}
			}
		}).qtip('show');
	},

	loadMobilizedCommunityUsers: function() {
		var ajaxDataUri = ajaxUri + '&tx_easyvote_communityajax[controller]=CommunityUser&tx_easyvote_communityajax[action]=listMobilizedCommunityUsers';
		$.ajax({
			url: ajaxDataUri,
			success: function(data) {
				$('#mobilizedCommunityUsers').html(data);
			}
		});
	},
	newMobilizedCommunityUser: function(callback) {
		var ajaxDataUri = ajaxUri + '&tx_easyvote_communityajax[controller]=CommunityUser&tx_easyvote_communityajax[action]=newMobilizedCommunityUser';
		$.ajax({
			url: ajaxDataUri,
			success: function(data) {
				$('#newMobilizedCommunityUserPlaceholder').append(data);
				if (typeof(callback) == 'function') {
					callback();
				}
			}
		});
	}
};

// The functions in namespace EasyvoteApp are invoked by the easyvote App
var EasyvoteApp = {
	insertAddress: function(id, lastName, firstName, email, phone) {
		// phone not yet implemented
		if (lastName + firstName + email === '') {
			// don't try to submit empty rows
			return true;
		}
		// remove empty rows
		$.each($('.newMobilizedCommunityUser'), function() {
			var $this = $(this);
			if (!$this.find('.firstName').val() && !$this.find('.lastName').val() && !$this.find('.email').val()) {
				$this.remove();
			}
		});
		var callback = function() {
			var $newMobilizedCommunityUser = $('.newMobilizedCommunityUser').last();
			$newMobilizedCommunityUser.find('.firstName').val(firstName);
			$newMobilizedCommunityUser.find('.lastName').val(lastName);
			$newMobilizedCommunityUser.find('.email').val(email);
		};
		Easyvote.newMobilizedCommunityUser(callback);
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

	/* Load mobilized community users */
	if ($('#mobilizedCommunityUsers').length > 0) {
		// add 3 empty fields
		Easyvote.newMobilizedCommunityUser();
		Easyvote.newMobilizedCommunityUser();
		Easyvote.newMobilizedCommunityUser();
		Easyvote.loadMobilizedCommunityUsers();
	}

	/* Add new mobilized community user */
	$body.on('click', '#newMobilizedCommunityUser', function(e) {
		e.preventDefault();
		Easyvote.newMobilizedCommunityUser();
	});

	/* Add a new mobilized community users */
	$body.on('click', '#saveMobilizedCommunityUsers', function(e) {
		e.preventDefault();
		$('form.newMobilizedCommunityUser').each(function() {
			var $this = $(this);
			var firstName = $.trim($this.find('.firstName').val());
			var lastName = $.trim($this.find('.lastName').val());
			var email = $.trim($this.find('.email').val());
			if (firstName + lastName + email === '') {
				// don't try to submit empty rows
				return true;
			}
			$(this).remove();
			var $formData = $(this).serializeArray();
			var ajaxDataUri = ajaxUri + '&tx_easyvote_communityajax[controller]=CommunityUser&tx_easyvote_communityajax[action]=createMobilizedCommunityUser';
			$.ajax({
				url: ajaxDataUri,
				data: $formData,
				success: function(returnValue) {
					Easyvote.displayFlashMessage(returnValue);
				}
			});
		});
		Easyvote.loadMobilizedCommunityUsers();
	});

	/* Remove a mobilized community user */
	$body.on('submit', '.removeMobilizedCommunityUser', function(e) {
		e.preventDefault();
		var $formData = $(this).serializeArray();
		var ajaxDataUri = ajaxUri + '&tx_easyvote_communityajax[controller]=CommunityUser&tx_easyvote_communityajax[action]=removeMobilizedCommunityUser';
		$.ajax({
			url: ajaxDataUri,
			data: $formData,
			success: function(returnValue) {
				Easyvote.displayFlashMessage(returnValue);
				Easyvote.loadMobilizedCommunityUsers();
			}
		});
	});

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

	// Facebook Share Link
	$body.on('click', '.fb-share-link', function(e) {
		$trigger = $(e.target).closest('a');
		FB.ui(
			{
				method: 'feed',
				link: $trigger.attr('data-href'),
				description: $trigger.attr('data-description')
			},
			function(response) {}
		);
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
})