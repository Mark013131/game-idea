# GAMEPORT

ゲームを投稿して共有するサイト向けの WordPress カスタムテーマです。リポジトリ直下の `gameport/` を、そのまま `wp-content/themes/` にコピーして使います。

## フォルダ構成

```
gameport/
├── style.css          テーマ情報と共通スタイル
├── functions.php      テーマの読み込みと機能
├── front-page.php     トップページ
├── index.php          フォールバックテンプレート
├── header.php
├── footer.php
├── page-*.php         固定ページ用テンプレート
├── single.php         個別投稿
├── author.php         投稿者ページ
├── search.php         検索結果
├── inc/               アカウントなど
├── parts/             共通パーツ
├── css/               ページ別スタイル
├── js/                スクリプト
└── assets/            画像・アイコン
```

## インストール

次のどちらかでテーマを追加し、管理画面の「外観 → テーマ」から GAMEPORT を有効化します。

- `gameport/` フォルダを WordPress の `wp-content/themes/` にコピーする
- `gameport/` を ZIP に圧縮し、「外観 → テーマ → 新規追加 → テーマのアップロード」からアップロードする

## 必要な固定ページ

次の固定ページを作り、スラッグとテンプレートを合わせます。

| スラッグ | テンプレート |
| --- | --- |
| `post` | 投稿する |
| `games` | ゲームを探す |
| `ranking` | ランキング |
| `contact` | お問い合わせ |
| `login` | ログイン |
| `register` | 新規登録 |

## パーマリンク

「設定 → パーマリンク」で「投稿名」を選んで保存します。
