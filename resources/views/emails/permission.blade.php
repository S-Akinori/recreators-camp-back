<html>
  <body>
    <p>{{$user->name}}さんから素材のダウンロード承認リクエストが届いてます。</p>
    <p>下記URLより詳細を確認してください。</p>
    <a href="{{config('app.front_url')}}/materials/{{$material->id}}/permission/{{$permission_token->id}}?token={{$permission_token->token}}">{{config('app.front_url')}}/materials/{{$material->id}}/permission/{{$permission_token->id}}?token={{$permission_token->token}}</a>
  </body>
</html>