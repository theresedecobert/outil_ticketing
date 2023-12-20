// Animation to display edit form on show page
document.getElementById('editButton').addEventListener('click', function() {
    var editForm = document.getElementById('editForm');
    editForm.style.display = editForm.style.display === "none" ? "block" : "none";
});

// Animation to display answer form on show page
document.getElementById('answerButton').addEventListener('click', function() {
    var answerForm = document.getElementById('answerForm'); // Corrected variable name
    answerForm.style.display = answerForm.style.display === "none" ? "block" : "none";
});