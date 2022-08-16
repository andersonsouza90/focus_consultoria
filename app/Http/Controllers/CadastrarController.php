<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CadastrarController extends Controller
{
    public function cadastrar(){

        return view('auth.cadastro');

    }

    public function cadastrarPost(Request $req){

        \Session::flash('post-cadastro', $req['nome']);

        $dados = $req->validate([
            'nome' => ['required', 'min:5'],
            'cnpj' => ['required', 'min:2'],
            'password' => ['required'],
        ],
        ['password.required' => "Informe a senha!",
        'cnpj.required' => "Informe o CNPJ!",
        'nome.required' => "Informe o nome!"
        ]
    );

        dd($req);

    }
}
