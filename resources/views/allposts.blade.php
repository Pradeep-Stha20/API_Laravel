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
                    
                </div>
            </div>
        </div>

    </div>

    {{-- Single Post model --}}
    <div class="modal fade" id="singlePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="singlePostLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fs-5" id="singlePostLabel">Single Post</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

    <script>
        document.querySelector("#logoutBtn").addEventListener('click',function(){
            const token =localStorage.getItem('api_token');
    
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
                //console.log(data.data.posts);
                var allpost = data.data.posts;
                const postContainer = document.querySelector('#postContainer');
                //window.location.href = 'http://localhost:8000/';

                var tabledata = `<table class="table table-table-bordered">
                        <tr class="table-dark">
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>View</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>`;
                        
                        allpost.forEach(post => {
                            tabledata += `<tr>
                            <td><img src="/uploads/${post.image}" width="150px" /></td>
                            <td>
                                <h6>${post.title}</h6>
                            </td>
                            <td>
                                <p>
                                ${post.description}
                                </p>
                            </td>
                            <td><button type="button" class="btn btn-sm btn-primary" data-bs-postid ="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal">View</button></td>
                            <td><button type="button" class="btn btn-sm btn-warning" data-bs-postid ="${post.id}" data-bs-toggle="modal" data-bs-target="#updatePostModal">Update</button></td>
                            <td><button type="button" class="btn btn-sm btn-danger" data-bs-postid ="${post.id}" data-bs-toggle="modal" data-bs-target="#singlePostModal">Delete</button></td>
                        </tr>`
                        });
                        
                        tabledata += `</table>`;

                        postContainer.innerHTML = tabledata;
            });
        }
        loadData();

        //open single post modal
        var singleModal =document.querySelector('#singlePostModal');
        if (singleModal) {
        singleModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            
            const modalBody =document.querySelector("#singlePostModal .modal-body");
                modalBody.innerHTML = "";
            // Extract info from data-bs-* attributes
            const id = button.getAttribute('data-bs-postid')
            const token =localStorage.getItem('api_token');
    
            fetch(`/api/posts/${id}`,{
                method: 'GET',
                headers:{
                    'Authorization': `Bearer ${token}`,
                    'Content-Type' : 'application/json'
                }
            })
            .then(response => response.json())
            .then(data =>{
                const post = data.data.post[0];

                
                modalBody.innerHTML = `
                Title : ${post.title}
                <br>
                Description : ${post.description}
                <br>
                <br>
                <img width="150px" src="http://localhost:8000/uploads/${post.image}" />
                `;
            });
            
        })
        }
</script>
  </body>
</html>
