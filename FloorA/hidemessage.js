setTimeout(function() {
    document.querySelector('.message').style.display = 'none';
}, 1000);

// Add click event listener to close button
document.querySelector('.message i').addEventListener('click', function() {
    document.querySelector('.message').style.display = 'none';
});