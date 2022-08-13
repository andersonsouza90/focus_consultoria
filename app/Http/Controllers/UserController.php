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

            var_dump(Auth::check());
            Session::put('user', Auth::user());
            Auth::login(Auth::user());

            var_dump(Auth::user());

            //dd('logou');
            //$request->session()->regenerate();

            return redirect()->intended('home');
        }

       return back()->withErrors([
           'cnpj' => 'Chave de acesso CNPJ e senha não encontrados.',
        ])->onlyInput('cnpj');
    }

}
