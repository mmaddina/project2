@extends('layout')
@section('content')

@if ($errors->any())
<div class="alert-danger">
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
  <ul>
</div> 
@endif

<form action="/comment/update/{{$comment->id}}" method="post">
@METHOD('PUT')
@csrf

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{$comment->title}}">
  </div>

 <div class="form-group">
<label for="exampleInputShortDesc">ShortDesc</label>
<input type="shortDesc" class="form-control" id="exampleInputShortDesc" name="desc" value="{{$comment->desc}}">
</div>
<button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection