<footer class="main-footer">
        <div class="footer-content">
            <p>&copy; 2026 Web4All — Tous droits réservés.</p>
            <div class="footer-links">
                <a href="/mentions-legales">Mentions légales</a>
            </div>
        </div>
    </footer>

    <button id="btn-top" aria-label="Retour en haut" style="
        position: fixed;
        bottom: 32px;
        right: 32px;
        width: 48px;
        height: 48px;
        background-color: #FFD500;
        border: 3px solid #111111;
        box-shadow: 4px 4px 0px #111111;
        font-size: 20px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    ">↑</button>

    <script>
        const btnTop = document.getElementById('btn-top');
        const footer = document.querySelector('.main-footer');

        window.addEventListener('scroll', () => {
            const footerTop = footer.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            const btnHeight = 48 + 32; // hauteur bouton + marge bottom

            if (window.scrollY > 300) {
                btnTop.style.opacity = '1';
                btnTop.style.pointerEvents = 'all';
            } else {
                btnTop.style.opacity = '0';
                btnTop.style.pointerEvents = 'none';
            }

            // Si le footer est visible, on remonte le bouton
            if (footerTop < windowHeight) {
                const offset = windowHeight - footerTop + 16;
                btnTop.style.bottom = offset + 'px';
            } else {
                btnTop.style.bottom = '32px';
            }
        });

        btnTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

</body>
</html>