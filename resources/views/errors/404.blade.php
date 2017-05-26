@extends('frontend_master')

@section('content')

<section class="container missing">
<h1>404</h1>
<p>Page not found</p>
<a href="{{URL::to('/')}}" class="btn">Return to Homepage</a>
</section>

@stop