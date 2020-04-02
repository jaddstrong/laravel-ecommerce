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
            <div class="card">
                <div class="card-header">
                    
                    <h4>Cart</h4>
                </div>

                <div class="card-body">

                    <div class="row">
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
                                
                                @if(Session::exists('cart'))
                                <?php $quantity = Session::get('cart'); ?> 
                                <?php $i = 0; ?>
                                <?php $totally = 0; ?>
                                @foreach($items as $item)
                                    <tr>
                                        <td><img src="images/{{$item->img}}" height="50px" width="50px"></td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$quantity[$i][1]}}</td>
                                        <td>&#8369;{{$item->price}}</td>
                                        <?php $total = $quantity[$i][1] *  $item->price; ?>
                                        <td>&#8369;{{$total}}</td>
                                        <td><button class="btn btn-sm btn-danger">Remove</td>
                                        <?php $totally += $total;  ?>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td align="right"><strong>Total:</strong></td>
                                    <td>&#8369;{{$totally}}</td>
                                    <td><button class="btn btn-primary" id="purchase">Purchase</button></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#purchase').click(function(){
        $.ajax({
            url:'/purchase',
            type:'GET',
            success:function(response){
                alert("haha");
            },
            error:function(response){
                alert("fail");
            }
        });
    });
</script>
@endsection
