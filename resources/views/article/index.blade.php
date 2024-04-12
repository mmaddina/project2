@extends('layout')
@section('content')
<table class="table">
  <thead>
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Title</th>
      <th scope="col">shortDesc</th>
      <th scope="col">Text</th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <th scope="row">{{$article->date}}</th>
      <td><a href="/article/{{$article->id}}">{{$article->title}}</a></td>
      <td>{{$article->shortDesc}}</td>
      <td>{{$article->text}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
{{$articles->links()}}
@endsection