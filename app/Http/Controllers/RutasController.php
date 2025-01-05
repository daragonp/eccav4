<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsPDF;
use App\Models\Present;
use App\Models\BlogCategory;
use App\Models\Worship;

class RutasController extends Controller
{
    //
    public function objetivos(){

        return view ('objetivos');
    }

    public function privacy(){

        return view ('privacy');
    }

    public function rights(){

        return view ('rights');
    }

    public function pqrs(){

        return view ('pqr');
    }

    public function data(){

        return view ('data');
    }

    public function mision(){

        return view ('mision');
    }

    public function meta(){

        return view ('meta');
    }

    public function declaracion(){

        return view ('declaracion');
    }

    public function mensajero(){

        return view ('mensajero');
    }

    public function lumbrera(){

        return view ('lumbrera');
    }

    public function ojos(){

        return view ('ojos');
    }

    public function herencia(){

        return view ('herencia');
    }

    public function worship(){

        $now = Worship::orderBy('created_at', 'DESC')->paginate(1);
        $worships = Worship::orderBy('created_at', 'DESC')->paginate(1);
        return view ('worships', compact('now', 'worships'));
    }
}
