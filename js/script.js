document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('input[name="search"]');
    const resultsTable = document.querySelector('table');

    searchInput.addEventListener('input', () => {
        const searchValue = searchInput.value;

        fetch('filter_employees.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'search': searchValue
            })
        })
        .then(response => response.text())
        .then(data => {
            resultsTable.innerHTML = data;
        });
    });
});
