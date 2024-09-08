import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { Save, Plus, Trash2 } from 'lucide-react';
import Icon from '@components/atoms/icon/Icon';
import ProfileIconSelector from '@components/blocks/profileIconSelector/ProfileIconSelector';
import unKnownUser from '@img/unknown-user.png';
import InputDisplayName from '@components/atoms/form/inputDisplayName/InputDisplayName';
import InputUrl from '@components/atoms/form/inputUrl/InputUrl';
import InputBio from '@components/atoms/form/inputBio/InputBio';
import InputAdditionalLinkName from '@components/atoms/form/inputAdditionalLinkName/InputAdditionalLinkName';
import Input from '@components/atoms/form/input/Input';

interface SocialLink {
  name: string;
  url: string;
}

export interface ProfileFormData {
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
  defaultProfileIcons: Array<string> | (() => Promise<string[]>);
  onSubmit: (data: ProfileFormData) => void;
}

const MAX_LINKS = 15;

const ProfileForm: React.FC<ProfileFormProps> = React.memo(
  ({ initialData, defaultProfileIcons, onSubmit }) => {
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
      displayName: true,
      bio: true,
      avatarUrl: true,
      twitter: true,
      github: true,
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

    const handleIconChange = useCallback((newIconUrl: string) => {
      setFormData((prev) => ({ ...prev, avatarUrl: newIconUrl }));
      setIsValid((prev) => ({ ...prev, avatarUrl: true }));
      setShowIconSelector(false);
    }, []);

    const handleInputChange = useCallback(
      (field: keyof ProfileFormData, value: string, valid: boolean) => {
        setFormData((prev) => ({ ...prev, [field]: value }));
        setIsValid((prev) => ({ ...prev, [field]: valid }));
      },
      []
    );

    const handleSocialLinkChange = useCallback(
      (platform: 'twitter' | 'github', value: string, valid: boolean) => {
        setFormData((prev) => ({
          ...prev,
          socialLinks: { ...prev.socialLinks, [platform]: value },
        }));
        setIsValid((prev) => ({ ...prev, [platform]: valid }));
      },
      []
    );

    const handleAdditionalLinkChange = useCallback(
      (index: number, field: 'name' | 'url', value: string, valid: boolean) => {
        setAdditionalLinks((prevLinks) => {
          const newLinks = [...prevLinks];
          newLinks[index][field] = value;
          return newLinks;
        });
        setIsValid((prev) => ({
          ...prev,
          [`additionalLink${index}_${field}`]: valid,
        }));
      },
      []
    );

    const addAdditionalLink = useCallback(() => {
      if (additionalLinks.length + 2 < MAX_LINKS) {
        setAdditionalLinks((prevLinks) => [
          ...prevLinks,
          { name: '', url: '' },
        ]);
        const newIndex = additionalLinks.length;
        setIsValid((prev) => ({
          ...prev,
          [`additionalLink${newIndex}_name`]: false,
          [`additionalLink${newIndex}_url`]: false,
        }));
      }
    }, [additionalLinks.length]);

    const removeAdditionalLink = useCallback(
      (index: number) => {
        setAdditionalLinks((prevLinks) =>
          prevLinks.filter((_, i) => i !== index)
        );
        setIsValid((prevIsValid) => {
          const newIsValid = { ...prevIsValid };
          // Remove validation state for the deleted link
          delete newIsValid[`additionalLink${index}_name`];
          delete newIsValid[`additionalLink${index}_url`];
          // Shift validation state for remaining links
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
      },
      [additionalLinks.length]
    );

    const handleSubmit = useCallback(
      (e: React.FormEvent) => {
        e.preventDefault();

        const isFormValid = Object.entries(isValid).every(([key, valid]) => {
          if (key.startsWith('additionalLink')) {
            const [, index] = key.split('_');
            const linkIndex = parseInt(index, 10);
            return linkIndex >= additionalLinks.length || valid;
          }
          return valid;
        });

        if (!isFormValid) {
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
          if (
            !submitData.socialLinks[key as keyof typeof submitData.socialLinks]
          ) {
            delete submitData.socialLinks[
              key as keyof typeof submitData.socialLinks
            ];
          }
        });

        onSubmit(submitData);
      },
      [formData, additionalLinks, isValid, onSubmit]
    );

    const memoizedInputDisplayName = useMemo(
      () => (
        <InputDisplayName
          value={formData.displayName}
          onChange={(value, valid) =>
            handleInputChange('displayName', value, valid)
          }
        />
      ),
      [formData.displayName, handleInputChange]
    );

    const memoizedInputBio = useMemo(
      () => (
        <InputBio
          initialValue={formData.bio}
          onInputChange={(value, valid) =>
            handleInputChange('bio', value, valid)
          }
        />
      ),
      [formData.bio, handleInputChange]
    );

    const memoizedInputUrlTwitter = useMemo(
      () => (
        <InputUrl
          label="Twitter(X)"
          value={formData.socialLinks.twitter || ''}
          onChange={(value, valid) =>
            handleSocialLinkChange('twitter', value, valid)
          }
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
          onChange={(value, valid) =>
            handleSocialLinkChange('github', value, valid)
          }
          placeholder="https://github.com/blogtaro"
        />
      ),
      [formData.socialLinks.github, handleSocialLinkChange]
    );

    const memoizedAdditionalLinks = useMemo(
      () =>
        additionalLinks.map((link, index) => (
          <div key={index} className="mb-4 border p-4 rounded-md relative">
            <div className="mb-2">
              <InputAdditionalLinkName
                label={`リンク名${index + 1}`}
                placeholder="表示したい名前"
                value={link.name}
                onChange={(value, valid) =>
                  handleAdditionalLinkChange(index, 'name', value, valid)
                }
                required
              />
            </div>
            <InputUrl
              label={`URL${index + 1}`}
              value={link.url}
              onChange={(value, valid) =>
                handleAdditionalLinkChange(index, 'url', value, valid)
              }
              placeholder="https://dev-diary-blog"
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
        )),
      [additionalLinks, handleAdditionalLinkChange, removeAdditionalLink]
    );

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
              <div className="sr-only">
                <Input
                  label="隠しアバターリンク"
                  onChange={(value, isValid) => {
                    console.log('avatarUrl is updated', value, isValid);
                  }}
                  value={formData.avatarUrl || ''}
                  required
                />
              </div>
            </div>
            <button
              type="button"
              onClick={() => setShowIconSelector(!showIconSelector)}
              className="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200 underline"
            >
              {showIconSelector ? 'キャンセル' : '変更する'}
            </button>
          </div>
            <div className="mt-4">
              <ProfileIconSelector
                icons={defaultProfileIcons}
                selectedIcon={formData.avatarUrl}
                onSelectIcon={handleIconChange}
                isVisible={showIconSelector}
              />
            </div>
          {memoizedInputDisplayName}
          {memoizedInputBio}
          {memoizedInputUrlTwitter}
          {memoizedInputUrlGithub}
          {memoizedAdditionalLinks}
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
  }
);

export default ProfileForm;
