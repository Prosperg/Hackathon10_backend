<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductRepository 
{
    protected $modelProduct;

    public function __construct(Product $modelProduct)
    {
        $this->modelProduct = $modelProduct;
    }

    public function getAll()
    {
        return $this->modelProduct->with(['categorie', 'images'])->get();
    }

    public function getById($id)
    {
        return $this->modelProduct->with(['categorie', 'images'])->find($id);
    }

    public function create($request)
    {
        $request->validate([
            "name" => 'required|min:3',
            "description" => 'required|min:5',
            "price" => 'required',
            "categorie_id" => 'required',
            "image_path" => 'required'
        ]);
        // dd($request);
        $product = $this->modelProduct::create([
            "name" => $request->name,
            "description" => $request->description,
            "price" => $request->price,
            "categorie_id" => $request->categorie_id,
        ]);

        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $imageFile) {
                $fileName = time() . '_' . $imageFile->getClientOriginalName();
                $filePath = $imageFile->storeAs('product', $fileName, 'public');
                $product->images()->create([
                    "path" => $filePath
                ]);
            }
        }

    }

    public function update($request, $id)
    {
        $product = $this->modelProduct::find($id);
        $product->name = is_null($request->name) ? $product->name : $request->name;
        $product->description = is_null($request->description) ? $product->description : $request->description;
        $product->price = is_null($request->price) ? $product->price : $request->price;
        $product->categorie_id = is_null($request->categorie_id) ? $product->categorie_id : $request->categorie_id;
            
        $product->save();
        // $images = ProductImage::where('product_id',$id)->get();
        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $imageFile) {
                $fileName = time() . '_' . $imageFile->getClientOriginalName();
                $filePath = $imageFile->storeAs('product', $fileName, 'public');

                //Mettre à jour les images si elles existent
                $images = ProductImage::where('product_id',$id)->get();
                foreach ($images as $image) {
                    $image->product_id = $id;
                    $image->path = $filePath;

                    $image->save();
                }
                    // $product->images()->create([
                    //     "path" => $filePath
                    // ]);
            }
        }
    }

    public function delete($id)
    {
        $product = $this->modelProduct::find($id);
            $product->delete();

        $images = ProductImage::where('product_id',$id)->get();
        foreach ($images as $image) {
            $image->delete();
        }

    }
}
