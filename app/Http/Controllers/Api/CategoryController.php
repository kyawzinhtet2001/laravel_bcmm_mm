<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
/**
 * This is for category
 * @author kyawzinhtet
 * @create 26/5/2022s
 */
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author kyaw zin htet
     * @create 26/5/2022
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
            #check its null
        if ($category->isNotEmpty()) {
            return response()->json(['status' => 'OK', "data" => $category]);
        }
        return response()->json(['status' => 'NG', "message" => "Data is not found"]);
    }

    /**
     * Store a newly created resource in storage.
     * @author kyaw zin htet
     * @create 26/5/2022
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data = [
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::beginTransaction();
        try {
            $duplicate_data = Category::where('name', $validated_data['name'])->first();
            if ($duplicate_data != null) {
                return response()->json(["status" => "NG", "message" => "Category has already added."]);
            }
            Category::insert($validated_data);
            DB::commit();
            return response()->json(["status" => "OK", "message" => "Category is Created."]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return response()->json(["status" => "NG", "message" => "Category is not Created."]);
        }
    }

    /**
     * Display the specified resource.
     * @author kyaw zin htet
     * @create 26/5/2022
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $category = Category::where("id", $id)->get()->first();
            #check its null
        if ($category != null) {
            return response()->json(['status' => 'OK', "data" => $category]);
        }
        return response()->json(['status' => 'NG', "message" => "Data Not Found."]);
    }

    /**
     * Update the specified resource in storage.
     * @author kyaw zin htet
     * @create 26/5/2022
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated_data = [
            'name' => $request->name,
            'updated_at' => now()
        ];
        $category = Category::where('id', $id)->get()->first();
        DB::beginTransaction();
        try {
            DB::enableQueryLog();
            #check its null
            if ($category != null) {
                $category->update($validated_data);
                DB::commit();
                return response()->json(["status" => "OK", "message" => "Category is Updated."]);
            }
            throw new Exception("Category not found");
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return response()->json(["status" => "NG", "message" => "Category is not Found."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @author kyaw zin htet
     * @create 26/5/2022
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->get()->first();
        DB::beginTransaction();
        try {
            #check its null
            if ($category != null) {
                $category->delete();
                DB::commit();
                return response()->json(["status" => "OK", "message" => "Category is Deleted."]);
            }
            throw new Exception("Category not found");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["status" => "NG", "message" => "Category is not Found."]);
        }
    }
}
