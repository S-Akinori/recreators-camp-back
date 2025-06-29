<html>
  <body>
    <p>{{$material->user->name}}様 <br/>
        いつもRecreator's Campをご利用いただきありがとうございます。Recreator's Camp運営です。
    </p>

    <p>
      {{$user->name}}様から素材のダウンロードリクエストが届いてます。<br>
      「アカウントにログインの上」、下記URLより詳細を確認してください。
    </p>
    <a href="{{config('app.front_url')}}/materials/{{$material->id}}/permission/{{$permission_token->id}}?token={{$permission_token->token}}">{{config('app.front_url')}}/materials/{{$material->id}}/permission/{{$permission_token->id}}?token={{$permission_token->token}}</a>
    <p>今後ともRecreator’s Campをよろしくお願いいたします。</p>
    <p>Recreator’s Camp運営</p>
  </body>
</html>