<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::all();

        if($products){
            return  response()->json($products , 200);
        }

        return  response()->json([
            "msg" =>"Products Not Found"
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $product = $request->validate([
            "nom"=>["required"],
            "description"=>["required"],
            "prix"=>["required"],
        ]);


        if($product){
            Product::create($product);
            return response()->json(["msg" => "Product created"], 201);
        }

        return response()->json(["mas"=> "Can't Creat product"], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if($product){

            return response()->json([$product], 200);
        }
        return response()->json(["msg" => "Product Not Found"], 400);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
         $data =  $request->validate([
            "nom"=>["required"],
            "description"=>["required"],
            "prix"=>["required"],
        ]);

        $product = Product::find($id);

        if($product){
            $product->update($data);
            return response()->json(["msg" => "Product Updated"], 200);
        }
        return response()->json(["msg" => "Product Not Found"], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if($user->tokenCan('user')){
            return  response()->json(["msg" => "Just Admin Can Delete Product"]);
        }


        $product = Product::find($id);

        if($product){
            $product->delete();
            return response()->json(["msg" => "Product Deleted"], 200);
        }
        return response()->json(["msg" => "Cant Delete product"], 400);
    }
}
