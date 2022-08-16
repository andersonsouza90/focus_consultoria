<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(){

        if(Session('userAuth')){
            return view('upload.index');
        }

        return redirect("/");

    }

    public function donwloadExemplo(){

        if(Session('userAuth')){

            if(Storage::disk('local')->exists("exemploNew.xlsx")){

                $path = Storage::disk('local')->path("exemploNew.xlsx");
                $content = file_get_contents($path);
                return response($content)->withHeaders([
                    'Content-type' => mime_content_type($path)
                ]);

            }
            return redirect('/404');

        }

        return redirect("/");

    }


    public function importar(Request $req){

        if(!Session('userAuth')){
            return redirect("/");
        }

        $dados = $req->validate([
                'arquivo'=> 'required|mimes:xlsx, csv, xls'
            ],
            [
                'arquivo.required' => "Informe o arquivo!",
                'arquivo.mimes' => "O arquivo deve ser do tipo xls ou xlsx conforme o exemplo para download!"
            ]
        );

        if($req->arquivo){

            $extensao = $req->arquivo->getClientOriginalExtension();
            $diretorio = "uploads_arquivos/diretorio-".session('userAuth')['id_usuario'];

            //$path_upload = $req->arquivo->storeAs($diretorio, 'planilha-'.session('userAuth')['id_usuario'].".{$extensao}");

            $path_upload = $req->arquivo->store($diretorio);
        }


        var_dump(session('userAuth')['id_usuario']);
        dd($path_upload);




    }


}
