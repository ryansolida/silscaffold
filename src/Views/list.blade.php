@extends('silscaffold::admin')
@section('content')
    @foreach ($items as $i)
        <div class="py-2">{{$i->name}}</div>
    @endforeach
@endsection