<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use App\Member;

class WebhookController extends Controller
{
    /**
     * Github webhooks
     *
     * @return \Illuminate\Http\Response
     */
    public function GithubWebhook(Request $request)

    {
        $github_signature = $request->header(‘X_HUB_SIGNATURE’);
        $payload = Input::all();
        list($algo, $signature) = explode('=', $github_signature);

        $payload_hash = hash_hmac($algo, $payload, 'king0105');

        if($payload_hash == signature) {
            shell_exec('cd /mnt/may/');
            shell_exec('git pull');
            return 200;
        }else{
             return 'invalid';
        }
        //Member::find(1)->update(['content'=>'fuck']);



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
