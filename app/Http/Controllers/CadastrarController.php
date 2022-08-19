<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CadastrarController extends Controller
{
    public function cadastrar(){

        return view('auth.cadastro');

    }

    public function cadastrarPost(Request $req){

        $retornoCadastro = [];

        $dados = $req->validate([
                'razaoSocial' => ['required', 'min:5'],
                'fantasia' => ['required', 'min:5'],
                'cnpj' => ['required', 'min:3'],
                'enderecoCompleto' => ['required'],
                'telefone' => ['required', 'min:10'],
                'email' => ['required', 'email:rfc,dns'],
                'password' => ['required', 'min:3']
            ],
            [
                'razaoSocial.required' => "Informe a Razão Social",
                'fantasia.required' => "Informe a fantasia",
                'enderecoCompleto.required' => "Informe o Endereco Completo",
                'password.required' => "Informe a senha!",
                'telefone.required' => "Informe o telefone!",
                'email.required' => "Informe o email!",
                'cnpj.required' => "Informe o CNPJ!"
            ]
        );

        try{

            $user = new User;
            $user->razao_social=$req->razaoSocial;
            $user->fantasia=$req->fantasia;
            $user->cnpj=$req->cnpj;
            $user->endereco=$req->enderecoCompleto;
            $user->telefone=$req->telefone;
            $user->email=$req->email;
            $user->password=Hash::make($req->password);
            $user->save();

        }catch (\Exception $e) {

            $retornoCadastro['tipo_alerta'] = 'danger';
            $retornoCadastro['mensagem'] = 'Erro ao cadastrar seu usuário.';
            return redirect()->route('route.login')->with('cadastro', $retornoCadastro);
        }


        $retornoCadastro['tipo_alerta'] = 'success';
        $retornoCadastro['mensagem'] = 'Cadastro Realizado com sucesso! Entre com sua senha.';

        return redirect()->route('route.login')->with('cadastro', $retornoCadastro);
        //return view("home")->with('cadastroRealizado', "Cadastro Realizado com sucesso!");

    }
}
