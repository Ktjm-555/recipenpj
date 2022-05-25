# レシピん　App
レシピを登録して、閲覧できるアプリです。  
また、材料を保存し、リストも閲覧できます。

# DEMO
<img width="1438" alt="スクリーンショット 2022-05-25 17 24 54" src="https://user-images.githubusercontent.com/102650893/170217239-4ba25686-8a38-4638-88bb-3f347a2f1be9.png">

* https://temp-application.herokuapp.com/(ダミー)

# アプリを作った理由
毎週、休日のタイミングで夕ご飯を考えることがレパートリーも少なく、憂鬱に感じていました。
そのため、レシピ、思いついたときにその都度投稿すれば、あらためて考えなくても良いと思いました。
また、買うものもリストとして保存できていれば、買い物行く際、レシピを見ながら考えることもありません。
そんな背景から作成しました。

# テスト用アカウント
メールアドレス：test@te.com  
パスワード：test111

# 利用方法
会員登録をし、レシピ（レシピ名、材料、作り方）の投稿をします。  
トップページのレシピの一覧から、気になったものをクリックすると詳細画面が開きます。  
自身のものであれば、編集、削除ができます。  
詳細画面の「作る予定」をクリックすると、材料が「買うものリスト」に表示されます。

# 機能一覧
* ログイン機能 
* レシピ投稿
  * 画像投稿 
* 編集機能
* 削除機能
* 自身の投稿一覧
* 投稿内容の一部保存機能（レシピの投稿から、材料のみを保存し、表示する。）
* ストップウォッチ機能　
　
# 工夫したポイント

# 使用技術（開発環境）

# 使ってもらった人からの感想

# 今後
* 一部保存機能で保存した内容を削除
* コメントをチャットでできる仕様を追加
* 材料名での検索機能
* オブジェクトを使ってコードをまとめる

# データベース設計
## memberテーブル

Column | Type | Options
-- | -- | --
name | varchar | null: false
passward | text | null: false
email | text | unique: true
created | timestamp |  
modified | timestamp |  	


## recipenテーブル
Column | Type | Options
-- | -- | --
recipename | text | null: false
member_id | int | foreign_key: true
image | text | null: false
foodstuffs | text | null: false
recipe | text | null: false
created | timestamp |  
modified | timestamp |  

## buyテーブル
Column | Type | Options
-- | -- | --
product | text |  
buy_u_id | int | null: false
recipe_d_id | int | null: false
