/* eslint-disable @typescript-eslint/no-explicit-any */
import React, { useState, useEffect, useCallback } from 'react';
import { Link, Mail, Calendar, Tag } from 'lucide-react';
import Icon from '@components/atoms/icon/Icon';
import github from '@img/github-mark.png';
import twitter from '@img/x-logo-black.png';
import { UserDetailsResponse } from '@/services/UserService';
import NotFound from '@components/atoms/notFound/NotFound';

interface ProfileProps {
  userData: UserDetailsResponse | (() => Promise<UserDetailsResponse>);
  isEditable: boolean;
  onEditProfile: () => void;
}

const Profile: React.FC<ProfileProps> = ({
  userData,
  isEditable,
  onEditProfile,
}) => {
  const [loadedUserData, setLoadedUserData] =
    useState<UserDetailsResponse | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [isNotFound, setIsNotFound] = useState<boolean>(false);

  const loadUserData = useCallback(async () => {
    if (typeof userData === 'function') {
      setIsLoading(true);
      try {
        const data = await userData();
        setLoadedUserData(data);
        setIsNotFound(false);
      } catch (error: any) {
        console.error('ユーザーデータの読み込みに失敗しました', error);
        if (
          error.message === 'HTTP error! status: 404' ||
          error.message === 'HTTP error! status: 400'
        ) {
          setIsNotFound(true);
        }
        setLoadedUserData(null);
      } finally {
        setIsLoading(false);
      }
    } else {
      setLoadedUserData(userData);
      setIsNotFound(false);
    }
  }, [userData]);

  useEffect(() => {
    loadUserData();
  }, [loadUserData]);

  if (isNotFound) {
    return <NotFound />;
  }

const profileEditButton = (
  <button
    className="mt-4 w-full bg-background-secondary text-primary py-2 px-4 rounded-md hover:bg-accent2 transition-colors text-sm sm:text-base"
    onClick={onEditProfile}
  >
    プロフィールを編集する
  </button>
);

if (isLoading) {
  return (
    <div className="bg-background-main p-4 rounded-lg shadow-md w-full max-w-sm mx-auto">
      <p className="text-sm text-secondary text-center">
        ユーザーデータを読み込んでいます...
      </p>
    </div>
  );
}

if (!loadedUserData || !loadedUserData.profile) {
  return (
    <div className="bg-background-main p-4 rounded-lg shadow-md w-full max-w-sm mx-auto">
      <p className="mt-4 text-sm text-secondary text-center">
        プロフィールが設定されていません
      </p>
      {isEditable && loadedUserData?.auth.canUpdate && profileEditButton}
    </div>
  );
}


  const { displayName, bio, avatarUrl, socialLinks } = loadedUserData.profile;

  return (
    <div className="bg-background-main p-4 sm:p-6 rounded-lg w-full max-w-sm mx-auto border">
      <div className="flex flex-col items-center">
        <Icon
          src={avatarUrl}
          alt={displayName + 'のユーザーアイコン'}
          size="w-16 h-16 sm:w-20 sm:h-20"
          shape="rounded-full"
          onClick={() => {
            console.log('ユーザーを編集する');
          }}
        />
        <h2 className="mt-4 text-lg sm:text-xl font-bold text-primary">
          {displayName}
        </h2>
        <p className="text-sm text-secondary">@{loadedUserData.user.name}</p>

        <div className="flex mt-2 space-x-2">
          {socialLinks.github && (
            <Icon
              src={github}
              alt="github"
              size="w-5 h-5 sm:w-6 sm:h-6"
              shape=""
              isButton={true}
              href={socialLinks.github}
            />
          )}
          {socialLinks.twitter && (
            <Icon
              src={twitter}
              alt="twitter"
              size="w-5 h-5 sm:w-6 sm:h-6"
              shape=""
              isButton={true}
              href={socialLinks.twitter}
            />
          )}
        </div>

        <p className="mt-4 text-xs sm:text-sm text-secondary text-center">
          {bio}
        </p>
      </div>

      {isEditable && loadedUserData.auth.canUpdate && profileEditButton}

      <div className="mt-4 text-xs sm:text-sm text-secondary space-y-2">
        <p className="flex items-center">
          <Mail className="w-4 h-4 mr-2 text-primary" />
          <span className="truncate">{loadedUserData.user.email}</span>
        </p>
        <p className="flex items-center">
          <Calendar className="w-4 h-4 mr-2 text-primary" />
          <span>
            {new Date(loadedUserData.user.createdAt).toLocaleDateString()}
          </span>
        </p>
        <p className="flex items-center">
          <Tag className="w-4 h-4 mr-2 text-primary" />
          <span>{loadedUserData.user.status}</span>
        </p>
        {Object.entries(socialLinks).map(([key, value]) => {
          if (key !== 'github' && key !== 'twitter' && value) {
            return (
              <a
                key={key}
                href={value}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center text-secondary hover:text-primary transition-colors"
              >
                <Link className="w-4 h-4 mr-2 text-primary" />
                <span>{key}</span>
              </a>
            );
          }
          return null;
        })}
      </div>
    </div>
  );
};

export default Profile;
