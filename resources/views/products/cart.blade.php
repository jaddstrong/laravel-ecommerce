@extends('layouts.app')

@section('content')
<style>
a{
    color:black;
    text-decoration: none;
}
a:hover{
    color:gray;
    text-decoration: none;
}
.card-text{
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
}
h5{
    white-space: nowrap; 
    overflow: hidden;
    text-overflow: ellipsis;
}
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    
                    <h4>Cart</h4>
                </div>

                <div class="card-body">

                    <div class="row">
                        @if(Session::exists('cart'))
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Image</th>
                                        <th>Product Name</th>
                                        <th>Product Quantity</th>
                                        <th>Product Price</th>
                                        <th>Sub-total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $quantity = Session::get('cart'); ?> 
                                    <?php $totally = 0; ?>
                                    @foreach($items as $key)
                                        <tr>
                                            <td><img src="images/{{$key->img}}" height="50px" width="50px"></td>
                                            <td>{{$key->name}}</td>
                                            <td>{{$quantity[$key->id][0][3]}}</td>
                                            <td>&#8369;{{$key->price}}</td>
                                            <?php $total = $quantity[$key->id][0][3] *  $key->price; ?>
                                            <td>&#8369;{{$total}}</td>
                                            <td><button class="btn btn-sm btn-danger remove" id="{{$key->id}}" name="remove">Remove</td>
                                            <?php $totally += $total;  ?>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"><strong>Total:</strong></td>
                                        <td>&#8369;{{$totally}}</td>
                                        <td><button class="btn btn-primary" id="purchase">Purchase</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="mx-auto">
                            <h5>Order products first..</h5>
                            </div>
                        @endif
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal: modalLoader-->
<div class="modal fade" id="loader" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
    <!--Content-->
    <div class="modal-content text-center" style="background-color:transparent;border:none;">
      <!--Body-->
      <div class="modal-body">
            <center><div class="loader"></div></center>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: modalLoader-->

<!--Modal: modalTnx-->
<div class="modal fade" id="modalTnx" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
    <!--Content-->
    <div class="modal-content text-center">
      <!--Header-->
      <div class="modal-header d-flex justify-content-center">
        <a type="button" class="close" aria-label="Close" href="/cart">
            <span aria-hidden="true" class="white-text">&times;</span>
        </a>
      </div>

      <div class="modal-body">
          <label>Thank you for purchasing</label>
      </div>
      <!--Footer-->
      <div class="modal-footer">
        <a type="button" class="btn  btn-primary" href="/user">Continue Shopping</a>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: modalTnx-->

<!--Modal: modalConfirmDelete-->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
    <!--Content-->
    <div class="modal-content text-center">
      <!--Header-->
      <div class="modal-header d-flex justify-content-center">
        <p class="heading"><strong>Are you sure?</strong></p>
      </div>

      <!--Footer-->
      <div class="modal-footer">
        <a href="" class="btn btn-outline-danger" id="yes" data-dismiss="modal">Yes</a>
        <a type="button" class="btn  btn-danger" data-dismiss="modal">No</a>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: modalConfirmDelete-->

<script>
    $('#purchase').click(function(){
        $('#loader').modal("show");
        $.ajax({
            url:'/purchase',
            type:'GET',
            success:function(response){
                $('#loader').modal("toggle");
                $('#modalTnx').modal("show");
            },
            error:function(response){
                alert("fail");
                location.reload(true);
            }
        });
    });

    $('.remove').click(function(e){
        e.preventDefault();
        $('#modalConfirmDelete').modal('show');
        var id = this.id;
        $('#yes').click(function(e){
            e.preventDefault();
            $.ajax({
                url:'/remove',
                type:'GET',
                data:{id:id},
                success:function(){
                    location.reload(true);
                }
            });
        });
    });
</script>
@endsection
