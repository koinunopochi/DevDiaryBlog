import React, { useState, useEffect } from 'react';
import { Save, Plus, Trash2 } from 'lucide-react';
import Icon from '@components/atoms/icon/Icon';
import ProfileIconSelector from '@components/blocks/profileIconSelector/ProfileIconSelector';
import { validateUrl } from './ProfileFormValidate';
import unKnownUser from '@img/unknown-user.png';
import InputDisplayName from '../../atoms/form/inputDisplayName/InputDisplayName';
import InputUrl from '../../atoms/form/inputUrl/InputUrl';
import InputBio from '../../atoms/form/inputBio/InputBio';
import Input from '../../atoms/form/input/Input';

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
  defaultProfileIcons: Array<string>;
  onSubmit: (data: ProfileFormData) => void;
}

const MAX_LINKS = 15;

const ProfileForm: React.FC<ProfileFormProps> = ({
  initialData,
  defaultProfileIcons,
  onSubmit,
}) => {
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

  const [showIconSelector, setShowIconSelector] = useState(false);

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

  const handleIconChange = (newIconUrl: string) => {
    setFormData((prev) => ({ ...prev, avatarUrl: newIconUrl }));
    setIsValid((prev) => ({ ...prev, avatarUrl: true }));
    setShowIconSelector(false);
  };

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
    console.log('handleSocialLinkChange', platform, value, valid);
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
    console.log('handleAdditionalLinkChange', index, field, value, valid);
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
        <div className="flex flex-col items-center">
          <div className="relative mb-2">
            <Icon
              src={formData.avatarUrl}
              alt="プロフィールのアイコン"
              size="w-20 h-20"
              shape="rounded-full"
              defaultSrc={formData.avatarUrl ? undefined : unKnownUser}
              className="border-2 border-gray-200"
            />
          </div>
          <button
            type="button"
            onClick={() => setShowIconSelector(!showIconSelector)}
            className="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200 underline"
          >
            {showIconSelector ? 'キャンセル' : '変更する'}
          </button>
        </div>
        {showIconSelector && (
          <div className="mt-4">
            <ProfileIconSelector
              icons={defaultProfileIcons}
              selectedIcon={formData.avatarUrl}
              onSelectIcon={handleIconChange}
            />
          </div>
        )}
        <InputDisplayName
          value={formData.displayName}
          onChange={(value, valid) =>
            handleInputChange('displayName', value, valid)
          }
        />
        <InputBio
          initialValue={formData.bio}
          onInputChange={(value, valid) =>
            handleInputChange('bio', value, valid)
          }
        />
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
          onChange={(value, valid) =>
            handleSocialLinkChange('github', value, valid)
          }
          placeholder="https://github.com/blogtaro"
        />
        {additionalLinks.map((link, index) => (
          <div key={index} className="mb-4 border p-4 rounded-md relative">
            <Input
              label={`リンク${index + 1}`}
              placeholder="表示したい名前"
              value={link.name}
              onChange={(value, valid) =>
                handleAdditionalLinkChange(index, 'name', value, valid)
              }
              required
            />
            <Input
              label={`URL${index + 1}`}
              value={link.url}
              onChange={(value, valid) =>
                handleAdditionalLinkChange(index, 'url', value, valid)
              }
              validate={validateUrl}
              required
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
