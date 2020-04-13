@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        @can('create-roles')
                            <button class="btn btn-md btn-primary" id="create" data-toggle="modal" data-target="#myModal">Create User</button>
                        @endcan  
                    </div>
                    <h5>Role Management</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="200px">Role</th>
                                <th>Permissions</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{$role->name}}</td>
                                    <td>
                                        @foreach($role->permissions as $permission)
                                            {{$permission->name}} ||
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('view-roles')
                                            <a href="" role="button" class="btn btn-sm btn-primary view" id="{{ $role->id }}">View</a>
                                        @endcan
                                        @can('delete-roles')
                                            <a href="#" role="button" class="btn btn-sm btn-danger delete" id="{{ $role->id }}" data-toggle="modal" data-target="#modalConfirmDelete">Delete</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
`
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <form id="form-table">
                        <div class="form-group">
                            <strong for="name">Role name:</strong><span id="error_role" style="color:red;"></span>
                            <input class="form-control" type="text" name="name" id="name">
                            <input type="hidden" id="id">
                        </div>
                        <div class="form-group">
                            <strong for="checkbox">Select permissions:</strong><span id="error_permission" style="color:red;"></span>
                            <div id="permission">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="id">
                @can('create-roles')
                    <button type="button" class="btn btn-primary" id="submit">Submit</button>
                @endcan
                @can('edit-roles')
                    <button type="button" class="btn btn-primary" id="update">Update</button>
                @endcan
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
      
    </div>
</div>



<script>
$( document ).ready(function(){
    $.get("/roles/permission",function(response){
        $.each(response, function(index, value) {
            $('#permission').append("<div class='checkbox'><input type='checkbox' id='checkbox"+value.id+"' class='checkbox' value='"+value.id+"'>"+value.name+"</label></div>");
        });
    });

    $('#create').click(function(){
        $('#form-table')[0].reset();
        $('#error_role').text("");
        $('#error_permission').text("");
    });

    $('#submit').click(function(e){
        e.preventDefault();
        var role = $('#name').val();
        var permission = [];
        $(':checkbox:checked').each(function(i){
            permission[i] = $(this).val();
        });
        $.ajax({
            url:"/roles/store",
            type: "POST",
            data:
            {
                role: role,
                permission: permission
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

    $('.view').click(function(e){
        e.preventDefault();
        $('#submit').hide();
        $('#update').show();
        $('.modal-title').text('View Role');
        $('.checkbox').prop('checked', false);
        $('#error_role').text("");
        $('#error_permission').text("");
        var id = this.id;

        $.get("/roles/show",{id:id},function(response){
            $('#name').val(response.name);
            $('#id').val(response.id);
            $.each(response.permissions, function(index, value) {
                $('#checkbox'+value['id']).prop('checked', true);
            });
            $('#myModal').modal('show');
        });
    });

    $('#update').click(function(e){
        e.preventDefault();
        var id = $('#id').val();
        var role = $('#name').val();
        var permission = [];
        $(':checkbox:checked').each(function(i){
            permission[i] = $(this).val();
        });
        $.ajax({
            url:"/roles/update",
            type:"POST",
            data:
            {
                id: id,
                role:role,
                permission: permission
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
            },
        });
    });

    $('.delete').click(function(e){
        e.preventDefault();
        $('#modalConfirmDelete').modal('show');
        var id = this.id;
        $('#yes').click(function(e){
            e.preventDefault();
            $.post("/roles/delete",{id:id},function(response){
                location.reload(true);
            });
        })
    });
});
</script>
@endsection
