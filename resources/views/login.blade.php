<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AJAX API CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="email" id="email" class="form-control" placeholder="email">
                        </div>
                        <div class="mb-3">
                            <input type="password" id="password" class="form-control" placeholder="password">
                        </div>
                        <button class="btn btn-primary" id="loginButton">Login</button>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $("#loginButton").on('click',function(){
            const email = $("#email").val();     
            const password = $("#password").val();     

            $.ajax({
                url : '/api/login',
                type : 'POST',
                contentType:'application/json',
                data: JSON.stringify({
                    email : email,
                    password : password,
                }),
                success: function(response){
                    console.log(response);

                    localStorage.setItem('api_token',response.token);
                    window.location.href = "/allposts";
                },
                error:function(xhr,status,error){
                    alert('Error:'+ xhr.responseText);
                }
            });

            });
        });
    </script>
</body>
</html>