<html>
  <body>
    <p>{{$material->name}}のダウンロードリクエストが承認されました。</p>
    <p>下記URLよりダウンロードが可能です。</p>
    <a href="{{config('app.front_url')}}/materials/{{$material->id}}">{{config('app.front_url')}}/materials/{{$material->id}}</a>
  </body>
</html>