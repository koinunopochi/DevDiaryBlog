<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\UserId;
use App\Models\EloquentProfile;
use Illuminate\Support\Collection;
use App\Domain\Repositories\UserProfileRepositoryInterface;
use App\Domain\ValueObjects\DisplayName;
use App\Domain\ValueObjects\SocialLinkCollection;
use App\Domain\ValueObjects\Url;
use App\Domain\ValueObjects\UserBio;

class EloquentUserProfileRepository implements UserProfileRepositoryInterface
{
  public function all(): Collection
  {
    return EloquentProfile::all()->map(function (EloquentProfile $eloquentProfile) {
      return new Profile(
        new UserId($eloquentProfile->user_id),
        new DisplayName($eloquentProfile->display_name),
        new UserBio($eloquentProfile->bio),
        new Url($eloquentProfile->avatar_url),
        new SocialLinkCollection(json_decode($eloquentProfile->social_links, true))
      );
    });
  }

  public function findById(UserId $id): ?Profile
  {
    $eloquentProfile = EloquentProfile::where('user_id', $id->toString())->first();

    return $eloquentProfile ? new Profile(
      new UserId($eloquentProfile->user_id),
      new DisplayName($eloquentProfile->display_name),
      new UserBio($eloquentProfile->bio),
      new Url($eloquentProfile->avatar_url),
      new SocialLinkCollection(json_decode($eloquentProfile->social_links, true))
    ) : null;
  }

  public function save(Profile $profile): void
  {
    $eloquentProfile = EloquentProfile::where('user_id', $profile->getUserId()->toString())->first();

    if (!$eloquentProfile) {
      // 新規プロフィールの場合は Eloquent モデルを作成
      $eloquentProfile = new EloquentProfile();
      $eloquentProfile->user_id = $profile->getUserId()->toString();
    }

    // プロフィール情報を更新
    $eloquentProfile->display_name = $profile->getDisplayName()->toString();
    $eloquentProfile->bio = $profile->getBio()->toString();
    $eloquentProfile->avatar_url = $profile->getAvatarUrl()->toString();
    $eloquentProfile->social_links = json_encode($profile->getSocialLinks()->toArray());

    $eloquentProfile->save();
  }

  public function delete(Profile $profile): void
  {
    EloquentProfile::where('user_id', $profile->getUserId()->toString())->delete();
  }
}
