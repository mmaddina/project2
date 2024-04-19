@extends('layout')
@section('content')
<div class="text-center mt-3">
<div class= 'card'>
  <div class="card-body">
    <h5 class="card-title">{{$article->title}}</h5>
    <h6 class="card-subtitle mb-2 text-muted">{{$article->shortDesc}}</h6>
    <p class="card-text">{{$article->text}}</p>
    <div class="d-flex">
        <a class="btn btn-secondary mr-3" href="/article/{{$article->id}}/edit" class="card-link">Edit article</a>
        <form action="/article/{{$article->id}}" method=post>
        @csrf
        @METHOD('DELETE')
        <button type="submit" class="btn btn-danger">Delete article</button>
        </form>
    </div>
</div>
</div>

<div class="text-center mt-3">
  <h4>Comments</h4>
</div>

@if (session('res'))
<div class="alert-success">
  <p>Комментарий успешно добавлен и отправлен на модерацию!</p>
</div>
@endif

@if ($errors->any())
<div class="alert-danger">
  <ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
  <ul>
</div> 
@endif

<form action="/comment" method="post">
    @csrf
    <input type="hidden"  name="article_id" value="{{$article->id}}">
    
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>
  <div class="form-group">
    <label for="text">Text</label>
    <textarea name="desc" id="text" cols="30" rows="10" class="form-control"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Save</button>
</form>


@foreach($comments as $comment)
<div class= 'card'>
  <div class="card-body">
    <h5 class="card-title">{{$comment->title}}</h5>
    <p class="card-text">{{$comment->desc}}</p>
    <div class="d-flex">
      @can('comment',$comment)
        <a class="btn btn-secondary mr-3" href="/comment/edit/{{$comment->id}}" class="card-link">Edit comment</a>
        <a class="btn btn-danger mr-3" href="/comment/delete/{{$comment->id}}" class="card-link">Delete comment</a>
      @endcan
    </div>
</div>
</div>
@endforeach
@endsection