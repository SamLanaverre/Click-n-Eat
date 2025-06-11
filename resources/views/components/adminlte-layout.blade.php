@extends('layout.adminlte')

@section('header')
    {{ $header ?? $slot ?? '' }}
@endsection

@section('content')
    {{ $slot }}
@endsection
