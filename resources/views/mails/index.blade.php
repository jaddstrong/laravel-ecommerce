@component('mail::message')
<p>{{ date('j F, Y H:i:s') }}</p>
<br>
<p>Hi {{$auth->name}},</p>
@component('mail::table')
| Product name         | Product price          | Product quantity       | Product sub-total      |
|:--------------------:|:----------------------:|:----------------------:|:----------------------:|
@foreach($purchase as $item)
|{{$item->product_name}}| {{$item->product_price}}| {{$item->product_quantity}}| {{$item->product_total}}|
@endforeach
<p>감사합니다</p>
@endcomponent
{{ config('app.name') }}
@endcomponent
