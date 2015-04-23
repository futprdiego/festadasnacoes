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

    public function show(Cliente $cliente)
    {
        $nomeForm = 'Clientes';

        return view('clientes.show', compact('nomeForm', 'cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $nomeForm = 'Clientes';

        return view('clientes.edit', compact('nomeForm', 'cliente'));
    }

    public function update(Cliente $cliente, ClientesRequest $request)
    {
        $nomeForm = 'Clientes';

        Cliente::update($request->all());

        return redirect('clientes');
    }

    public function destroy(Cliente $cliente)
    {
        $usuario = User::findOrFail($cliente->id_user);

        $cliente->delete();

        $usuario->delete();

        return redirect('clientes');
    }
}
