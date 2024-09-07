import React, { useState, useEffect } from 'react';
import InputUrl from '../../atoms/form/inputUrl/InputUrl';

interface ProfileFormData {
  socialLinks: {
    twitter?: string;
    github?: string;
  };
}

interface ProfileFormProps {
  initialData?: ProfileFormData;
  onSubmit: (data: ProfileFormData) => void;
}

const ProfileForm: React.FC<ProfileFormProps> = ({ initialData, onSubmit }) => {
  const [formData, setFormData] = useState<ProfileFormData>(() => ({
    socialLinks: {
      twitter: initialData?.socialLinks.twitter || '',
      github: initialData?.socialLinks.github || '',
    },
  }));

  const [isValid, setIsValid] = useState<Record<string, boolean>>({
    twitter: false,
    github: false,
  });

  useEffect(() => {
    if (initialData) {
      setFormData({
        socialLinks: {
          twitter: initialData.socialLinks.twitter || '',
          github: initialData.socialLinks.github || '',
        },
      });
    }
  }, [initialData]);

  const handleSocialLinkChange = (
    platform: 'twitter' | 'github',
    value: string,
    valid: boolean
  ) => {
    console.log('handleSocialLinkChange', platform, value, valid);
    setFormData((prev) => ({
      ...prev,
      socialLinks: { ...prev.socialLinks, [platform]: value },
    }));
    setIsValid((prev) => ({ ...prev, [platform]: valid }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (Object.values(isValid).some((valid) => !valid)) {
      alert('エラーがあるため保存できません。入力内容を確認してください。');
      return;
    }

    const submitData = {
      ...formData,
      socialLinks: {
        twitter: formData.socialLinks.twitter || '',
        github: formData.socialLinks.github || '',
      },
    };

    onSubmit(submitData);
  };

  return (
    <div className="max-w-2xl mx-auto p-6">
      <form onSubmit={handleSubmit} className="space-y-6">
        <InputUrl
          label="Twitter(X)"
          value={formData.socialLinks.twitter || ''}
          onChange={(value, valid) => {
            console.log('Twitter Input Change', value, valid);
            handleSocialLinkChange('twitter', value, valid);
          }}
          placeholder="https://twitter.com/blogtaro"
        />
        <InputUrl
          label="GitHub"
          value={formData.socialLinks.github || ''}
          onChange={(value, valid) => {
            console.log('GitHub Input Change', value, valid);
            handleSocialLinkChange('github', value, valid);
          }}
          placeholder="https://github.com/blogtaro"
        />
        <button
          type="submit"
          className="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
        >
          保存
        </button>
      </form>
    </div>
  );
};

export default ProfileForm;
