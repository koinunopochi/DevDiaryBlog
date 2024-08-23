# ユーザー管理システム: ポリシーベースアクセス制御のドメインモデル設計

## エンティティ

1. User（ユーザー）

   - 属性:
     - ID: UserId (値オブジェクト)
     - Username: Username (値オブジェクト)
     - Email: EmailAddress (値オブジェクト)
     - Password: HashedPassword (値オブジェクト)
     - Status: UserStatus (値オブジェクト)
     - CreatedAt: DateTime
     - UpdatedAt: DateTime
     - LastLoginAt: DateTime (オプション)

2. Profile（プロフィール）

   - 属性:
     - UserID: UserId (値オブジェクト)
     - DisplayName: DisplayName (値オブジェクト)
     - Bio: UserBio (値オブジェクト)
     - AvatarUrl: Url (値オブジェクト)
     - SocialLinks: SocialLinkCollection (値オブジェクト)

3. Role（ロール）

   - 属性:
     - ID: RoleId (値オブジェクト)
     - Name: RoleName (値オブジェクト)
     - Description: RoleDescription (値オブジェクト)

4. Policy（ポリシー）

   - 属性:
     - ID: PolicyId (値オブジェクト)
     - Name: PolicyName (値オブジェクト)
     - Description: PolicyDescription (値オブジェクト)
     - Document: PolicyDocument (値オブジェクト)

5. UserRole（ユーザーロール）
   - 属性:
     - ID: UserRoleId (値オブジェクト)
     - UserId: UserId (値オブジェクト)
     - RoleId: RoleId (値オブジェクト)
     - AssignedAt: DateTime
     - AssignedBy: UserId (値オブジェクト)

## 値オブジェクト

1. UserId

   - user-から始まるUUIDv4
   - example: user-123e4567-e89b-12d3-a456-426614174000

2. Username

   - 文字列（最小長さ 3、最大長さ 20）
   - 英数字とアンダースコアのみ許可
   - 一意性制約

3. EmailAddress

   - 有効なメールアドレス形式
   - 一意性制約

4. HashedPassword

   - ハッシュ化されたパスワード文字列

5. UserStatus

   - 列挙型: Active, Inactive, Suspended, Deleted

6. DisplayName

   - 文字列（最大長さ 50）

7. UserBio

   - 文字列（最大長さ 500）

8. Url

   - 有効な URL 形式

9. SocialLinkCollection

   - キーと値のペアのコレクション（例：{twitter: "https://twitter.com/username", ...}）

10. RoleId

    - UUID

11. RoleName

    - 文字列（最大長さ 50）
    - 一意性制約

12. RoleDescription

    - 文字列（最大長さ 255）

13. PolicyId

    - UUID

14. PolicyName

    - 文字列（最大長さ 50）
    - 一意性制約

15. PolicyDescription

    - 文字列（最大長さ 255）

16. PolicyDocument

    - JSON 形式の文字列
    - ポリシーの詳細な定義（許可/拒否するアクション、リソース、条件など）

17. UserRoleId
    - UUID

## 集約

1. User 集約

   - ルートエンティティ: User
   - 含まれるエンティティ: Profile
   - 含まれる値オブジェクト: UserId, Username, EmailAddress, HashedPassword, UserStatus

2. Role 集約

   - ルートエンティティ: Role
   - 含まれるエンティティ: なし
   - 含まれる値オブジェクト: RoleId, RoleName, RoleDescription

3. Policy 集約
   - ルートエンティティ: Policy
   - 含まれるエンティティ: なし
   - 含まれる値オブジェクト: PolicyId, PolicyName, PolicyDescription, PolicyDocument

## リレーションシップ

1. User - Profile: 一対一
2. User - Role: 多対多
3. Role - Policy: 多対多
