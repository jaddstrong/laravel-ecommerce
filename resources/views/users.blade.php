
<!DOCTYPE html>
<html>
<head>
    
</head>
<body>
    
<div class="container">
    <?php $test = Session::get('cart'); ?> 
    {{$test[0][1]}}
    {{-- @foreach(Session::get('cart') as $test)
    {{$test[0]}}
    {{$test[1]}}
    @endforeach --}}
    {{-- {{ Session::get('cart') }} --}}
</div>
   
</body>
   

</html>