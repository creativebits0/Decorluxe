function universalLiveSearch(input) {

    const processUrl = input.dataset.processUrl;
    const targetDiv = input.dataset.targetDiv;
    const type = input.dataset.type || "all";

    const search = input.value;

    fetch(
        `${processUrl}?user_type=${type}&search=${encodeURIComponent(search)}`
    )
    .then(response => response.text())
    .then(html => {

        document.getElementById(targetDiv).innerHTML = html;

    })
    .catch(error => {

        console.error("Search Error:", error);

    });
}