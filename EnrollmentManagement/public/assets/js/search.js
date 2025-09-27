document.querySelectorAll('.course-card').forEach(card => {
    card.style.display = 'block';
});

document.getElementById('courseLists').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const cards = document.querySelectorAll('.course-card');

    cards.forEach(card => {
        const courseName = card.querySelector('.courseName').textContent.toLowerCase();
        card.style.display = courseName.includes(query) ? 'block' : 'none';
    });
});