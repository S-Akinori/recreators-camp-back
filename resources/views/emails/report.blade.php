<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>通報通知</title>
</head>
<body>
    <p>新しい通報がありました。詳細は以下の通りです。</p>
    <p><strong>通報者:</strong> {{ $reporterName }} (ID: {{ $reporterId }})</p>
    <p><strong>通報内容:</strong> {{ $description }}</p>
    <p><strong>対象:</strong> {{ $reportType }}</p>
    @if ($materialId)
        <p><strong>素材ID:</strong> {{ $materialId }}</p>
        <p><strong>素材名:</strong> <a href="{{config('app.front_url').'/materials/'.$materialId}}">{{ $materialName }}</a></p>
    @endif
    @if ($commentId)
        <p><strong>コメントID:</strong> {{ $commentId }}</p>
        <p><strong>コメント内容:</strong> {{ $commentContent }}</p>
    @endif
    <p>このメールはシステムから自動送信されています。</p>
</body>
</html>
