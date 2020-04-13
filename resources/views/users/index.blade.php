@extends('layouts.app')

@section('content')
<style>
    span{
        color: red;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        @can('create-users')
                            <button class="btn btn-md btn-primary" id="create" data-toggle="modal" data-target="#myModal">Create User</button>
                        @endcan
                    </div>
                    <h5>User Management</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $key => $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @if(!empty($user->roles[0]))
                                        <td>{{ $user->roles[0]->name }}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        @can('view-users')
                                            <a href="#" role="button" class="btn btn-sm btn-primary view" id="{{ $user->id }}">View</a>
                                        @endcan
                                        @can('delete-users')
                                            <a href="#" role="button" class="btn btn-sm btn-danger delete" id="{{ $user->id }}" data-toggle="modal" data-target="#modalConfirmDelete">Delete</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-group">
                        <strong for="name">User name:</strong><span id ="error_name"></span>
                        <input class="form-control" type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <strong for="name">User email:</strong><span id ="error_email"></span>
                        <input class="form-control" type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group" id="note">
                        <p><strong>Note:</strong><small> The password fields are for changing password. Leave blank if not.</small></p>
                    </div>
                    <div class="form-group" id="pass">
                        <strong for="name">User password:</strong><span id ="error_password"></span>
                        <input class="form-control" type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group" id="conpass">
                        <strong for="name">User confirm password:</strong>
                        <input class="form-control" type="password" name="confirm-password" id="confirm-password" required>
                    </div>
                    <div class="form-group">
                        <strong for="name">User Role:</strong><span id ="error_role"></span>
                        <select class="form-control" id="role" name="role">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="id">
                @can('create-users')
                    <button type="button" class="btn btn-primary" id="submit">Submit</button>
                @endcan
                @can('edit-users')
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                @endcan
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div>

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
$( document ).ready(function(){
    $.get("/user/role",function(response){
        $.each(response, function(index, value) {
            $('#role').append("<option id ="+value+" name="+value+">"+value+"</option>");
        });
    });

    $('#create').click(function(){
        $('#title').text("Create User");
        $('#name').val("");
        $('#email').val("");
        $('#password').val("");
        $('#confirm-password').val("");
        $('#note').hide();
        $('#submit').show();
        $('#update').hide();
    });

    $('#submit').click(function(e){
        e.preventDefault();
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm-password').val();
        var role = $('#role').val();

        $.ajax({
            url:"/user/store", 
            type:"POST",
            data:
            {
                name: name,
                email: email,
                password: password,
                confirmPassword: confirmPassword,
                role: role
            },
            success:function(response)
            {
                location.reload(true);
            },
            error:function(response)
            {
                var errors = "";
                var msg = $.parseJSON(response['responseText']);
                $.each(msg['errors'], function(index, value) {
                    $('#error_'+index).text(value[0]);
                });
            }
        });
    });

    $('.view').click(function(){
        var id = this.id;
        $('#note').show();
        $('#submit').hide();
        $('#update').show();
        $.get("/user/show",{id:id},function(response){
            $('#title').text("View User");
            $('#name').val(response[0].name);
            $('#email').val(response[0].email);
            $('#id').val(response[0].id);
            if(response[0].roles[0] != null){
                $('#role').val(response[0].roles[0].name);
            }
            $('#myModal').modal("show");
        });
    });

    $('#update').click(function(e){
        e.preventDefault();
        var id = $('#id').val();
        var name = $('#name').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm-password').val();
        var role = $('#role').val();
        $.ajax({
            url:"/user/update",
            type:"POST",
            data:
            {
                id: id,
                name: name,
                email: email,
                password: password,
                confirmPassword: confirmPassword,
                role: role
            },
            success:function(response)
            {
                location.reload(true);
            },
            error:function(response)
            {
                var errors = "";
                var msg = $.parseJSON(response['responseText']);
                $.each(msg['errors'], function(index, value) {
                    $('#error_'+index).text(value[0]);
                });
            }
        });
    });

    $('.delete').click(function(){
        var id = this.id;
        $('#yes').click(function(){
            $.post("/user/delete", {id:id},function(response){
                location.reload(true);
            });
        });
    });
});
</script>
@endsection
