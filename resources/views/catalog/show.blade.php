@extends('layouts.app')

@section('content')

<div class="container catalog">
    <div class="row">

        <div class="col-sm-4 col-md-3">
                @foreach ($catalogs as $item)
                    @if($catalog->id == $item->id)
                    	<h3>{!! link_to_route('catalog.show', $item->title, $item->id, ['class'=>'active']) !!}</h3>
	                    <ul>
                        @foreach ($item->products as $product)
	                        <li>{!! link_to_route('product.show', $product->title, $product->id) !!}</li>
	                    @endforeach
                        </ul>
                    @else
                    	<h3>{!! link_to_route('catalog.show', $item->title, $item->id) !!}</h3>
                    @endif
                @endforeach
        </div>


        <div class="col-sm-8 col-md-9">
                <h1>{!! $catalog->title !!}</h1>
                <div class="row">
                    @foreach($catalog->products as $product)

<div class="col-sm-6 col-md-4">
 <div class="thumbnail">
   @if($product->avatar)
    <div class="hovereffect">
        <img class="img-responsive" src="/{{ $product->avatar }}" alt="{{ $product->title }}">
        <div class="overlay">
           <h2>{{ $product->title }}</h2>
           <a class="info btn-send ajax" href="/ajax" data-toggle="modal" data-target="#open-modal">Заказать просчет</a>
        </div>

    </div>
    @endif
    <div class="caption">
<h3>{!! link_to_route('product.show', $product->title, $product->id) !!}</h3>
 <p>{!! $product->description !!}</p>
</div>
</div></div>
                    @endforeach
                </div>
        </div>



</div>


  <div class="row">
             <div class="col-sm-12 col-md-12">
             {!! $catalog->content !!}
             </div>
            @foreach($catalog->photo as $photo)
            <div class="col-sm-3 col-md-4">
                <a class="thumbnail" href="/{!! $photo !!}" data-lightbox="photo"><img src="/{!! $photo !!}" alt="{!! $catalog->title !!}"></a>
            </div>
            @endforeach
  </div>

    </div>
</div>

@endsection
