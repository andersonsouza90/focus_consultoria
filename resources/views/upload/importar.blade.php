<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<title>XML RPS - Vitória - ES</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="XML">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <style>
            /* Remove the navbar's default margin-bottom and rounded borders */
            .navbar {
                margin-bottom: 0;
                border-radius: 0;
            }

            body {
                    background: #1a202c;
                }

                .container{
                    padding-top: 20px;
                }

                #linkArquivo{
                    display: inline;
                    margin-left: 10%;
                }

                html, body {
                    height: 90%;
                    }

                    .full-height {
                    height: 100%;
                    }
        </style>

	</head>
	<body>
	<nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <img style="width: 130px" src="https://irp-cdn.multiscreensite.com/b2b567e5/images/LogoFocusConsultoriaHorizontal.jpg">

            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                  <li class="active"><a href="#">Home</a></li>
                  <li><a href="#">Meus Dados</a></li>
                  <li><a href="{{route('route.upload')}}">Upload</a></li>
                  <li><a href="#">Contato</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="{{route('route.logout')}}"><span class="glyphicon glyphicon-log-out"></span> Sair</a></li>
                </ul>
              </div>
        </div>
    </nav>

    <div class="jumbotron full-height">
        <div class="container text-center">
          <h3>XML</h3>
        </div>
        <div class="container">
            <div class="row">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <?= die; ?>
                @endif



                <div class="alert alert-success" role="alert">
                    Upload efetuado com sucesso!
                </div>

                <div class="alert alert-light" role="alert" style="font-size: 16px;">
                    <a href="downloadArquivoImportado/{{$retornoGeraXML['nome_arquivo']}}" target="_blank">Clique aqui para acessar o arquivo importado.</a> <br>
                    <a href="downloadXML/{{$retornoGeraXML['xml_file_name']}}" target="_blank">
                        Documento XML gerado com total de {{$retornoGeraXML['QuantidadeRps']}} RPS</a> <br>
                    <a href="http://nfse.vitoria.es.gov.br/" target="_blank">Validador de arquivos RPS (no formato XML)</a>

                </div>
            </div>

        </div>
    </div>




	</body>
</html>
