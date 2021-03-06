<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Product;
use Auth;
class ProductsController extends Controller
{

    //ADMIN DISPLAY PRODUCTS IN DATA TABLES
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('drop', false)->latest('created_at')->get();
            return Datatables::of($data)
                    // ->editColumn('img', function ($img) {
                    //     return "<img src='images/'".$img->img.">";
                    // })
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<button id="'.$row->id.'" class="edit btn btn-primary btn-sm">View</button>
                                   <button id="'.$row->id.'" class="btn btn-danger btn-sm drop" data-toggle="modal" data-target="#modalConfirmDelete">Drop</button>';
     
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
      
        return view('products.index');
    }

    //ADMIN CREATE PRODUCT
    public function store(Request $request)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stack' => 'required'
        ]);
 
        if ($files = $request->file('image')) {
 
            $filename = $request->file('image')->getClientOriginalName();
            $fileToStore = rand().$filename;
            $request->file('image')->move(public_path('images'), $fileToStore);

            //INSERT PRODUCT TO DATABASE;
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stack = $request->stack;
            $product->img = $fileToStore;
            $product->drop = false;
            $product->save();
             
            return "Created new product.";
        }
        return "Fail to create product";
    }

    //GET DATA'S OF A PRODUCT TO DISPLAY IN MODAL
    public function show(Request $request)
    {
        $product = Product::find($request->id);

        return response()->json($product);
    }

    //ADMIN SET STATUS TO DROP
    public function drop(Request $request)
    {
        $drop = Product::find($request->id);
        // unlink(public_path('images/'.$drop->img));
        $drop->drop = true;
        $drop->save();

        return "Product has been drop.";
    }

    //ADMIN UPDATE A PRODUCT
    public function update(Request $request)
    {  
        request()->validate([
            'name1' => 'required',
            'description1' => 'required',
            'price1' => 'required',
            'stack1' => 'required'
        ]);

        $files = $request->file('image1');
        if (!empty($files)) {

            //STORE IMAGE IN THE DIRECTORY
            $filename = $request->file('image1')->getClientOriginalName();
            $fileToStore = rand().$filename;
            $request->file('image1')->move(public_path('images'), $fileToStore);

            //REMOVE PREVIOUS IMAGE IN DIRECTORY;
            $update = Product::find($request->id);
            unlink(public_path('images/'.$update->img));

            //UPDATE A PRODUCT IMAGE TO DATABASE;
            $update->img = $fileToStore;
            $update->save();
        }

        //UPDATE A PRODUCT TO DATABASE;
        $update = Product::find($request->id);
        $update->name = $request->name1;
        $update->description = $request->description1;
        $update->price = $request->price1;
        $update->stack = $request->stack1;
        $update->save();

    }

    public function display()
    {
        $products = Product::where('drop', false)->where('stack', '!=', 0)->latest('created_at')->paginate(12);

        return view('products.display')->with('products', $products);
    }


    
}
