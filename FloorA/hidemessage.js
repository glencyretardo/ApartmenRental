document.addEventListener("DOMContentLoaded", function() {

    setTimeout(function() {
        document.querySelector('.message').style.display = 'none';
    }, 2000);

    // Add click event listener to close button
    document.querySelector('.message i').addEventListener('click', function() {
        document.querySelector('.message').style.display = 'none';
    });

});
