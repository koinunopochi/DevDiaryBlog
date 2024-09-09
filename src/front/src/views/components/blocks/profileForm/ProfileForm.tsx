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
import { UserDetailsResponse } from '@/services/UserService';

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
  initialData: ProfileFormData | (() => Promise<UserDetailsResponse>);
  defaultProfileIcons: string[] | (() => Promise<string[]>);
  onSubmit: (data: ProfileFormData) => void;
}

const MAX_LINKS = 15;

const ProfileForm: React.FC<ProfileFormProps> = React.memo(
  ({ initialData, defaultProfileIcons, onSubmit }) => {
    const [formData, setFormData] = useState<ProfileFormData>({
      displayName: '',
      bio: '',
      avatarUrl: '',
      socialLinks: {
        twitter: '',
        github: '',
      },
    });

    const [additionalLinks, setAdditionalLinks] = useState<SocialLink[]>([]);
    const [isValid, setIsValid] = useState<Record<string, boolean>>({
      displayName: true,
      bio: true,
      avatarUrl: true,
      twitter: true,
      github: true,
    });
    const [showIconSelector, setShowIconSelector] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
      const fetchData = async () => {
        try {
          let userData: ProfileFormData;

          if (typeof initialData === 'function') {
            setIsLoading(true);
            const response = await initialData();
            userData = {
              displayName: response.profile?.displayName || '',
              bio: response.profile?.bio || '',
              avatarUrl: response.profile?.avatarUrl || '',
              socialLinks: response.profile?.socialLinks || {
                twitter: '',
                github: '',
              },
            };
          } else {
            userData = initialData;
          }

          setFormData(userData);
          const otherLinks = Object.entries(userData.socialLinks)
            .filter(([key]) => key !== 'twitter' && key !== 'github')
            .map(([name, url]) => ({ name, url: url || '' }));
          setAdditionalLinks(otherLinks);
        } catch (err) {
          console.error('Error fetching data:', err);
          setError('データの取得中にエラーが発生しました。');
        } finally {
          setIsLoading(false);
        }
      };

      fetchData();
    }, [initialData, defaultProfileIcons]);

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
          value={formData.bio}
          onChange={(value, valid) => handleInputChange('bio', value, valid)}
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

    const totalLinks = additionalLinks.length + 2;

    if (isLoading) {
      return <div>読み込み中...</div>;
    }

    if (error) {
      return <div>エラー: {error}</div>;
    }

    return (
      <div className="max-w-2xl mx-auto p-4 sm:p-6 text-gray-800 dark:text-gray-200">
        <form onSubmit={handleSubmit} className="space-y-4 sm:space-y-6">
          <div className="flex flex-col items-center">
            <div className="relative mb-2">
              <Icon
                src={formData.avatarUrl}
                alt="プロフィールのアイコン"
                size="w-16 h-16 sm:w-20 sm:h-20"
                shape="rounded-full"
                defaultSrc={formData.avatarUrl ? undefined : unKnownUser}
                className="border-2 border-gray-200 dark:border-gray-600"
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
              className="text-sm sm:text-base text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200 underline"
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
          <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-4">
            <div className="flex flex-col mb-4 sm:mb-0">
              <button
                type="button"
                onClick={addAdditionalLink}
                className={`flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 mr-4 ${
                  totalLinks >= MAX_LINKS ? 'opacity-50 cursor-not-allowed' : ''
                }`}
                disabled={totalLinks >= MAX_LINKS}
              >
                <Plus size={18} className="mr-1" />
                <span className="text-sm sm:text-base">他のリンクを追加</span>
              </button>
              {totalLinks >= MAX_LINKS && (
                <span className="text-red-500 dark:text-red-400 text-xs sm:text-sm mt-1">
                  リンクの数は15個までです
                </span>
              )}
            </div>
            <button
              type="submit"
              className="w-full sm:w-auto flex items-center justify-center bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200 text-sm sm:text-base"
            >
              <Save size={18} className="mr-2" />
              保存
            </button>
          </div>
        </form>
      </div>
    );
  }
);

export default ProfileForm;
