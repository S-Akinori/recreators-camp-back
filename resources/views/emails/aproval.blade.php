<html>
  <body>
    <p>
      {{$user->name}}様<br>
      いつもRecreator's Campをご利用いただきありがとうございます。Recreator's Camp運営です。
    </p>
    <p>{{$material->user->name}}様に送った「{{$material->name}}」の承認リクエストが承認されました。</p>
    <p>下記URLよりダウンロードが可能です。</p>
    <a href="{{config('app.front_url')}}/materials/{{$material->id}}">{{config('app.front_url')}}/materials/{{$material->id}}</a>
    <p>ぜひ他の素材もあわせてご検討ください。</p>
    <p>今後ともRecreator's Campをよろしくお願いいたします。</p>
    <p>Recreator's Camp運営</p>
  </body>
</html>