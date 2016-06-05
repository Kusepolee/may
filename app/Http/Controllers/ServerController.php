<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;

class ServerController extends Controller
{
    /**
     * Github webhooks
     *
     * @return \Illuminate\Http\Response
     */
    public function GithubWebhook(Request $request)
    {
        // $hook = $request->all();
        // $code = json_decode($hook, true);

        // $signatre = $request->header('X-Hub-Signature');

        // $content = $request->payload;

        // $resault = hash_hmac('sha1', $content, 'king0105');
        $resault = 'git workd';

        Member::find(1)->update(['content'=>$resault]);
        //fuck the github pull

        //Logie::add(['info', $hook]);
        //good
        //return true;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
