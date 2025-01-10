<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
        <!-- Header Row -->
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-primary">Create Post</h1>
            </div>
        </div>
    
        <!-- Form Row -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <form id="addform" class="card p-4 shadow-sm">
                    <!-- Title Input -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" class="form-control" placeholder="Enter title">
                    </div>
    
                    <!-- Description Input -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" class="form-control" rows="5" placeholder="Enter description"></textarea>
                    </div>
    
                    <!-- Image Input -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input type="file" id="image" class="form-control">
                    </div>
    
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/allposts" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script>
    var addform = document.querySelector("#addform");

    addform.onsubmit = async(e) => {
        e.preventDefault();
        const token = localStorage.getItem('api_token');

        const title = document.querySelector("#title").value;
        const description = document.querySelector("#description").value;
        const image = document.querySelector("#image").files[0];

        var formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('image', image);

        let response = await fetch('/api/posts',{
                method: 'POST',
                body: formData,
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
  </script>
</body>
</html>