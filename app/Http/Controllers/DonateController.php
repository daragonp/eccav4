<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DonateController extends Controller
{
    //
    public function index(){

        $description = 'Procesamiento de pagos con PayU';

        return view('donate', compact('description'));
    }

    public function create(Request $request){

        $donor = new Donation();

        $reference = DB::table('donations')->orderBy('reference', 'DESC')->first();

        $donor->reference =  str_pad($reference->reference + 1, 8, "0", STR_PAD_LEFT);
        $donor->name = $request->name;
        $donor->amount = $request->donate;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->address = $request->address;
        $donor->message = $request->message;

        $donor->save();
        $apikey = '4Vj8eK4rloUd272L48hsrarnUA';
        $mercado = '508029';
        $total = $request->donate;
        $moneda='COP';
        $comodin = "~";
        $frase = md5($apikey.$comodin.$mercado.$comodin.$donor->reference.$comodin.$total.$comodin.$moneda);

        $url="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/";

        return Redirect::to($url)->with(compact('frase'));
    }
}
