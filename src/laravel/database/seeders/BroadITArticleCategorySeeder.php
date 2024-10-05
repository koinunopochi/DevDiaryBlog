<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EloquentArticleCategory;
use App\Domain\ValueObjects\ArticleCategoryName;
use App\Domain\ValueObjects\ArticleCategoryDescription;
use App\Domain\ValueObjects\ArticleCategoryId;

class BroadITArticleCategorySeeder extends Seeder
{
  public function run()
  {
    $categories = [
      [
        'name' => 'ソフトウェア開発',
        'description' => '- プログラミング言語と開発手法
- ソフトウェアアーキテクチャとデザインパターン
- 開発ツールとIDE
- フレームワークとライブラリ
- コード品質と最適化テクニック'
      ],
      [
        'name' => 'インフラストラクチャ',
        'description' => '- クラウドコンピューティング（AWS、Azure、GCPなど）
- サーバー管理とネットワーキング
- 仮想化技術とコンテナ化
- CI/CDパイプライン
- インフラストラクチャ・アズ・コード（IaC）'
      ],
      [
        'name' => 'データサイエンス',
        'description' => '- 機械学習とAI
- ビッグデータ処理と分析
- データビジュアライゼーション
- 統計学と数学的基礎
- 自然言語処理（NLP）とコンピュータビジョン'
      ],
      [
        'name' => 'セキュリティ',
        'description' => '- サイバーセキュリティベストプラクティス
- 暗号化技術と認証システム
- ネットワークセキュリティ
- セキュリティ監査とペネトレーションテスト
- コンプライアンスと規制（GDPR、PCI DSSなど）'
      ],
      [
        'name' => 'エマージングテクノロジー',
        'description' => '- ブロックチェーンと分散型技術
- IoTとエッジコンピューティング
- AR/VRと空間コンピューティング
- 量子コンピューティング
- 5Gと次世代通信技術'
      ],
      [
        'name' => 'キャリア＆スキル開発',
        'description' => '- 継続的学習と自己啓発
- 技術資格と認定
- リーダーシップとマネジメントスキル
- コミュニケーションとプレゼンテーションスキル
- ワークライフバランスとメンタルヘルス'
      ],
      [
        'name' => 'プロジェクト管理',
        'description' => '- アジャイルとスクラム手法
- DevOpsの実践
- プロジェクト計画と見積もり
- リスク管理と問題解決
- チーム管理とコラボレーション手法'
      ],
      [
        'name' => 'ビジネス＆テクノロジー',
        'description' => '- デジタルトランスフォーメーション戦略
- テクノロジースタートアップと起業
- IT戦略とアライメント
- テクノロジーの経済的影響
- イノベーションマネジメント'
      ],
      [
        'name' => 'オープンソース＆コミュニティ',
        'description' => '- オープンソースプロジェクトとライセンス
- コミュニティ貢献とオープンソース開発
- テックカンファレンスとイベント
- オンラインコミュニティとフォーラム
- メンタリングとナレッジシェアリング'
      ],
      [
        'name' => 'テクノロジー倫理＆社会影響',
        'description' => '- AIと機械学習の倫理的考慮事項
- プライバシーとデータ保護
- テクノロジーの社会的影響と責任
- デジタルアクセシビリティとインクルージョン
- 持続可能なテクノロジーと環境への影響'
      ],
      [
        'name' => 'プロジェクト＆開発コミュニティ',
        'description' => '- プロジェクト紹介と募集
- コードレビューとペアプログラミング
- ハッカソンとコーディングチャレンジ
- オープンソースコントリビューション機会
- プロジェクト管理ツールとベストプラクティス
- コミュニティ主導の学習リソース'
      ],
    ];

    foreach ($categories as $category) {
      EloquentArticleCategory::create([
        'id' => (new ArticleCategoryId())->toString(),
        'name' => (new ArticleCategoryName($category['name']))->toString(),
        'description' => (new ArticleCategoryDescription($category['description']))->toString(),
      ]);
    }
  }
}
