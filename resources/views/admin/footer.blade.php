<footer class="bg-dark text-light py-4">
    <div class="container text-center">
        <div class="row justify-content-center">
            <!-- Logo o nombre del sitio -->
            <div class="col-md-4">
                <h4 class="mb-2">Emancipación Cristiana Afro</h4>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-2">
            <p class="mb-0">
                <small>&copy; <span id="currentYear"></span> Todos los Derechos Reservados | <a
                        href="http://www.tezbrillante.org" class="text-light">tezbrillante.org</a></small>
            </p>
        </div>
    </div>
</footer>

<script>
    const audioPlayer = document.getElementById('audioPlayer');
    const toggleButton = document.getElementById('toggleButton');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');

    toggleButton.addEventListener('click', () => {
        if (audioPlayer.paused) {
            audioPlayer.play();
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        } else {
            audioPlayer.pause();
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    });

    audioPlayer.addEventListener('error', (error) => {
        console.error("Error al reproducir el audio:", error);
        alert("Hubo un error al reproducir la transmisión. Por favor, inténtalo de nuevo más tarde.");
    });
    // Cambiar el año dinámicamente en el footer.
    document.getElementById('currentYear').textContent = new Date().getFullYear();
</script>
