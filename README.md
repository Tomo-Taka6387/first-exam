＃アプリケーション名
フリマアプリ

＃環境構築

Docker ビルド
1.git clone git@github.com:Tomo-Taka6387/first-exam.git

2.DockerDesktop アプリを立ち上げる
3.docker-compose up -b --build

※MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compose.yml ファイルを編集してください。

#Laravel 環境構築
1.docker-compose exec php bash
2.composer install
3.「env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
4..env に以下の環境変数を追加
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

5.アプリケーションキーの作成
php artisan key:generate

6.マイグレーションの実行
php artisan make:migration

7.シーディングの実行
php artisan migrate:fresh --seed

＃使用技術
*PHP8.4.4.4
*Laravel8.83.8
*MySQL9.2.0

＃ER 図
![ER図](./ER図.png)

＃URL
*環境開発: http://localhost/
*phpMyAdmin: http://localhost:8080/

#Userのログイン用初期データ
*メールアドレス: test@test.com
*パスワード: password
