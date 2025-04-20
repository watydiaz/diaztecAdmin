<style>
    .back-to-top {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }
</style>

</main><br><br>
    <footer style="background-color: black; color: white; text-align: center; padding: 10px; margin-top: 20px; position: fixed; bottom: 0; width: 100%;">
        <p>&copy; <?php echo date('Y'); ?> Sistema de Gestión de Tickets. <a href="wwww.diaztecnologia.co">Diaztecnologia</a> Todos los derechos reservados.</p>
    </footer>

<button class="back-to-top" id="backToTop" onclick="scrollToTop()">&uarr;</button>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeo5e1l5e1l5e1l5e1l5e1l5e1l5e1l5e1l5e1l5e1l5e1l5e1" crossorigin="anonymous"></script>

<script>
    const backToTopButton = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
</body>
</html>