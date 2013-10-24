$(function() {
    initializeVotings();
    bindNavigation();

    $('a#goToGegenvorschlag').click(function() {
    var jumpToId = $(this).closest('div.abstimmungsvorlage').next().attr('id');
    scrollToElement('#' + jumpToId);
    });
$('a#goToHauptvorlage').click(function() {
    var jumpToId = $(this).closest('div.abstimmungsvorlage').prev().attr('id');
    scrollToElement('#' + jumpToId);
    });

$('.toggle-trigger').click(function() {
    toggleBlock(this);
    });

$('.searchResults .abstimmungsvorlage-header').click(function(e) {
    $('.abstimmungsvorlage-content', $(this).parent()).slideToggle('slow', function() {
        $('i', $(this).parent()).toggleClass('icon-chevron-down');
    });
})
});

function initializeVotings() {
    if (typeof metaVotingProposalId != 'undefined') {
        openVoting(metaVotingProposalId);

    } else {
        var firstVoting = $('.meta-abstimmungsvorlage').first().attr('id').split('-')[2];
        openVoting(firstVoting);
    }
}

function bindNavigation() {
    $('.meta-abstimmungsvorlage-navi').click(function() {
        var metaVotingId = $(this).attr('id').split('-')[3];
        $('.meta-abstimmungsvorlage-navi').unbind('click');
        openVoting(metaVotingId);
    });
}

function openVoting(metaVotingId) {

    $('#meta-abstimmungsvorlage-navi-' + metaVotingId).slideUp(300, function() {
        $('.meta-abstimmungsvorlage').not('#meta-abstimmungsvorlage-' + metaVotingId).slideUp(300, function() {
            $('#meta-abstimmungsvorlage-' + metaVotingId).slideDown(300, function() {
                $('.meta-abstimmungsvorlage-navi').not('#meta-abstimmungsvorlage-navi-' + metaVotingId).slideDown(300, function() {
                    $('#votingsDashboard').show();
                });

            });
        });
    });
    bindNavigation();

}

function scrollToElement(elementId){
    $('html, body').animate({
        scrollTop: $(elementId).offset().top
    }, 1000);
}

function toggleBlock(trigger) {
    $('.toggle-handle', trigger).toggleClass('toggle-handle-plus').promise().done(function() {
        $(trigger).next().slideToggle('fast').toggleClass('toggle-closed');
    })
}

function resetForm() {
    $("#searchForm").find("input[type=text], textarea, select").val("");
    $("#searchForm").find("input[type=checkbox]").attr('checked', false);
}