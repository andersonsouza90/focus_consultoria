<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>

            $(function(){

                /*$("#btnEntrar").click(function(){
                    alert($("#cnpj").val());
                });*/



            });

        </script>

        <style>
                body {
                    background: #1a202c;
                }

                .btn-login {
                    font-size: 0.9rem;
                    letter-spacing: 0.05rem;
                    padding: 0.75rem 1rem;
                }
        </style>
    </head>

    <body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">

          <div class="form-floating mb-3">
                <img style="width: 130px" src="https://irp-cdn.multiscreensite.com/b2b567e5/images/LogoFocusConsultoriaHorizontal.jpg">
          </div>

            <h5 class="card-title text-center mb-5 fw-light fs-5">Novo Cadastro</h5>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <?php


            ?>

@if (Session::has('post-cadastro'))
        <li>{!! session('post-cadastro') !!}</li>
   @endif
            <form method="post" action="{{route('route.cadastrarPost')}}">
              @csrf
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="nome" placeholder="nome" >
                <label for="nome">Nome</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" name="cnpj" placeholder="CNPJ">
                <label for="cnpj">CNPJ</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" placeholder="Senha">
                <label for="password">Senha</label>
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" id="btnEntrar" type="submit">Cadastrar</button>
                <a href="/" class="btn fw-bold">Cancelar</a>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>



</html>
