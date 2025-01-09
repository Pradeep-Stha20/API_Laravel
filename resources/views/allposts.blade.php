<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class=" bg-dark text-white mb-4">
                <h1>All Posts</h1>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-8">
                <a href="/addpost" class="btn btn-sm btn-primary">Add New</a>
                <button class="btn btn-sm btn-danger" id="logoutBtn">Logout</button>
            </div>
        </div>
        <div class="row">
            <div class="row-8">
                <div id="postContainer">
                    <table class="table table-table-bordered">
                        <tr class="table-dark">
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>View</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                        <tr>
                            <td><img src="" width="150px" /></td>
                            <td>
                                <h6>Post Title 1</h6>
                            </td>
                            <td>
                                <p>
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Consequuntur assumenda dolorum accusamus animi nam repellendus, quis eligendi minima nostrum eius sit maiores ut blanditiis consequatur. Totam fuga ipsum officia doloribus.
                                </p>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-primary">View</button></td>
                            <td><button type="button" class="btn btn-sm btn-warning">Update</button></td>
                            <td><button onclick="deletepost(${post.id})" class="btn btn-sm btn-danger">Delete</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <script>
        document.querySelector("#logoutBtn").addEventListener('click',function(){
            const token =localStorage.getItem('api_token')
    
            fetch('/api/logout',{
                method: 'POST',
                headers:{
                    'Authorization': `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data =>{
                console.log(data);
                window.location.href = 'http://localhost:8000/';
            });
        });
        
        function loadData(){
            const token =localStorage.getItem('api_token')
            fetch('/api/posts',{
                method: 'GET',
                headers:{
                    'Authorization': `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data =>{
                console.log(data);
                //window.location.href = 'http://localhost:8000/';
            });
        }
        loadData();
    </script>
  </body>
</html>
