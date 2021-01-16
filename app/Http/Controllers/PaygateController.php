<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Support\Facades\Validator;
use Gloudemans\Shoppingcart\Facades\Cart;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class PaygateController extends Controller
{
    /**
     * PAYGATE METHODE 1 : POST
     */
    public function payment()
    {
        if (request()->isMethod('POST')) {
            $validator = Validator::make(request()->all(), [
                'phoneNumber' => 'required'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->invoice_id = null;
            $order->status = 'initialised';
            $order->identifier = uniqid();
            $order->payment_reference = null;
            $order->save();

            $apiToken = env('API_KEY');
            $amount = Cart::subtotal();
            $description = 'New purchase from :: ' . request()->phoneNumber;

            Session::put('orderId', $order->getKey());

            $client = new Client();

            $response = $client->post('https://paygateglobal.com/api/v1/pay', [
                'json' => [
                    'auth_token' => $apiToken,
                    'phone_number' => request()->phoneNumber,
                    'amount' => $amount,
                    'identifier' => $order->identifier,
                    'description' => $description
                ]
            ]);

            $response = json_decode($response->getBody());

            //Update order
            $order->payment_reference = $response->tx_reference;
            $order->save();

            //IF ERROR DB::rollBack();
            if ($response->status != 0) {
                DB::rollBack();
                return back()->with('message', "Une erreur s'est produite!");
            }

            return back()->with('success', 'Votre paiement a été prise en compte');
        }
        return view('payment');
    }

    /**
     * PAYGATE METHODE 2 : GET
     * Save new order and redirect user to paygate portal
     */
    public function payThroughPaygate()
    {
        # We insert a new order in the order table with the 'initialised' status
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->invoice_id = null;
        $order->status = 'initialised';
        $order->identifier = uniqid();
        $order->payment_reference = null; //No payment confirmation from paygate yet
        $order->save();

        $apiToken = env('API_KEY');
        $amount = Cart::subtotal();
        $description = 'Payment for purchase of products';
        $returnUrl = env('NGROK_URL');

        # In order to update the order if the payment is complete
        Session::put('orderId', $order->getKey());

        $paygatePortal = "https://paygateglobal.com/v1/page" .
            "?token=$apiToken" . 
            "&amount=$amount" . 
            "&description=$description" . 
            "&identifier=$order->identifier" . 
            "&url=$returnUrl";

        return redirect($paygatePortal);
    }

    public function paymentConfirmation()
    {
        $order = Order::where('identifier', request()->identifier)->first();
        $order->payment_reference = request()->payment_reference;
        $order->status = 'paid';
        $order->save();
        return response()->json();
    }
}
