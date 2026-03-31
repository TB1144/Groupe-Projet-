    <footer class="main-footer">
        <div class="footer-content">
            <p>&copy; 2026 Web4All — Tous droits réservés.</p>
            <div class="footer-links">
                <a href="/mentions-legales">Mentions légales</a>
            </div>
        </div>
    </footer>
    <script>
        const burgerMenu = document.getElementById('burger-menu');
        const navLinks = document.querySelector('.nav-links');
        if (burgerMenu) {
            burgerMenu.addEventListener('click', () => navLinks.classList.toggle('active'));
        }
    </script>
</body>
</html>