@extends('layouts.master')

@section('title')
	Error
@endsection

@section('content')
	<h1 class="text-danger">Error!</h1>

	<div class="alert alert-danger">{{ $error }}</div>
@endsection