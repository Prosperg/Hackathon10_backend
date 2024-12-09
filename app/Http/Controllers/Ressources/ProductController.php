<?php

namespace App\Http\Controllers\Ressources;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productModelRepo;
    public function __construct(ProductRepository $productR)
    {
        $this->middleware('auth:api');
        $this->productModelRepo = $productR;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = $this->productModelRepo->getAll();
        } catch (\Throwable $th) {
            dd($th);
            // return response()->json(["error"=>$th]);
        }
        return response()->json(["products" => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->productModelRepo->create($request);
        return response()->json(["message" => "Product succesful created"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productModelRepo->getById($id);
        if (!$product) {
            return response()->json(["message" => "Le produit est introuvable."], 404);
        }
        return response()->json(["product" => $product], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {  
        // dd($request);
        if (Product::where('id',$id)->exists()) {
            $this->productModelRepo->update($request,$id);
            return response()->json(["message" => "Product succesfully updated"], 200);

        } else {
            return response()->json(["message"=>"Product not found"],404);
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Product::where('id',$id)->exists()) {
            $this->productModelRepo->delete($id);
            return response()->json(["message"=>"Product deleted."],202);

        } else {

            return response()->json(["message"=>"Product not found."],404);

        } 
    }
}
