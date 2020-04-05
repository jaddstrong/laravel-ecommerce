@extends('layouts.app')

@section('content')
    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary create" data-toggle="modal" data-target="#myModal" id="create">Create Product</button>
                </div>

                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stack</th>
                                <th width="70px">Image</th>
                                <th width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Modal -->
 <div id="myModal1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Update Product</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form enctype="multipart/form-data" method="POST" id="upload_image_form1" action="javascript:void(0)">
                <div class="form-group">
                    <label for="name">Product name:</label>
                    <input type="text" id="name1" name="name1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Product description:</label>
                    <input type="text" id="description1" name="description1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Product price:</label>
                    <input type="text" id="price1" name="price1" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stack">Product stack:</label>
                    <input type="number" id="stack1" name="stack1" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="image">Product image:</label><br>
                    <input type="file" id="image1" name="image1">
                </div>
                <div class="form-group">
                    <input type="hidden" id="id" name="id">
                    <center>
                        <img id="image_preview_container1" src="/images/default-pro.jpg" alt="preview image" style="max-height: 200px;">
                    </center>
                </div>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit" id="update">Update</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
        </div>
    </div>

    </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create Product</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form enctype="multipart/form-data" method="POST" id="upload_image_form" action="javascript:void(0)">
                <div class="form-group">
                    <label for="name">Product name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Product description:</label>
                    <input type="text" id="description" name="description" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Product price:</label>
                    <input type="number" id="price" name="price" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stack">Product stack:</label>
                    <input type="number" id="stack" name="stack" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="image">Product image:</label><br>
                    <input type="file" id="image" name="image" required>
                </div>
                <div class="form-group">
                    <center>
                        <img id="image_preview_container" src="/images/default-pro.jpg" alt="preview image" style="max-height: 200px;">
                    </center>
                </div>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
        </div>
    </div>

    </div>
</div>
<!-- Modal -->

<!--Modal: modalConfirmDelete-->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md modal-notify modal-danger" role="document">
    <!--Content-->
    <div class="modal-content text-center">
      <!--Header-->
      <div class="modal-header d-flex justify-content-center">
        <p class="heading"><strong>Are you sure to delete this item?</strong></p>
      </div>

      <!--Footer-->
      <div class="modal-footer">
        <a type="button" href="#" class="btn  btn-danger" data-dismiss="modal">No</a>
        <a href="" class="btn btn-outline-danger" id="yes" data-dismiss="modal">Yes</a>
        
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: modalConfirmDelete-->

<script>
    //DATA TABLES
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},        
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'price', name: 'price'},
            {data: 'stack', name: 'stack'},
            {data: 'img', render: function(data, type, row){ 
                return "<img src = 'images/"+data+"' width='70' height='60'>"; 
            }},    
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#create').click(function(){
        $('#image_preview_container').attr('src', 'images/default-pro.jpg'); 
    });
        
    //CHANGE IMAGE UPON CHOOSING NEW IMAGE-->CREATE MODAL
    $('#image').change(function(){
        
        let reader = new FileReader();
        reader.onload = (e) => { 
        $('#image_preview_container').attr('src', e.target.result); 
        }
        reader.readAsDataURL(this.files[0]); 

    });

    //CHANGE IMAGE UPON CHOOSING NEW IMAGE-->UPDATE MODAL
    $('#image1').change(function(){

        let reader = new FileReader();
        reader.onload = (e) => { 
        $('#image_preview_container1').attr('src', e.target.result); 
        }
        reader.readAsDataURL(this.files[0]); 

    });

    //CREATE A PRODUCT
    $('#upload_image_form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "/product",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {
                this.reset();
                table.ajax.reload();
                $('#myModal').modal('toggle');
            },
            error: function(data){
                alert("fail");
                $('#myModal').modal('toggle');
            }
        });
    });

    //UPDATE A PRODUCT
    $('#upload_image_form1').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: "/product/update",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {
                this.reset();
                table.ajax.reload();
                $('#myModal1').modal('toggle');
            },
            error: function(data){
                alert("fail");
                $('#myModal1').modal('toggle');
            }
        });
    });

    //GET DATA'S OF A PRODUCT
    $(".data-table").on('click', '.edit', function(e){
        e.preventDefault();
        var id = this.id;
        $.ajax({
            url:"/product/show",
            type:"GET",
            data:{id:id},
            success:function(response){
                $('#id').val(response.id);
                $('#name1').val(response.name);
                $('#description1').val(response.description);
                $('#price1').val(response.price);
                $('#stack1').val(response.stack);
                $('#image_preview_container1').attr('src', '/images/'+response.img);
                $('#myModal1').modal("show");
            }
        });
    });

    //SET PRODUCT STATUS TO DROP
    $(".data-table").on('click', '.drop', function(e){
        e.preventDefault();
        var id = this.id;
        $('#yes').click(function(e){
            e.preventDefault();
            $.ajax({
                url:"/product/drop",
                type:"POST",
                data:{id:id},
                success:function(response){
                    table.ajax.reload();
                }
            });
        });
    });

    
</script>   
@endsection