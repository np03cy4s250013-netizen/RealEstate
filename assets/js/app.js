document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#searchForm');
    const resultsContainer = document.querySelector('#propertiesContainer');

    if (!form || !resultsContainer) {
        return;
    }

    const filterFields = ['location', 'min_price', 'max_price', 'house_type']
        .map(id => document.getElementById(id))
        .filter(Boolean);

    function fetchFilteredResults() {
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();

        fetch('ajax_filter.php?' + queryString, { method: 'GET' })
            .then(response => response.text())
            .then(html => {
                resultsContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading properties:', error);
            });
    }

    filterFields.forEach(field => {
        field.addEventListener('input', fetchFilteredResults);
        field.addEventListener('change', fetchFilteredResults);
    });
});
