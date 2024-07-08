document.addEventListener("DOMContentLoaded", function () {
    var searchInput = document.getElementById("search");
    if (searchInput) {
        var debounceTimeout;
        searchInput.addEventListener("input", function () {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function () {
                var searchValue = searchInput.value;
                var url = new URL(window.location.href);
                url.searchParams.set("search", searchValue);
                window.location.href = url.toString();
            }, 500); // adjust the delay to your liking (in ms)
        });
        searchInput.focus();
    }
});