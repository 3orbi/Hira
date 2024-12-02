document.querySelectorAll('a[data-link]').forEach(link => {
    link.addEventListener('click', function (event) {
        event.preventDefault();

        const url = this.getAttribute('href');
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('content').innerHTML = html;
                history.pushState(null, '', url); // Met à jour l'URL
            })
            .catch(error => console.error('Erreur lors du chargement :', error));
    });
});
