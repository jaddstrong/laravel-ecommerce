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
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" id="card">
                <div class="card-header">Products</div>

                <div class="card-body">
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-md-3" style="padding:5px;">
                                <a href="#" id="{{$product->id}}" class="show">
                                    <div class="card" style="border:1px solid;">
                                        <img class="card-img-top" src="images/{{$product->img}}" style="max-height:150px;padding:1px;"/><hr>
                                        <div class="card-block" style="padding:3px;margin-top:-20px;">
                                            <h5 class="card-title">
                                                {{$product->name}}
                                            </h5>
                                            <p class="card-text" style="margin-top:-10px;">
                                                {{$product->description}}<br>
                                                &#8369;{{$product->price}}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Product information</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <center>
                <img id="image_preview_container" src="" alt="preview image" style="max-height: 200px;">
            
            </center>
            <div class="col-lg-12">
                <center>
                <h5 id="name"></h5>
                <p id="des"></p>
                </center>
                <div class="float-right">
                    <strong for="quantity">Quantity:</strong>
                    <input type="number" name="quantity" id="quantity" style="width:50px;" min="0" max="" value="1">
                    <input type="hidden" id="product_id" name="product_id">
                </div>
                    <p><strong for="price">Price:</strong> &#8369;<span id="price"></span></p><hr>
                    <p><strong for="total">Total:</strong> &#8369;<span id="total"></span></p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="addToCart" class="addToCart btn btn-primary" data-dismiss="modal">Add to Cart</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
  
    </div>
</div>

<!--Modal: addToCart-->
<div class="modal fade" id="modalAddToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-md modal-notify modal-danger" role="document">
    <!--Content-->
    <div class="modal-content text-center">
      <!--Header-->
      <div class="modal-header d-flex justify-content-center">
        <p class="heading">Product in the Cart</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="white-text">&times;</span>
          </button>
      </div>
      <!--Body-->
      <div class="modal-body">
        <div class="col-12">
            <p>Do you need more time to make a purchase decision?</p>
            <p>No pressure, your product will be waiting for you in the cart.</p>
          </div>
      </div>

      <!--Footer-->
      <div class="modal-footer">
        <a type="button" href="/test" class="btn btn-info">Go to cart</a>
        <a type="button" class="btn btn-outline-info waves-effect" data-dismiss="modal">Cancel</a>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!--Modal: modalConfirmDelete-->

<script>
$(document).ready(function() {
    $(".show").click(function(e){
        e.preventDefault();
        $('#quantity').val("1");
        var id = this.id;
        $.ajax({
            url:"/product/show",
            type:"GET",
            data:{id:id},
            success:function(response){
                $('#product_id').val(response.id);
                $('#name').text(response.name);
                $('#des').text(response.description);
                $('#price').text(response.price);
                $('#total').text(response.price);
                $('#quantity').attr('max', response.stack);
                $('#image_preview_container').attr('src', '/images/'+response.img);
                $('#myModal').modal('show');
            },
            error:function(response){

            }
        });
    });

    $('#quantity').change(function(){
        var price = $('#price').text();
        var quantity = $('#quantity').val();
        var total = price * quantity;
        $('#total').text(total);
    });

    $('#addToCart').click(function(){
        var id = $('#product_id').val();
        var quantity = $('#quantity').val();
        $.ajax({
            url:'/addToCart',
            type:'POST',
            data:
            {
                id:id,
                quantity:quantity
            },
            success:function(){
                $('#myModal').modal('toggle');
                $('#modalAddToCart').modal('show');
            }
        });
    });
});
</script>
@endsection
