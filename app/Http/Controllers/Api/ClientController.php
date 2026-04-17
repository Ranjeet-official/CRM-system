<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {

       $client = Client::paginate(5);
        return response()->json([
            'status' => true,
            'data' => $client
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'contact_name' => 'required',
            'contact_email' => 'required|email',
            'company_name' => 'required'
        ]);

        $client = Client::create($request->all());
        return response()->json([
          'message' => 'create client data',
          'data' => $client]);
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        $client->update($request->all());

        return response()->json([$client]);
    }

    public function delete($id)
    {
        $client = Client::find($id);

        $client->delete();

        return response()->json([
            'status' => true,
            'message' => "delete client"
        ]);
    }
}
