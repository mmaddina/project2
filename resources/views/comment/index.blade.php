@extends('layout')
@section('content')
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Title</th>
      <th scope="col">Article</th>
      <th scope="col">Username</th>
      <th scope="col">Approve</th>

    </tr>
  </thead>
  <tbody>
    @foreach($comments as $comment)
    <tr>
      <th scope="row">{{$comment->title}}</th>
      <td>{{$comment->desc}}</td>
      <td><a href="/article/{{$comment->article_id}}">{{$comment->article}}</a></td>
      <td>{{$comment->name}}</td>
      <td>
        @if (!$comment->accept)
          <a class="btn btn-success" href="/comment/{{$comment->id}}/accept">Accept</a>
        @else
          <a class="btn btn-warning"href="/comment/{{$comment->id}}/reject">Reject</a>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection