<?php

namespace Database\Factories;

use App\Domain\ValueObjects\PolicyDescription;
use App\Domain\ValueObjects\PolicyDocument;
use App\Models\EloquentPolicy;
use App\Domain\ValueObjects\PolicyId;
use App\Domain\ValueObjects\PolicyName;
use Illuminate\Database\Eloquent\Factories\Factory;

class EloquentPolicyFactory extends Factory
{
  protected $model = EloquentPolicy::class;

  public function definition(): array
  {
    return [
      'id' => (new PolicyId())->toString(),
      'name' => (new PolicyName($this->faker->unique()->words(3, true)))->toString(),
      'description' => (new PolicyDescription($this->faker->sentence()))->toString(),
      'document' => (new PolicyDocument($this->generatePolicyDocument()))->toArray(),
    ];
  }

  private function generatePolicyDocument(): array
  {
    return [
      'Version' => $this->faker->date('Y-m-d'),
      'Statement' => [
        $this->generatePolicyStatement(),
      ],
    ];
  }

  private function generatePolicyStatement(): array
  {
    return [
      'Sid' => 'Stmt' . $this->faker->unique()->numberBetween(1000000, 9999999),
      'Effect' => $this->faker->randomElement(['Allow', 'Deny']),
      'Action' => $this->faker->words($this->faker->numberBetween(1, 3)),
      'Resource' => $this->faker->words($this->faker->numberBetween(1, 3)),
      'Condition' => $this->generateCondition(),
    ];
  }

  private function generateCondition(): array
  {
    return [
      $this->faker->word => [
        $this->faker->word => $this->faker->word,
      ],
    ];
  }

  public function withCustomStatement(array $statement)
  {
    return $this->state(function (array $attributes) use ($statement) {
      $attributes['document']['Statement'][] = $statement;
      return $attributes;
    });
  }
}

// // 基本的なポリシーを作成
// $policy = EloquentPolicy::factory()->create();

// // カスタムステートメントを持つポリシーを作成
// $customStatement = [
//   'Sid' => 'CustomStatement',
//   'Effect' => 'Allow',
//   'Action' => ['customAction1', 'customAction2'],
//   'Resource' => ['customResource1'],
//   'Condition' => [
//     'StringEquals' => [
//       'aws:username' => 'johndoe'
//     ]
//   ]
// ];

// $policyWithCustomStatement = EloquentPolicy::factory()
//   ->withCustomStatement($customStatement)
//   ->create();
