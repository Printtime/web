@extends('layouts.app')

@section('content')

<div class="container">
<h1>{{ $value->type->title }}</h1>
<h3>{{ $value->variable->title }}</h3>

<div class="hidden" id="roll_width">{{ $value->type->roll_width }}</div>


<div class="hidden" id="price">{{ $value->price }}</div>
<div class="hidden" id="coef_width">{{ $value->type->width }}</div>
<div class="hidden" id="coef_height">{{ $value->type->height }}</div>

<div class="row">
	<div class="col-sm-12">
	<br>


@if(isset($order->id))
  {{ Form::open(array('route' => array('order.update', $order->id))) }}
@else
  {{ Form::open(array('route' => array('order.save', $value->id))) }}
@endif



  <div class="form-group">
    <label for="title">Название заказа</label>
    <input value="{{ $order->title or null }}" required="required" name="title" type="text" class="form-control" id="title" placeholder="Укажите название заказа">
  </div>

<hr>

<label>Макет</label>
<ul>
  <li>Расширения файла: <b>tif или tiff</b></li>
  <li>Схема формирования цвета: <b>Coated Fogra27 ISO 12647-2:20004</b></li>
  <li>Размер файла: <b>не более 2 Гб</b></li>
</ul>

<div class="row">


        <input id="file0" type="hidden" name="file0">
        <input id="file1" type="hidden" name="file1">

    <div class="container">
      <div class="row">

        @include('printfile.form', ['side' => '0', 'side_name'=>'Загрузить лицевую сторону'])
      @if($value->type->product->order_vis)
        @include('printfile.form', ['side' => '1', 'side_name'=>'Загрузить обратную сторону'])
      @endif

    </div></div>



</div>

<br>

<label>Размер и количество</label>

<div class="row">
  <div class="col-sm-4">
    <label class="sr-only" for="width">Ширина</label>
    <div class="input-group">
      <div class="input-group-addon">Ширина</div>
      <input name="width" type="number" step="1" class="calc form-control text-center input-lg" id="width" value="{{ $order->width or $value->type->width }}">
      <div class="input-group-addon"> мм.</div>
    </div>
  </div>
  <div class="col-sm-4">
    <label class="sr-only" for="height">Высота</label>
    <div class="input-group">
      <div class="input-group-addon">Высота</div>
      <input name="height" type="number" step="1" class="calc form-control text-center input-lg" id="height" value="{{ $order->height or $value->type->height }}">
      <div class="input-group-addon"> мм.</div>
    </div>
  </div>

  <div class="col-sm-4">
    <label class="sr-only" for="count">Количество</label>
    <div class="input-group">
      <div class="input-group-addon">Количество</div>
      <input name="count" type="number" min="1" class="calc form-control text-center input-lg" id="count" value="{{ $order->count or '1' }}">
      <div class="input-group-addon"> шт.</div>
    </div>
  </div>

<div id="make_message" class="col-sm-12"></div>


</div>




@if(count($value->type->product->postpresss) >= 1)

<br>

  <label>Постработы</label>

  <table class="table table-hover postpresss">
@foreach($value->type->product->postpresss as $pp)
  <tr>
    <td>{{ $pp->label }}</td>
    <td>{!! Form::select('postpress['.$pp->id.']', $pp->getData(), $getPostpressArr, ['class'=>'form-control', 'id'=>$pp->name]) !!}

        <div class="hidden" id="ppp" ppid="postpress[{{ $pp->id }}]">
          @foreach($pp->getPPP() as $PPP)
            @if($PPP->ppprice and $PPP->ppprice_count)<div pppid="{{ $PPP->id }}" ppprice="{{ $PPP->ppprice }}" ppprice_count="{{ $PPP->ppprice_count }}"></div>@endif
          @endforeach
        </div>

    </td>
    <td>
      @if($pp->f) <span id="f{!! $pp->name !!}">{!! $pp->f !!}</span> грн/м погонный @endif
    </td>
    <td width="128px" class="text-right"><span id="price{!! $pp->name !!}">0</span> грн.</td>
  </tr>
@endforeach

  </table>

@endif

<br>

<label>Комментарий к заказу</label>
<textarea name="comment" class="form-control" rows="3" placeholder="Если необходимо, прокомментируйте детали заказа...">{{ $order->comment or null }}</textarea>

<br>


<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Самовывоз со склада Printtime</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">Вы сможете забрать свой заказ по адресу: Украина, г.Кривой Рог, ул. Волгоградская , 12</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Доставка перевозчиком: <img src="/images/novaposhta_icon.png"> Новая почта</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
              {{ Form::label('Город и отделение', null, ['class' => 'control-label']) }}
              <div id="city" class="form-group">
                <input value="{{ $order->delivery->city or null }}" name="city" type="text" class="typeahead form-control" placeholder="Введите город...">

              </div>

              <div id="warehouses" class="form-group">
                <select class="form-control" @if(!isset($order->delivery->warehouses)) disabled="disabled" @endif name="warehouses">
                  @if(isset($order->delivery->warehouses))
                    <option>{{ $order->delivery->warehouses }}</option>
                  @endif
                </select>
              </div>


            <div class="form-group">
                {{ Form::label('Получатель', null, ['class' => 'control-label']) }}
                <input value="{{ $order->delivery->name or null }}" name="name" type="text" class="form-control" placeholder="Укажите Ф.И.О. получателя">
            </div>
            <div class="form-group">
                {{ Form::label('Телефон', null, ['class' => 'control-label']) }}
                <input value="{{ $order->delivery->phone or null }}" name="phone" type="text" class="form-control" placeholder="+380">
            </div>

            Услуга доставки оплачиваются и расчитывается перевозчиком "Новая почта" и взимается с получателя.
      </div>
    </div>
  </div>
</div>




<table class="table table-striped">
<tr>
	<td>Печать</td>
	<td><span id="print">{{ $value->price }}</span> грн. (<span id="area">{{ round(($value->type->height * $value->type->width)/1000000, 2) }}</span> м2)</td>
</tr>
<tr>
	<td>Постработы</td>
	<td><span id="PricePostpress">0.00</span> грн.</td>
</tr>
<tr>
	<td>Ваша скидка <span id="discount">{{ Auth::user()->discount }}</span>%</td>
	<td>Экономия <span id="economy">{{ $value->price * Auth::user()->discount / 100 }}</span> грн.</td>
</tr>
<tr>
	<td>Ваша баланс</td>
	<td><span id="balance">{{ Auth::user()->balance }}</span> грн. (<a href="{!! route('user.transfer') !!}" class="ajax-pay" data-toggle="modal" data-target="#open-modal-pay">Пополнить баланс</a>)</td>
</tr>
<tr>
	<td>Итого к оплате</td>
	<td><b id="sum">{{ $order->sum or $value->price - $value->price * Auth::user()->discount / 100 }}</b> грн.</td>
</tr>
</table>

     <input id="sumPay" type="hidden" name="sum" value="{{ $value->price - $value->price * Auth::user()->discount / 100 }}">

{!! Form::submit('Оформить заказ', ['class' => 'submit btn btn-success btn-lg']) !!}

{!! Form::close() !!}

	</div>
</div>
</div>

@endsection
