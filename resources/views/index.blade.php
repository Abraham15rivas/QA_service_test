@extends('layouts.app')

@section('content')
    <an-event-component
        :event="{{ $event }}"
        user="{{ $check == true ? 'auth' : 'guest' }}"
    ></an-event-component>
@endsection