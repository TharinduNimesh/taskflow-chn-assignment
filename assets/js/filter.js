document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Search tasks..."]');
    const categoryButtons = document.querySelectorAll('.filter-category');
    const taskCards = document.querySelectorAll('.task-card');

    // Add active state to category buttons
    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            categoryButtons.forEach(btn => btn.classList.remove('ring-2', 'ring-violet-500/50'));
            button.classList.add('ring-2', 'ring-violet-500/50');
            filterTasks();
        });
    });

    // Handle search input
    searchInput.addEventListener('input', debounce(filterTasks, 300));

    function filterTasks() {
        const searchTerm = searchInput.value.toLowerCase();
        // Get the first button with ring-2 class or default to 'all'
        const activeCategoryElement = document.querySelector('.filter-category.ring-2');
        const activeCategory = activeCategoryElement ? activeCategoryElement.querySelector('span').textContent.toLowerCase() : 'all';

        taskCards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            const categories = Array.from(card.querySelectorAll('.task-category')).map(cat => cat.textContent.toLowerCase());

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesCategory = activeCategory === 'all' || categories.includes(activeCategory);

            // Apply smooth transition
            if (matchesSearch && matchesCategory) {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
                card.style.display = 'block';
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (card.style.opacity === '0') {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
    }

    // Debounce function to limit rapid firing of search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});