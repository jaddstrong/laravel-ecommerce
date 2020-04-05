<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Product;
use App\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        request()->validate([
            'quantity' => 'required|min:1'
        ]);
        $product = Product::find($request->id);
        $total = $product->price * $request->quantity;
        if ($request->session()->exists('cart')) {
            $data = $request->session()->get('cart');
            $check = false;
            foreach($data as $key)
            {
                if($key[0][0] == $request->id)
                {
                    
                    $total_quantity = $request->quantity + $key[0][3];
                    $request->session()->forget('cart.'.$request->id);
                    $put = $request->session()->push('cart.'.$request->id, [$request->id, $product->name, $product->price, $total_quantity, $total]);
                    $check = true;
                }
            }
            if($check == false){
                $request->session()->push('cart.'.$request->id, [$request->id, $product->name, $product->price, $request->quantity, $total]);
            }
        }else{
            $request->session()->push('cart.'.$request->id, [$request->id, $product->name, $product->price, $request->quantity, $total]);
        }
    }

    public function cart(Request $request)
    {
        if ($request->session()->exists('cart')) {
            $data = $request->session()->get('cart');
            $arr = array();
            foreach($data as $key)
            {
                array_push($arr, $key[0][0]);
            }
            $product = Product::whereIn('id', $arr)->get();
            return view('products.cart')->with('items', $product);
        }

        return view('products.cart');
    }

    public function purchase(Request $request)
    {
        if ($request->session()->exists('cart')) {
            $data = $request->session()->get('cart');
            $items = array();
            $quantity = array();
            
            foreach($data as $key)
            {
                
                array_push($items, $key[0][0]);
                array_push($quantity, $key[0][3]);
            }
            $products = Product::whereIn('id', $items)->get();
            $i = 0;
            foreach($products as $item){
                $logs = new Log;
                $logs->user_id = Auth::user()->id;
                $logs->product_id = $item->id;
                $logs->product_name = $item->name;
                $logs->product_price = $item->price;
                $logs->product_quantity = $quantity[$i];
                $logs->product_total = $quantity[$i] * $item->price;
                $logs->send = false;
                $logs->save();

                $update = Product::find($item->id);
                $stock = $update->stack - $quantity[$i];
                $update->stack = $stock;
                $update->save();
                $i++;
            }

            //Send Mail
            $auth = Auth::user();
            $purchase = Log::where('user_id', Auth::user()->id)->where('send', false)->get();
            Mail::to(Auth::user()->email)->send(new SendMail($purchase,$auth));
            foreach($purchase as $item)
            {
                $send = Log::find($item->id);
                $send->send = true;
                $send->save();
            }
            $request->session()->forget('cart');
        }
        return null;
    }

    public function remove(Request $request)
    {
        $data = $request->session()->get('cart');
        foreach($data as $key)
        {
            // dd($key[0][0]);
            if($key[0][0] == $request->id)
            {
                $request->session()->forget('cart.'.$request->id);
            }
        }
    }

}
