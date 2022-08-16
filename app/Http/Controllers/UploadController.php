<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Http\Controllers\ValidacaoArquivoController;

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
                // return response($content)->withHeaders([
                //     'Content-type' => mime_content_type($path)
                // ]);
                return response()->download(storage_path().'/app/exemploNew.xlsx');

            }
            return redirect('/404');

        }

        return redirect("/");

    }


    public function importar(Request $req){

        if(!Session('userAuth')){
            return redirect("/login");
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

            ini_set('memory_limit','256M');

            $extensao = $req->arquivo->getClientOriginalExtension();
            $diretorio = "uploads_arquivos/diretorio-".session('userAuth')['id_usuario'];

            //$path_upload = $req->arquivo->storeAs($diretorio, 'planilha-'.session('userAuth')['id_usuario'].".{$extensao}");

            $path_upload = $req->arquivo->store($diretorio);

            $rows = Excel::toArray(new UsersImport, $path_upload)[0];

            if(count($rows[0]) <= 1){

                return back()->withErrors([
                    'arquivo' => 'Arquivo Vazio.',
                 ]);

            }

            //var_dump(count($rows[0]));

            $validacaoController = new ValidacaoArquivoController;
            $return = $validacaoController->validacao($rows);

            if($return['countErro'] > 0){
                return back()->withErrors([
                    'totalErros' => "Total de colunas para checar " . $return['countErro'],
                    'msg' => $return['msg']

                 ]);
             }

             var_dump('agora validar os dados');
            dd($return); die;
        }







    }


}
