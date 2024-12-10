<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class TicketCategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function getTicketCategory()
    {
        $ticketCat = TicketCategory::all();
        return response()->json(["ticket_category"=>$ticketCat],200);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|min:3",
                "price" => "required|integer",
                "description" => "nullable",
                "signing"=>Str::random(6)
            ]);
            
            TicketCategory::create([
                "name" => $request->name,
                "price" => $request->price,
                "description" => $request->description,
                "signing"=>Str::random(6)
            ]);
            return response()->json(["message"=>"success"],201);
        } catch (\Throwable $th) {
            return response()->json(["messgage"=>$th->getMessage()]);
        }
    }

    public function update(TicketCategory $ticketCategory, Request $request)
    {
        try {
            $validate = $request->validate([
                "name" => "required|3",
                "price" => "required|integer",
                "description" => "nullable"
            ]);
            $cat = $ticketCategory->update($validate);
            return response()->json(["statut_code"=>"success"],202);
        } catch (\Throwable $th) {
            return response()->json(["messgage"=>$th->getMessage()],$th->getCode());
        }
    }

}
