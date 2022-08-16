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
                <li><a href="/upload">Upload</a></li>
                <li><a href="#">Contato</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Sair</a></li>
            </ul>
            </div>
        </div>
    </nav>



	<div class="container">
		<div class="row">
			<div id="form-login">
                @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

				<form class="form-horizontal well" action="{{route('route.importar')}}"
                method="post" name="upload_excel" enctype="multipart/form-data">
                @csrf
					<fieldset>
						<legend>Upload CSV/Excel</legend>

                        <div class="form-floating mb-3">
                            <input type="file" name="arquivo" id="arquivo" class="input-large">
                            <!-- <label>CSV/Excel Arquivo:</label> -->
                        </div>

						<!---div--- class="control-group">
							<div class="control-label">
								<label>CSV/Excel Arquivo:</label>
							</div>
							<div class="controls">
								<input type="file" name="file" id="file" class="input-large" required="">
							</div>
						</!---div--->

                        <br>

                        <div class="d-grid">
                            <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit" id="submit" name="Import">
                                Enviar
                            </button>
                            <h4 id="linkArquivo">
                                <a href="downloadExemplo" target="_blank">Arquivo de Exemplo</a></h4>
                        </div>

					</fieldset>
				</form>

			</div>
		</div>
	</div>


	</body>
</html>
