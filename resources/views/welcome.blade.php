@extends('layouts.app')

@section('content')
    <all-event-component
        user="{{ $check == true ? 'auth' : 'guest' }}"
    ></all-event-component>
@endsection