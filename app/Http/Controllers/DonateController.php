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
        $validated = $request->validate([
            'name' => 'required|string|max:64',
            'donate' => 'required|numeric|min:100',
            'email' => 'required|email|max:64',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500',
        ]);

        $donor = new Donation();
        $latestReference = DB::table('donations')->orderBy('reference', 'desc')->value('reference');
        $nextReference = $latestReference ? ((int) $latestReference) + 1 : 1;

        $donor->reference = str_pad($nextReference, 8, '0', STR_PAD_LEFT);
        $donor->name = $validated['name'];
        $donor->amount = (int) $validated['donate'];
        $donor->email = $validated['email'];
        $donor->phone = $validated['phone'] ?? null;
        $donor->address = $validated['address'] ?? null;
        $donor->message = $validated['message'] ?? null;
        $donor->save();

        $apikey = env('PAYU_API_KEY', '4Vj8eK4rloUd272L48hsrarnUA');
        $mercado = env('PAYU_MERCHANT_ID', '508029');
        $total = $donor->amount;
        $moneda = 'COP';
        $comodin = '~';
        $frase = md5($apikey.$comodin.$mercado.$comodin.$donor->reference.$comodin.$total.$comodin.$moneda);

        $url = 'https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/';

        return Redirect::to($url)->with(compact('frase'));
    }
}
