window.addEventListener("DOMContentLoaded", (e) => {
    // trigger moment.js formatting
    $(".momentjs").each(function(index) {
        let datetime = $(this).text();
        let formatted = moment(datetime).fromNow();
        $(this).text(formatted);
        $(this).prop("title", datetime);
    });


    // activate tooltips
    $(function () {
        $("[data-toggle='tooltip']").tooltip()
    })

    // autofocus search input on toggle click
    var searchToggle = document.getElementById("search-toggle");
    searchToggle.addEventListener("click", function(e) {
        if (searchToggle.checked) {
            var searchInput = document.getElementById("search-input");
            searchInput.focus();
        }
    });
});

