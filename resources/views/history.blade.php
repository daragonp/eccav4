@extends('layouts.main')

@section('title', "Historia - Mensaje de la semana")
    <style>
#mensajes {
  margin-top: 20px;
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
  text-align: center;
}

#mensajes td, #mensajes th {
  border: 1px solid #ddd;
  padding: 8px;
}

#mensajes tr:nth-child(even){background-color: #f2f2f2;}

#mensajes tr:hover {background-color: #ddd;}

#mensajes th {
  padding-top: 12px;
  padding-bottom: 12px;
  background-color: #04AA6D;
  color: white;
}
</style>
 @section('content')

    <div class="container">
        <div class="row">
            <table id="mensajes">
                <thead>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Enlace</th>
                    <th>Audio</th>
                </thead>
                <tbody>
                    @foreach($message as $msg)
                        <tr>
                            <td>{{$msg->title}}</td>
                            <td>{{$msg->created_at->format('d-M-Y') }}</td>
                            <td><a href="../pdfdoc/pdfnews/{!!$msg->pdfdoc!!}" target="_blank"><i class="fa-regular fa-file-pdf fa-2x"></i></td>
                            <td>
                                @if($msg->audio)
                                    <a href="audio/readings/{{$msg->audio}}" type="audio/mpeg"><i class="fa-solid fa-headphones fa-2x"></i>
                                @else
                                    <span>Este mensaje no contiene audio</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th>Título</th>
                    <th>Fecha</th>
                    <th>Enlace</th>
                    <th>Audio</th>
                </tfoot>
            </table>
        </div>
    </div>
@stop
