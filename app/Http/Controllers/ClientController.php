<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::paginate(5);

        return view('clients.index',[
            'clients' => $clients,
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('clients.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientStoreRequest $request)
    {
         Client::create($request->validated());

        //  dd($request->validated());

         return redirect()->route('clients.index')->with('success','Client Create Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        $client->update($request->validated());

         return redirect()->route('clients.index')->with('success','Client Create Successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted!');
    }
}
