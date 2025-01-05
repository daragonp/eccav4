        <style>
            .customLink>a {
                text-decoration: none;
                color: #232323;
            }

            #circleIcon {
                width: 60px;
                height: 60px;
                line-height: 60px;
                border: 2px dotted #232323;
                border-radius: 100%;
                text-align: center
            }
        </style>
        <!-- Start Mega Menu HTML -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-light shadow">
            <div class="container-fluid">
                <img src="../images/logo/logo.png" alt="Emancipación Cristiana Afro" style="width:150px;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-content"
                    aria-controls="navbar-content" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-content">
                    <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">
                                <span class="icon d-flex justify-content-center" style="color: rgb(14, 104, 38);">
                                    <i class="fa-2x fas fa-home"></i><br>
                                </span>
                                <span class="text" style="font-weight: bold;">Inicio</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://skool.tezbrillante.org/" tabindex="-1"
                                aria-disabled="true">
                                <span class="icon d-flex justify-content-center" style="color: rgb(14, 104, 38);">
                                    <i class="fa-2x fas fa-book"></i><br>
                                </span>
                                <span class="text" style="font-weight: bold;">Instituto Bíblico</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('fullpost') }}" tabindex="-1" aria-disabled="true">
                                <span class="icon d-flex justify-content-center" style="color: rgb(14, 104, 38);">
                                    <i class="fa-2x fa-solid fa-message 2x"></i><br>
                                </span>
                                <span class="text" style="font-weight: bold;">Mensaje de la semana</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown dropdown-mega position-static">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside">
                                <span class="icon d-flex justify-content-center" style="color: rgb(14, 104, 38);">
                                    <i class="fa-2x fas fa-podcast"></i><br>
                                </span>
                                <span class="text" style="font-weight: bold;">Programas</span>
                            </a>
                            <div class="dropdown-menu shadow">
                                <div class="mega-content px-4">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12 col-sm-4 col-md-3 py-4">
                                                <h5>Programas a petición</h5>
                                                <div class="list-group">
                                                    <a class="list-group-item" href="{{ url('lumbrera') }}">Lumbrera a
                                                        mi camino</a>
                                                    <a class="list-group-item" href="{{ url('ojos') }}">Viéndome con
                                                        los ojos de Dios</a>
                                                    <a class="list-group-item" href="{{ url('herencia') }}">La herencia
                                                        espiritual de las generaciones</a>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 col-md-3 py-4">
                                                <h5>Meditad en la palabra de vida</h5>
                                                <div class="card">
                                                    <img src="../images/general/dominical.png" class="img-fluid"
                                                        alt="image">
                                                    <div class="card-body">
                                                        <p class="card-text">Domingos a las 9:00 am</p>
                                                        <p class="card-text"
                                                            style="font-weight: bold; color: rgb(14, 104, 38);">Haz clic
                                                            aquí para unirte usando <a style="color: rgb(14, 104, 38);"
                                                                href="https://meet.google.com/pcn-yftz-tvn?hs=122&authuser=0">Google
                                                                Meet</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 col-md-6 py-4">
                                                <h5>Programación Radio</h5>
                                                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                                    <table class="table table-bordered table-striped mb-0">
                                                        <thead>
                                                            <tr class="text-primary">
                                                                <th>Horario</th>
                                                                <th>Programa</th>
                                                                <th>Dirige</th>
                                                                <th>Día</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>00:01</td>
                                                                <td>Principios de Sabiduría</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>00:30</td>
                                                                <td>Música Flauta instrumental</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>00:45</td>
                                                                <td>Lectura de la Biblia NTD </td>
                                                                <td>Producción SBU</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>01:00</td>
                                                                <td>Músicas adoración </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>01:30</td>
                                                                <td>Músicas adoración </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>02:00</td>
                                                                <td>Música</td>
                                                                <td></td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>02:10</td>
                                                                <td>Lumbrera a mi camino</td>
                                                                <td>Henry Belalcazar</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>02:30</td>
                                                                <td>Música </td>
                                                                <td>Equipo ECCA</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>02:45</td>
                                                                <td>Lectura de la Biblia NTD </td>
                                                                <td>Producción SBU</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>03:00</td>
                                                                <td>Música Flauta instrumental </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>03:30</td>
                                                                <td>Música Flauta instrumental</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>04:00</td>
                                                                <td>Meditad en la palabra de vida</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>04:30</td>
                                                                <td>Música Alabanza para danzar </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>05:00</td>
                                                                <td>Mañana &#8211; Principios de sabiduría.</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>05:10</td>
                                                                <td>Lumbrera a mi camino</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>05:25</td>
                                                                <td>Canción no puedo parar de alabarte</td>
                                                                <td>Descargado de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>05:30</td>
                                                                <td>Música Alabanza para danzar </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>05:45</td>
                                                                <td>Haciendo vallado oración de Daniel 9:1-19</td>
                                                                <td>Rafael Bonilla Ruiz</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>06:00</td>
                                                                <td>Mañana &#8211; Principios de sabiduría.</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>06:25</td>
                                                                <td>Canción no puedo parar de alabarte</td>
                                                                <td>Descargado de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>06:30</td>
                                                                <td>Música Alabanza para danzar </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>07:00</td>
                                                                <td>Lugar Secreto </td>
                                                                <td>Carmen Emilia Paz Díaz</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>07:30</td>
                                                                <td>Música Lluvia en el desierto </td>
                                                                <td>PaulWilbur</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>08:00</td>
                                                                <td>Formando un ejército para Cristo </td>
                                                                <td>Italia collazos Ramos</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>08:30</td>
                                                                <td>Música Lluvia en el desierto </td>
                                                                <td>PaulWilbur</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>09:00</td>
                                                                <td><strong>Franja Infantil</strong></td>
                                                                <td><strong>Equipo ECCA</strong></td>
                                                                <td><strong>S</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>10:00</td>
                                                                <td><strong>Franja Infantil</strong></td>
                                                                <td><strong>Equipo ECCA</strong></td>
                                                                <td><strong>S</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>09:00</td>
                                                                <td>Siembra y Cosecha </td>
                                                                <td>Ana Lucia Cambindo Ramos</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>09:30</td>
                                                                <td>Música Salmos cantados </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10:00<strong></strong></td>
                                                                <td>Música entraré a Jerusalén</td>
                                                                <td>PaulWilbur</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10:30</td>
                                                                <td>Música entraré a Jerusalén</td>
                                                                <td>PaulWilbur</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10:45</td>
                                                                <td>Lectura de la Biblia NTD </td>
                                                                <td>Producción SBU</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>11:00</td>
                                                                <td>Música de alabanza de poder</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>11:30</td>
                                                                <td>Música adoración </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>12:00</td>
                                                                <td>Medio día &#8211; Principios de sabiduría.</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>12:30</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>13:00</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>13:10</td>
                                                                <td>Lumbrera a mi camino</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>13:30</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>14:00</td>
                                                                <td>Tarde &#8211; Principios de sabiduría.</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>14:30</td>
                                                                <td>Música Salmos cantados </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>15:03</strong></td>
                                                                <td><strong>Culto Dominical</strong></td>
                                                                <td><strong>Henry Belalcazar V</strong></td>
                                                                <td><strong>D</strong></td>
                                                                <td><strong></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>15:03</td>
                                                                <td>Meditad en la palabra de vida</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>15:30</td>
                                                                <td>Meditad en la palabra de vida</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>16:00</td>
                                                                <td>Franja infantil </td>
                                                                <td>Equipo ECCA</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>16:30</td>
                                                                <td>Franja infantil </td>
                                                                <td>Equipo ECCA</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>17:00</td>
                                                                <td>Música Salmos cantados </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>17:30</td>
                                                                <td>Música Salmos cantados </td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>17:40</td>
                                                                <td>La hora del retorno &#8211; Principios de sabiduría.
                                                                </td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>17:45</td>
                                                                <td>Lectura de la Biblia NTD </td>
                                                                <td>Producción SBU</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>18:00</td>
                                                                <td>Formando un ejército Cristo</td>
                                                                <td>Italia collazos Remos</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>18:25</td>
                                                                <td>Canción no puedo parar de alabarte</td>
                                                                <td>Descargado de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>18:30</td>
                                                                <td>Música Vocal Sampling </td>
                                                                <td>Monte de Sion</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>19:00</td>
                                                                <td>Música Vocal Sampling </td>
                                                                <td>Monte de Sion</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>19:10</td>
                                                                <td>Lumbrera a mi camino</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>19:30</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>20:00</td>
                                                                <td>Noche &#8211; Principios de sabiduría.</td>
                                                                <td>Lorena Banguero Rivas</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>20:00</td>
                                                                <td>Lugar Secreto</td>
                                                                <td>Carmen Emilia Paz</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>20:30</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>20:45</td>
                                                                <td>Haciendo Vallado. Oración de Daniel 9:1-19</td>
                                                                <td>Rafael Bonilla Ruiz</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>21:00</strong></td>
                                                                <td><strong>Culto Dominical</strong></td>
                                                                <td><strong>Henry Belalcazar V</strong></td>
                                                                <td><strong>S-D</strong></td>
                                                                <td><strong></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>21:00</td>
                                                                <td>Meditad en la palabra de vida</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>21:30</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>22:00</td>
                                                                <td>Música adoración</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>22:00</td>
                                                                <td>Siembra y cosecha</td>
                                                                <td>Ana Lucia Cambindo</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>22:30</td>
                                                                <td>Música </td>
                                                                <td>Equipo ECCA</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>22:45</td>
                                                                <td>Lectura de la Biblia NTD </td>
                                                                <td>Producción SBU</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>23:00</td>
                                                                <td>Música Flauta instrumental</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>23:10</td>
                                                                <td>Meditad en la palabra de vida</td>
                                                                <td>Henry Belalcazar V</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>23:30</td>
                                                                <td>Música Flauta instrumental</td>
                                                                <td>Diferentes intérpretes de YouTube</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>23:45</td>
                                                                <td>Haciendo vallado oración de Daniel 9:1-19</td>
                                                                <td>Rafael Bonilla Ruiz</td>
                                                                <td>L-D</td>
                                                            </tr>
                                                            <tr>
                                                                <td>24:00</td>
                                                                <td>Lugar Secreto </td>
                                                                <td>Carmen Emilia Paz Díaz</td>
                                                                <td>L-D</td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('opinion') }}" tabindex="-1" aria-disabled="true">
                                <span class="icon d-flex justify-content-center" style="color: rgb(14, 104, 38);">
                                    <i class="fa-solid fa-earth-africa fa-2x"></i><br>
                                </span>
                                <span class="text" style="font-weight: bold;">Mirada Afro</span>
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex ms-auto">
                        <span class="icon">
                            <a href="https://www.facebook.com/ECCARadio/"><i class="fab fa-facebook fa-2x"
                                    style="color:rgb(14, 104, 38);"></i></a>
                            <a href="#"><i class="fab fa-youtube fa-2x" style="color:rgb(247, 204, 175);"></i></a>
                        </span>
                    </div>
                    <div class="d-flex ms-auto">

                        <span>
                            <a href="{{ url('donate') }}"><i class="fas fa-hand-holding-heart fa-2x"
                                    style="color:rgb(14, 104, 38);">Donar</i></a><br>
                        </span>

                    </div>
                    <div class="d-flex ms-auto">
                        <!-- Inicio. radio hosting USAstreams.com html5 player -->
                        <!-- Licencia: GRATIS-XDF4543ERF -->
                        <iframe name="contenedorPlayer" class="cuadroBordeado" allow="autoplay" width="250px"
                            height="50px" marginwidth=5 marginheight=5 hspace=5 vspace=10 frameborder=10 scrolling=no
                            src="https://cp.usastreams.com/pr2g/APPlayerRadioHTML5.aspx?stream=https://cast6.asurahosting.com/proxy/dilinger/;&fondo=05&formato=mp3&color=6&titulo=2&autoStart=1&vol=5&tipo=1&nombre=Radio+ECCA&botonPlay=4"></iframe>
                        <!-- En players responsive puede modificar el weight a sus necesidades, Por favor no modifique el resto del codigo para poder seguir ofreciendo este servicio gratis  -->
                        <!-- Fin. USAstreams.com html5 player -->
                    </div>
                </div>
            </div>
        </nav>
        @auth
            <div class="container-fluid" role="navigation">
                <div class="text-center py-2">
                    <h5 style="color:rgb(14, 104, 38);"> Bienvenido {{ auth()->user()->name }}</h5>
                </div>
                <div class="row text-center align-items-center g-0">
                    <div class="col-lg-5 col-md-5 d-none d-lg-block d-md-block d-xs-none ms-auto">
                        <div class="d-flex justify-content-around border-top border-bottom border-dark">
                            <div class="customLink"><a class="d-inline-block p-3"
                                    href="{{ url('dashboard') }}">Dashboard</a></div>
                            <div class="customLink"><a class="d-inline-block p-3" href="{{ url('new-news') }}">Crear
                                    mensaje</a></div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 mx-auto" style="max-width:120px;">
                        <a href="/"><img class="img-responsive" src="../admin/images/layout_img/face.jpg"
                                alt="#" id="circleIcon"></a>
                    </div>
                    <div class="col-lg-5 col-md-5 d-none d-lg-block d-md-block d-xs-none me-auto">
                        <div class="d-flex justify-content-around border-top border-bottom border-dark">
                            <div class="customLink"><a class="d-inline-block p-3" href="{{ url('logout') }}">Salir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
