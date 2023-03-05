// Get the search form element
const searchForm = document.getElementById('search-form');

// Add an event listener for form submission
searchForm.addEventListener('submit', (event) => {
    // Prevent the default form submission behavior
    event.preventDefault();

    // Get the search criteria value
    const criteria = document.getElementById('search-criteria').value;

    // Get the search query value
    const query = document.getElementById('search-query').value;

    // Send an AJAX request to the search route with the criteria and query values
    fetch('/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ criteria, query })
    })
    .then(response => response.json())
    .then(data => {
        // Handle the search results
        console.log(data);
    })
    .catch(error => {
        // Handle the error
        console.error(error);
    });
});