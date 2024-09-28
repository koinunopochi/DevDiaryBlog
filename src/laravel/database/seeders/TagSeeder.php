<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EloquentTag;
use App\Domain\ValueObjects\TagId;
use App\Domain\ValueObjects\TagName;

class TagSeeder extends Seeder
{
  public function run()
  {
    $tags = [
      // プログラミング言語
      'PHP',
      'JavaScript',
      'Python',
      'Java',
      'C#',
      'Ruby',
      'Go',
      'Rust',
      'TypeScript',
      'Swift',
      'Kotlin',
      'Scala',
      'Dart',
      'C++',
      'C',
      'Objective-C',
      'R',
      'Perl',
      'Haskell',
      'Lua',

      // フレームワーク
      'Laravel',
      'React',
      'Vue.js',
      'Angular',
      'Django',
      'Spring',
      'ASP.NET',
      'Ruby on Rails',
      'Express.js',
      'Flask',
      'Symfony',
      'CodeIgniter',
      'Yii',
      'Nest.js',
      'FastAPI',
      'Svelte',
      'Ember.js',
      'Meteor',

      // データベース
      'MySQL',
      'PostgreSQL',
      'MongoDB',
      'Redis',
      'SQLite',
      'Oracle',
      'SQL Server',
      'Cassandra',
      'Elasticsearch',
      'MariaDB',
      'DynamoDB',
      'Couchbase',
      'Neo4j',
      'InfluxDB',

      // インフラ・クラウド
      'AWS',
      'Azure',
      'GCP',
      'Docker',
      'Kubernetes',
      'Terraform',
      'Ansible',
      'Vagrant',
      'Heroku',
      'DigitalOcean',
      'OpenStack',
      'Cloudflare',
      'Netlify',
      'Vercel',

      // バージョン管理
      'Git',
      'GitHub',
      'GitLab',
      'Bitbucket',
      'Subversion',
      'Mercurial',

      // 開発手法・プラクティス
      'Agile',
      'Scrum',
      'DevOps',
      'TDD',
      'CI/CD',
      'マイクロサービス',
      'RESTful API',
      'GraphQL',
      'DDD',
      'BDD',
      'Kanban',
      'XP',
      'Lean',
      'Waterfall',

      // フロントエンド技術
      'HTML',
      'CSS',
      'Sass',
      'Less',
      'webpack',
      'Babel',
      'ESLint',
      'Prettier',
      'Stylelint',
      'Gulp',
      'Grunt',
      'PostCSS',
      'Tailwind CSS',
      'Bootstrap',
      'Material-UI',
      'Ant Design',

      // バックエンド技術
      'Node.js',
      'Nginx',
      'Apache',
      'WebSocket',
      'gRPC',
      'RabbitMQ',
      'Kafka',
      'Celery',
      'Redis Queue',

      // モバイル開発
      'Android',
      'iOS',
      'React Native',
      'Flutter',
      'Xamarin',
      'Ionic',
      'PhoneGap',
      'Cordova',

      // AI・機械学習
      'Machine Learning',
      'Deep Learning',
      'TensorFlow',
      'PyTorch',
      'Keras',
      'Scikit-learn',
      'NLP',
      'Computer Vision',
      'Reinforcement Learning',
      'GANs',

      // セキュリティ
      'OWASP',
      'Penetration Testing',
      'Encryption',
      'Authentication',
      'Authorization',
      'OAuth',
      'JWT',
      'SSL/TLS',
      'Firewall',
      'VPN',
      'Intrusion Detection',
      'GDPR',
      'HIPAA',

      // パフォーマンス
      'Performance Optimization',
      'Caching',
      'Load Balancing',
      'CDN',
      'Profiling',
      'Memory Management',

      // ツール
      'VS Code',
      'IntelliJ IDEA',
      'Postman',
      'Jira',
      'Slack',
      'Trello',
      'Confluence',
      'Jenkins',
      'Travis CI',
      'CircleCI',
      'SonarQube',
      'Selenium',
      'JUnit',
      'PyTest',
      'Jest',

      // その他
      'Open Source',
      'API',
      'Serverless',
      'IoT',
      'Blockchain',
      '5G',
      'AR/VR',
      'エッジコンピューティング',
      'ビッグデータ',
      'データ分析',
      'UX/UI',
      'アクセシビリティ',
      'SEO',
      'PWA',
      'WebAssembly',
      'Quantum Computing',
      'Cybersecurity',
      'DevSecOps',
      'SRE',
      'Functional Programming',
      'Microservices',
      'Monorepo',
      'Design Patterns',
      'Code Review',
      'Technical Debt'
    ];

    foreach ($tags as $tag) {
      EloquentTag::create([
        'id' => (new TagId())->toString(),
        'name' => (new TagName($tag))->toString(),
      ]);
    }
  }
}
