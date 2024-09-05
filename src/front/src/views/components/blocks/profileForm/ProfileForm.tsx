import React, { useState, useEffect } from 'react';
import { Save, Plus, Trash2 } from 'lucide-react';
import Input from '@components/atoms/form/input/Input';
import Textarea from '@components/atoms/form/textarea/Textarea';
import {
  validateAdditionalLinkName,
  validateDisplayName,
  validateUrl,
  validateUserBio,
} from './ProfileFormValidate';

interface SocialLink {
  name: string;
  url: string;
}

interface ProfileFormData {
  displayName: string;
  bio: string;
  avatarUrl: string;
  socialLinks: {
    twitter?: string;
    github?: string;
    [key: string]: string | undefined;
  };
}

interface ProfileFormProps {
  initialData?: ProfileFormData;
  onSubmit: (data: ProfileFormData) => void;
}

const MAX_LINKS = 15;

const ProfileForm: React.FC<ProfileFormProps> = ({ initialData, onSubmit }) => {
  const [formData, setFormData] = useState<ProfileFormData>(() => ({
    displayName: initialData?.displayName || '',
    bio: initialData?.bio || '',
    avatarUrl: initialData?.avatarUrl || '',
    socialLinks: {
      twitter: initialData?.socialLinks.twitter || '',
      github: initialData?.socialLinks.github || '',
    },
  }));

  const [additionalLinks, setAdditionalLinks] = useState<SocialLink[]>(() => {
    if (initialData) {
      return Object.entries(initialData.socialLinks)
        .filter(([key]) => key !== 'twitter' && key !== 'github')
        .map(([name, url]) => ({ name, url: url || '' }));
    }
    return [];
  });

  const [isValid, setIsValid] = useState<Record<string, boolean>>({
    displayName: false,
    bio: false,
    avatarUrl: false,
    twitter: false,
    github: false,
  });

  useEffect(() => {
    if (initialData) {
      setFormData({
        displayName: initialData.displayName || '',
        bio: initialData.bio || '',
        avatarUrl: initialData.avatarUrl || '',
        socialLinks: {
          twitter: initialData.socialLinks.twitter || '',
          github: initialData.socialLinks.github || '',
        },
      });

      const otherLinks = Object.entries(initialData.socialLinks)
        .filter(([key]) => key !== 'twitter' && key !== 'github')
        .map(([name, url]) => ({ name, url: url || '' }));
      setAdditionalLinks(otherLinks);
    }
  }, [initialData]);

  const handleInputChange = (
    field: keyof ProfileFormData,
    value: string,
    valid: boolean
  ) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
    setIsValid((prev) => ({ ...prev, [field]: valid }));
  };

  const handleSocialLinkChange = (
    platform: 'twitter' | 'github',
    value: string,
    valid: boolean
  ) => {
    setFormData((prev) => ({
      ...prev,
      socialLinks: { ...prev.socialLinks, [platform]: value },
    }));
    setIsValid((prev) => ({ ...prev, [platform]: valid }));
  };

  const handleAdditionalLinkChange = (
    index: number,
    field: 'name' | 'url',
    value: string,
    valid: boolean
  ) => {
    const newLinks = [...additionalLinks];
    newLinks[index][field] = value;
    setAdditionalLinks(newLinks);
    setIsValid((prev) => ({
      ...prev,
      [`additionalLink${index}_${field}`]: valid,
    }));
  };

  const addAdditionalLink = () => {
    if (additionalLinks.length + 2 < MAX_LINKS) {
      setAdditionalLinks([...additionalLinks, { name: '', url: '' }]);
      const newIndex = additionalLinks.length;
      setIsValid((prev) => ({
        ...prev,
        [`additionalLink${newIndex}_name`]: false,
        [`additionalLink${newIndex}_url`]: false,
      }));
    }
  };

  const removeAdditionalLink = (index: number) => {
    setAdditionalLinks((prevLinks) => {
      const newLinks = prevLinks.filter((_, i) => i !== index);
      return [...newLinks];
    });

    setIsValid((prevIsValid) => {
      const newIsValid = { ...prevIsValid };

      for (let i = index; i < additionalLinks.length - 1; i++) {
        newIsValid[`additionalLink${i}_name`] =
          newIsValid[`additionalLink${i + 1}_name`];
        newIsValid[`additionalLink${i}_url`] =
          newIsValid[`additionalLink${i + 1}_url`];

        delete newIsValid[`additionalLink${i + 1}_name`];
        delete newIsValid[`additionalLink${i + 1}_url`];
      }

      return newIsValid;
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (Object.values(isValid).some((valid) => !valid)) {
      alert(
        'エラーがあるため保存できません。必須項目や入力内容を確認してください。'
      );
      return;
    }

    const submitData = {
      ...formData,
      socialLinks: {
        twitter: formData.socialLinks.twitter || '',
        github: formData.socialLinks.github || '',
      },
    };

    additionalLinks.forEach((link) => {
      if (link.name && link.url) {
        submitData.socialLinks[
          link.name as keyof typeof submitData.socialLinks
        ] = link.url;
      }
    });

    Object.keys(submitData.socialLinks).forEach((key) => {
      if (!submitData.socialLinks[key as keyof typeof submitData.socialLinks]) {
        delete submitData.socialLinks[
          key as keyof typeof submitData.socialLinks
        ];
      }
    });

    onSubmit(submitData);
  };

  const totalLinks = additionalLinks.length + 2; // +2 for Twitter and GitHub

  return (
    <div className="min-w-[500px] max-w-[600px]: max-w-2xl mx-auto p-6">
      <form onSubmit={handleSubmit} className="space-y-6">
        <Input
          label="表示名"
          initialValue={formData.displayName}
          placeholder="ぶろぐたろう"
          onInputChange={(value, valid) =>
            handleInputChange('displayName', value, valid)
          }
          validate={validateDisplayName}
          required
        />
        <Textarea
          label="経歴・自己紹介"
          initialValue={formData.bio}
          placeholder="○○に興味があります！"
          onTextareaChange={(value, valid) =>
            handleInputChange('bio', value, valid)
          }
          validate={validateUserBio}
          required
        />
        <Input
          label="アバターURL"
          initialValue={formData.avatarUrl}
          placeholder="https://example.com/avatar.jpg"
          onInputChange={(value, valid) =>
            handleInputChange('avatarUrl', value, valid)
          }
          validate={validateUrl}
          required
        />
        <Input
          label="Twitter(X)"
          initialValue={formData.socialLinks.twitter}
          placeholder="https://twitter.com/blogtaro"
          onInputChange={(value, valid) =>
            handleSocialLinkChange('twitter', value, valid)
          }
          validate={validateUrl}
        />
        <Input
          label="GitHub"
          initialValue={formData.socialLinks.github}
          placeholder="https://github.com/blogtaro"
          onInputChange={(value, valid) =>
            handleSocialLinkChange('github', value, valid)
          }
          validate={validateUrl}
        />
        {additionalLinks.map((link, index) => (
          <div key={index} className="mb-4 border p-4 rounded-md relative">
            <Input
              label={`リンク${index + 1}`}
              placeholder="表示したい名前"
              initialValue={link.name}
              onInputChange={(value, valid) =>
                handleAdditionalLinkChange(index, 'name', value, valid)
              }
              validate={validateAdditionalLinkName}
              required
              className="mb-2"
            />
            <Input
              label={`URL${index + 1}`}
              placeholder="https://example.com"
              initialValue={link.url}
              onInputChange={(value, valid) =>
                handleAdditionalLinkChange(index, 'url', value, valid)
              }
              validate={validateUrl}
              required
              className="mb-2"
            />
            <button
              type="button"
              onClick={() => removeAdditionalLink(index)}
              className="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded p-1 transition-colors duration-200"
              aria-label={`リンク${index + 1}を削除`}
            >
              <Trash2 size={20} />
            </button>
          </div>
        ))}
        <div className="flex justify-between items-center pt-4">
          <div className="flex flex-col">
            <button
              type="button"
              onClick={addAdditionalLink}
              className={`flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 mr-4 ${
                totalLinks >= MAX_LINKS ? 'opacity-50 cursor-not-allowed' : ''
              }`}
              disabled={totalLinks >= MAX_LINKS}
            >
              <Plus size={20} className="mr-1" />
              他のリンクを追加
            </button>
            {totalLinks >= MAX_LINKS && (
              <span className="text-red-500 text-sm mt-1">
                リンクの数は15個までです
              </span>
            )}
          </div>
          <button
            type="submit"
            className="flex items-center bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition-colors duration-200"
          >
            <Save size={20} className="mr-2" />
            保存
          </button>
        </div>
      </form>
    </div>
  );
};

export default ProfileForm;
