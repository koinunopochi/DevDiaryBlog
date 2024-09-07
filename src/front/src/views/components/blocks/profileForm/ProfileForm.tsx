import React, { useState, useEffect, useCallback, useMemo } from 'react';
import InputUrl from '@components/atoms/form/inputUrl/InputUrl';
import InputName from '@components/atoms/form/inputName/InputName';
import { mockCheckNameAvailability } from '@components/atoms/form/inputName/InputName.stories';

interface ProfileFormData {
  name: string;
  socialLinks: {
    twitter?: string;
    github?: string;
  };
}

interface ProfileFormProps {
  initialData?: ProfileFormData;
  onSubmit: (data: ProfileFormData) => void;
}

const ProfileForm: React.FC<ProfileFormProps> = React.memo(
  ({ initialData, onSubmit }) => {
    const [formData, setFormData] = useState<ProfileFormData>(() => ({
      name: initialData?.name || '',
      socialLinks: {
        twitter: initialData?.socialLinks.twitter || '',
        github: initialData?.socialLinks.github || '',
      },
    }));

    const [isValid, setIsValid] = useState<Record<string, boolean>>({
      name: false,
      twitter: false,
      github: false,
    });

    useEffect(() => {
      if (initialData) {
        setFormData({
          name: initialData.name || '',
          socialLinks: {
            twitter: initialData.socialLinks.twitter || '',
            github: initialData.socialLinks.github || '',
          },
        });
      }
    }, [initialData]);

    const handleNameChange = useCallback((value: string, valid: boolean) => {
      console.log('handleNameChange', value, valid);
      setFormData((prev) => ({ ...prev, name: value }));
      setIsValid((prev) => ({ ...prev, name: valid }));
    }, []);

    const handleSocialLinkChange = useCallback(
      (platform: 'twitter' | 'github', value: string, valid: boolean) => {
        console.log('handleSocialLinkChange', platform, value, valid);
        setFormData((prev) => ({
          ...prev,
          socialLinks: { ...prev.socialLinks, [platform]: value },
        }));
        setIsValid((prev) => ({ ...prev, [platform]: valid }));
      },
      []
    );

    const handleSubmit = useCallback(
      (e: React.FormEvent) => {
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
      },
      [formData, isValid, onSubmit]
    );

    const memoizedInputName = useMemo(
      () => (
        <InputName
          value={formData.name}
          onInputChange={(value, valid) => {
            console.log('Name Input Change', value, valid);
            handleNameChange(value, valid);
          }}
          placeholder="山田 太郎"
          checkNameAvailability={mockCheckNameAvailability}
        />
      ),
      [formData.name, handleNameChange]
    );

    const memoizedInputUrlTwitter = useMemo(
      () => (
        <InputUrl
          label="Twitter(X)"
          value={formData.socialLinks.twitter || ''}
          onChange={(value, valid) => {
            console.log('Twitter Input Change', value, valid);
            handleSocialLinkChange('twitter', value, valid);
          }}
          placeholder="https://twitter.com/blogtaro"
        />
      ),
      [formData.socialLinks.twitter, handleSocialLinkChange]
    );

    const memoizedInputUrlGithub = useMemo(
      () => (
        <InputUrl
          label="GitHub"
          value={formData.socialLinks.github || ''}
          onChange={(value, valid) => {
            console.log('GitHub Input Change', value, valid);
            handleSocialLinkChange('github', value, valid);
          }}
          placeholder="https://github.com/blogtaro"
        />
      ),
      [formData.socialLinks.github, handleSocialLinkChange]
    );

    return (
      <div className="max-w-2xl mx-auto p-6">
        <form onSubmit={handleSubmit} className="space-y-6">
          {memoizedInputName}
          {memoizedInputUrlTwitter}
          {memoizedInputUrlGithub}
          <button
            type="submit"
            className="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
          >
            保存
          </button>
        </form>
      </div>
    );
  }
);

export default ProfileForm;
