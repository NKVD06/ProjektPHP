document.querySelector('.search-form')?.addEventListener('submit', function(e) {
    const input = this.querySelector('input[name="search"]');
    if (!input.value.trim()) {
        e.preventDefault();
    }
});