<?php

namespace App\Domain\ValueObjects;

use Illuminate\Support\Facades\Log;

class TagName
{
    private string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
        Log::info('class : TagName - method : constructor - $value : ' . $this->value);
    }

    public function validate(string $value): void
    {
        $trimmedValue = trim($value);
        
        if (empty($trimmedValue)) {
            Log::error('class : TagName - method : validate - $value : ' . $value);
            throw new \InvalidArgumentException("タグ名は空であってはいけません。");
        }

        if (mb_strlen($trimmedValue) > 25) {
            Log::error('class : TagName - method : validate - $value : ' . $value);
            throw new \InvalidArgumentException("タグ名は25文字以内である必要があります。");
        }

        if (!preg_match('/^[a-zA-Z0-9ぁ-んァ-ヶー一-龠０-９Ａ-Ｚａ-ｚ]+$/u', $trimmedValue)) {
            Log::error('class : TagName - method : validate - $value : ' . $value);
            throw new \InvalidArgumentException("タグ名には全角英数、半角英数、全角文字のみが使用できます。");
        }

        Log::info('class : TagName - method : validate - $value : ' . $value);
    }

    public function toString(): string
    {
        Log::info('class : TagName - method : toString - $value : ' . $this->value);
        return $this->value;
    }

    public function equals(TagName $other): bool
    {
        return $this->value === $other->value;
    }
}
