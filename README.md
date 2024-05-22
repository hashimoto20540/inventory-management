# inventory-management

## 概要<br>
XAMPPのhtdocsディレクトリー（C:\xampp\htdocs）にinventory-managementフォルダを配置すると動作します。<br>

### 商品の在庫管理<br>
- CRUD処理<br>
  - 商品登録<br>
  - 商品一覧<br>
  - 商品編集<br>
  - 商品削除<br>
- 商品検索機能<br>

### ログイン機能<br>
- ユーザー登録画面<br>
- ログイン画面<br>
- ホーム画面<br>
- 未ログインだとログイン画面にリダイレクト

## 使用技術<br>
### 使用アプリ・言語
 
| アプリ・言語  | バージョン |
| ------------- | ------------- |
| XAMPP  | 7.3.6  |
| Apache  | 2.4.39  |
| PHP  | 7.3.6  |
| MariaDB  | 15.1  |
| HTML  | ----  |
| JavaScript  | ----  |

### 環境<br>
OS：Wndows11

## 作成方法<br>
以下のサイトを参考に作成<br>
商品在庫管理<br>
https://jp-seemore.com/web/7537/#toc3<br>
ログイン機能<br>
https://qiita.com/ryo-futebol/items/5fb635199acc2fcbd3f


## ページのURL<br>
新規会員登録<br>
http://localhost/inventory-management/signup.php<br>

ログイン画面<br>
http://localhost/inventory-management/login_form.php<br>

## 遷移図・デザイン<br>
### Figma<br>
https://www.figma.com/file/d2Px5R2JZrS5hS5YZ14Vq0/%E5%9C%A8%E5%BA%AB%E7%AE%A1%E7%90%86%E3%82%B7%E3%82%B9%E3%83%86%E3%83%A0?type=design&node-id=0%3A1&mode=design&t=nMkhcnabUZ4Xdbc5-1

### 旧遷移図<br>
https://docs.google.com/spreadsheets/d/124QxFUf24I2REP_Xs3qHbswRSgVkc-ljpKWcY_aPk34/edit#gid=0

### ER図(Figmaで作成)<br>
https://www.figma.com/file/chXWXnK4O5lSjceNumvKG9/ER%E5%9B%B3?type=design&node-id=3%3A147&mode=design&t=kPsWDoFcxsuwvhHL-1


## 参考文献<br>
・商品在庫管理：PHPで学ぶ！在庫管理システムの10ステップ作成ガイド<br>
https://jp-seemore.com/web/7537/#toc3<br>
・ログイン機能 →見れなくなってる(;´Д｀)。やはりQiitaでいいね数が少ないものはダメ。<br>
https://qiita.com/ryo-futebol/items/5fb635199acc2fcbd3f<br>
・若手プログラマー必読！５分で理解できるER図の書き方５ステップ<br>
https://www.ntt.com/business/sdpf/knowledge/archive_50.html<br>
・やさしい図解で学ぶ　中間テーブル　多対多　概念編<br>
https://qiita.com/ramuneru/items/db43589551dd0c00fef9<br>
・参考にしたデザイン：square様<br>
https://squareup.com/dashboard/<br>
・【書籍】達人に学ぶDB設計徹底指南書を読んで学んだこと【第4章】：多対多のER図作成時に参考<br>
https://kazuki13070311.hatenadiary.jp/entry/2021/07/17/002811<br>
・仕様書とは？開発事例をもとに成功する仕様書の書き方を解説<br>
https://monstar-lab.com/dx/solution/howto-specification/<br>
PHP MySQL Ajax Live Search(検索機能作成時に使用)<br>
https://www.tutorialrepublic.com/php-tutorial/php-mysql-ajax-live-search.php<br>
ヒアドキュメント <br>
https://www.php.net/manual/ja/language.types.string.php<br>
