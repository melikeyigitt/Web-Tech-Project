// Get the elements from the DOM
const clearButton = document.getElementById('clear-button');
const form = document.querySelector('form');
const cancelButton = document.createElement('button');

// Configure the Cancel button
cancelButton.type = 'button';
cancelButton.textContent = 'Cancel';
cancelButton.id = 'cancel-button';
form.querySelector('div:last-child').appendChild(cancelButton);

// Attach event listeners to the buttons
clearButton.addEventListener('click', () => {
    const confirmation = confirm('Are you sure you want to clear the form?');
    if (confirmation) {
        form.reset();
    }
});

cancelButton.addEventListener('click', () => {
    window.location.href = 'viewBlog.php';
});