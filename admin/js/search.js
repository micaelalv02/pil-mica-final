const url_search = $(".seachbar-block").attr("data-url");

$(".search-bar").autocomplete({
    source: url_search + '/api/search/get_data.php',
    minLength: 3,
    position: { my: "top", at: "center bottom" },
    classes: {
        "ui-autocomplete": "highlight"
    },
    response: function(event, ui) {
        return false;
    }
});
