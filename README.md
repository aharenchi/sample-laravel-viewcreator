# sample-laravel-viewcreator

Laravel の View Creator を利用して、スマートフォンとその他のデバイス(パソコン/タブレット)で表示する View テンプレートを切り替えるデモプロジェクトです。

## 実行方法

1. docker コンテナを起動

```
docker compose up -d
```

2. http://localhost:8080/ にアクセスする

## テスト方法

1. docker コンテナに入る

```
docker compose exec php bash
```

2. phpunit を実行

```
./vendor/bin/phpunit
```
