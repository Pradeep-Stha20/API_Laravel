<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css" />
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


      <!--Update Post Modal  -->
      <div class="modal fade" id="updatePostModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updatePostLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fs-5" id="updatePostLabel">Update Post</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <form id="updateform">
            <div class="modal-body">
                <input type="hidden" id="postId" class="form-control" value="">
                <b>Title</b> <input type="text" id="postTitle" class="form-control" value="">
                <b>Description</b> <input type="text" id="postBody" class="form-control" value="">
                <br>
                <img id="showImage" width="150px">
                <br> <br>
                <p><b>Upload Image</b> <input type="file" id="postImage" class="form-control" value=""></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" value="Save changes" class="btn btn-primary">
            </div>
        </form>
          </div>
        </div>
      </div>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>

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
                            <td><button type="button" class="btn btn-sm btn-success" data-bs-postid ="${post.id}" data-bs-toggle="modal" data-bs-target="#updatePostModal">Update</button></td>
                            <td><button onclick="deletePost(${post.id})" class="btn btn-sm btn-danger">Delete</button></td>
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


        //Update Post
        var updateModal =document.querySelector('#updatePostModal');
        if (updateModal) {
            updateModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            
            // Extract info from data-bs-* attributes
            const id = button.getAttribute('data-bs-postid');
            console.log(id);
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

                document.querySelector('#postId').value = post.id;
                document.querySelector('#postTitle').value = post.title;
                document.querySelector('#postBody').value = post.description;
                document.querySelector('#showImage').src = `/uploads/${post.image}`;
            });
            
        })
        }

        //Update Post Modal
        var updateform = document.querySelector("#updateform");

        updateform.onsubmit = async(e) => {
        e.preventDefault();
        const token = localStorage.getItem('api_token');

        const postId = document.querySelector("#postId").value;
        const title = document.querySelector("#postTitle").value;
        const description = document.querySelector("#postBody").value;

        
        var formData = new FormData();
        formData.append('id', postId);
        formData.append('title', title);
        formData.append('description', description);
        
        if(!document.querySelector("#postImage").files[0] ==""){
            
            const image = document.querySelector("#postImage").files[0];
            formData.append('image', image);
        }

        let response = await fetch(`/api/posts/${postId}`,{
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-HTTP-Method-Override' : 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://127.0.0.1:8000/allposts"
            });


    }

    //Delete Post
    async function deletePost(postId){
        const token = localStorage.getItem('api_token');

        let response = await fetch(`/api/posts/${postId}`,{
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://127.0.0.1:8000/allposts"
            });
    }

    <script>
        $(document).ready(function () {
            var table = $('#myTable').DataTable({
                "ajax": {
                    "url": "{{ route('getall') }}",
                    "type": "GET",
                    "dataType": "json",
                    "headers": {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "dataSrc": function (response) {
                        if (response.status === 200) {
                            return response.employees;
                        } else {
                            return [];
                        }
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "address" },
                    { "data": "phone" },
                    {
                        "data": null,
                        "render": function (data, type, row) {
                            return '<a href="#" class="btn btn-sm btn-success edit-btn" data-id="'+data.id+'" data-name="'+data.name+'" data-email="'+data.email+'" data-address="'+data.address+'" data-phone="'+data.phone+'">Edit</a> ' +
                                   '<a href="#" class="btn btn-sm btn-danger delete-btn" data-id="'+data.id+'">Delete</a>';
                        }
                    }
                ]
            });
    
            // Export to Excel functionality
            $('#exportBtn').click(function() {
    var table = $('#myTable').DataTable();
    var data = table.rows({search: 'applied'}).data();  // Get filtered rows
    var ws_data = [];
    
    // Add company details
    ws_data.push(['Company Name: Xelwel Innovation Pvt. Ltd.']);  // Replace with your company name
    ws_data.push(['Email: info@xelwel.com.np']);  // Replace with your company email
    ws_data.push(['Phone: +977-9843569096']);  // Replace with your company phone number
    ws_data.push([]);  // Empty row for spacing

    // Specify columns you want to export
    var selectedColumns = ['id', 'name', 'email', 'address', 'phone'];

    // Add headers to Excel sheet
    var headers = [];
    selectedColumns.forEach(function(col) {
        headers.push(col.charAt(0).toUpperCase() + col.slice(1)); // Capitalize first letter
    });
    ws_data.push(headers);  // Add headers as the first row

    // Add data rows to Excel sheet
    data.each(function(value, index) {
        var row = [];
        selectedColumns.forEach(function(col) {
            row.push(value[col]);  // Push only the selected columns' data
        });
        ws_data.push(row);  // Add row to the data
    });

    // Convert array of arrays to sheet and export
    var ws = XLSX.utils.aoa_to_sheet(ws_data);  // Convert to sheet

    // Add a company logo (optional)
    // Note: Adding images directly to XLSX is more complex and requires additional libraries like xlsx-populate
    // If this is essential, explore libraries like xlsx-populate or exceljs

    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Employees');  // Append sheet to workbook
    XLSX.writeFile(wb, 'intens.xlsx');  // Export Excel file
});

    
            // Edit button functionality
            $('#myTable tbody').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');
                var address = $(this).data('address');
                var phone = $(this).data('phone');
    
                $('#edit-id').val(id);
                $('#edit-name').val(name);
                $('#edit-email').val(email);
                $('#edit-address').val(address);
                $('#edit-phone').val(phone);
                $('#editModal').modal('show');
            });
    
            // Employee form submit (add new employee)
            $('#employee-form').submit(function (e) {
                e.preventDefault();
                const employeedata = new FormData(this);
    
                $.ajax({
                    url: '{{ route('store') }}',
                    method: 'post',
                    data: employeedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            alert("Saved successfully");
                            $('#employee-form')[0].reset();
                            $('#exampleModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                        }
                    }
                });
            });
    
            // Edit form submit (update employee)
            $('#edit-form').submit(function (e) {
                e.preventDefault();
                const employeedata = new FormData(this);
    
                $.ajax({
                    url: '{{ route('update') }}',
                    method: 'POST',
                    data: employeedata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            alert(response.message);
                            $('#edit-form')[0].reset();
                            $('#editModal').modal('hide');
                            $('#myTable').DataTable().ajax.reload();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
    
            // Delete button functionality
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
    
                if (confirm('Are you sure you want to delete this employee?')) {
                    $.ajax({
                        url: '{{ route('delete') }}',
                        type: 'DELETE',
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                alert(response.message);
                                $('#myTable').DataTable().ajax.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                            alert('Error: ' + error);
                        }
                    });
                }
            });
        });
    </script>
</script>
  </body>
</html>
