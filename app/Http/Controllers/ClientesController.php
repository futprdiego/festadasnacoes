<?php namespace App\Http\Controllers;

use App\Cliente;
use App\Http\Requests;
use App\Http\Requests\ClientesRequest;
use App\User;
use Illuminate\Support\Facades\Auth;


class ClientesController extends Controller {

    public function __construct()
    {
        $this->middleware('administrador', ['except' => ['index', 'show']]);
    }
    
    public function index()
    {
        $nomeForm = 'Clientes';

        $clientes = Cliente::all();

        return view('clientes.index', compact('nomeForm', 'clientes'));
    }

    public function create()
    {
        $nomeForm = 'Clientes';

        return view('clientes.create', compact('nomeForm'));
    }

    public function store(ClientesRequest $request)
    {
        $dados = $request->all();

        $usuario = [
            'name' => $dados['nome'],
            'email' => $dados['email'],
            'password' => bcrypt($dados['password']),
            'id_users_tipo' => 3
        ];

        User::create($usuario);

        $dados['id_user'] = User::orderby('created_at', 'desc')->first()->id;

        Cliente::create($dados);

        return redirect('clientes');
    }

    public function show($id)
    {
        $nomeForm = 'Clientes';
        $cliente = Cliente::findOrFail($id);

        return view('clientes.show', compact('nomeForm', 'cliente'));
    }

    public function edit($id)
    {
        $nomeForm = 'Clientes';
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('nomeForm', 'cliente'));
    }

    public function update($id, ClientesRequest $request)
    {
        $nomeForm = 'Clientes';
        $cliente = Cliente::findOrFail($id);

        Cliente::update($request->all());

        return redirect('clientes');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $usuario = User::findOrFail($cliente->id_user);

        $cliente->delete();

        $usuario->delete();

        return redirect('clientes');
    }
}