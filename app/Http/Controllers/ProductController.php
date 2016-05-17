<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductQuota;
use Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $outs = Product::orderBy('updated_at', 'desc')
                          ->leftJoin('config', 'products.type', '=', 'config.id')
                          ->select('products.*','config.name as typeName')
                          ->paginate(30);
        return view('product',['outs'=>$outs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product_form', ['act'=>'产品登记']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ProductStoreRequest $request)
    {
        $input = $request->all();
        $input['createBy'] = intval(Session::get('id'));
        Product::create($input);
        return redirect('/product');
    }

    /**
     * 产品定额
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function quota($id)
    {
        $outs = ProductQuota::where('for',$id)->get();
        return view('product_quota');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
