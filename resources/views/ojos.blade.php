<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Lumbrera a mi camino | Emancipación Cristiana Afro</title>
    @include('header')
  </head>
  <body>
    @include('navbar')
    <div class="container"><br>
        <h2>Lumbrera a mi camino - Viéndome con los ojos de Dios</h2><hr>
        <iframe src="https://www.ivoox.com/player_es_podcast_1444438_zp_1.html?c1=e4e46b" width="100%" height="400" frameborder="0" allowfullscreen="" scrolling="no" loading="lazy"></iframe>    </div>
        
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  </body>
  <footer>
    <div class="container">
      @include('footer')
    </div>
  </footer>
  <!-- Bootstrap 5 JS -->
 
  <script>
    document.addEventListener('click',function(e){
      // Hamburger menu
      if(e.target.classList.contains('hamburger-toggle')){
        e.target.children[0].classList.toggle('active');
      }
    })         
  </script>
</html>