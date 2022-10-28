var lang;
$.ajax({
    url: $('body').attr('data-url') + "/lang/" + $('html').attr('lang') + ".json",
    async: false,
    dataType: 'json',
    success: function(json) {
        lang = json;
    }
});
window.langText = lang;