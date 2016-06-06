<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class WebhookController extends Controller
{
    /**
     * Github webhooks
     *
     * @return \Illuminate\Http\Response
     */
    public function GithubWebhook(Request $request)

    {
        $github_signature = @$_SERVER['HTTP_X_HUB_SIGNATURE'];
        $payload = file_get_contents('php://input');

        $arr = explode('=', $github_signature);
        $algo = $arr[0];
        $signature = $arr[1];

        $payload_hash = hash_hmac($algo, $payload, 'king0105');

        if($payload_hash != $signature) return 'invalid key!';
        
        shell_exec('cd /mnt/may/  && git pull');
        //shell_exec('git pull'); //?
        return 200;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function test()
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
