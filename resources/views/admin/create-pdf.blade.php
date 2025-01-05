<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Mensaje de la semana</title>
        <style>
        h1{
        text-align: center;
        text-transform: uppercase;
        }
        .contenido{
        font-size: 20px;
        }
        #primero{
        background-color: #ccc;
        }
        #segundo{
        color:#44a359;
        }
        #tercero{
        text-decoration:line-through;
        }
    </style>
    </head>
    <body>
        <h1>{!!$news->title!!}</h1>
        <hr>
        <div class="contenido">
            <p id="tercero"><img src="C:/xampp/htdocs/ecca/public/images/news/{{$news->image}}" alt="" height="100px;"></p>
            <p id="primero">{!!$news->abstract!!}</p>
            <p id="segundo">{!!$news->text!!}</p>
        </div>
    </body>
</html>