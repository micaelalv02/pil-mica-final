const url_search = $(".seachbar-block").attr("data-url");

$(".search-bar").autocomplete({
    source: url_search + '/api/search/get_data.php',
    minLength: 3,
    position: { my: "center+30 top", at: "center bottom" },
    classes: {
        "ui-autocomplete": "highlight"
    },
    select: function(event, ui) {
        document.location.href = ui.item.link;
        return false;
    }
});