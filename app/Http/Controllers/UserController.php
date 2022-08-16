<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class UserController extends Controller
{


    public function autenticacao(Request $request) {

        $credentials = $request->validate([
                'cnpj' => ['required', 'min:2'],
                'password' => ['required'],
            ],
            ['password.required' => "Informe a senha!",
            'cnpj.required' => "Informe o CNPJ!"
            ]
        );

        if (Auth::attempt($credentials)) {

            Session::put('userAuth', Auth::user());
            Auth::login(Auth::user());

            return redirect()->intended('home');
        }

       return back()->withErrors([
           'cnpj' => 'Chave de acesso CNPJ e senha não encontrados.',
        ])->onlyInput('cnpj');
    }

}
