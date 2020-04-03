@component('mail::message')
@component('mail::table')
| Product name         | Product price          | Product quantity       | Product sub-total      |
|:--------------------:|:----------------------:|:----------------------:|:----------------------:|
@foreach($purchase as $item)
|{{$item->product_name}}| {{$item->product_price}}| {{$item->product_quantity}}| {{$item->product_total}}|
@endforeach
<p>tnx!</p>
@endcomponent
{{ config('app.name') }}
@endcomponent
