import React from 'react';
import { Link, Mail, Calendar, Tag } from 'lucide-react';
import Icon from '@components/atoms/icon/Icon';
import github from '@img/github-mark.png';
import twitter from '@img/x-logo-black.png';

interface SocialLinks {
  github?: string;
  twitter?: string;
  [key: string]: string | undefined;
}

interface ProfileData {
  displayName: string;
  bio: string;
  avatarUrl: string;
  socialLinks: SocialLinks;
}

interface UserData {
  id: string;
  user: {
    name: string;
    email: string;
    status: string;
    createdAt: string;
    updatedAt: string;
  };
  profile: ProfileData | null;
}

interface ProfileProps {
  userData: UserData;
  isEditable: boolean;
  onEditProfile: () => void;
}

const Profile: React.FC<ProfileProps> = ({
  userData,
  isEditable,
  onEditProfile,
}) => {
  const profileEditButton = (
    <button
      className="mt-4 w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 py-2 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
      onClick={onEditProfile}
    >
      プロフィールを編集する
    </button>
  );

  if (!userData.profile) {
    return (
      <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md min-w-[280px] max-w-[400px] w-full mx-auto">
        <p className="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
          プロフィールが設定されていません
        </p>
        {isEditable && profileEditButton}
      </div>
    );
  }

  const { displayName, bio, avatarUrl, socialLinks } = userData.profile;

  return (
    <div className="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md min-w-[280px] max-w-[400px] w-full mx-auto">
      <div className="flex flex-col items-center">
        <Icon
          src={avatarUrl}
          alt={displayName + 'のユーザーアイコン'}
          size="w-20 h-20"
          shape="rounded-full"
          onClick={() => {
            console.log('ユーザーを編集する');
          }}
        />
        <h2 className="mt-4 text-xl font-bold dark:text-white">
          {displayName}
        </h2>
        <p className="text-gray-600 dark:text-gray-400">
          @{userData.user.name}
        </p>

        <div className="flex mt-2 space-x-2">
          {socialLinks.github && (
            <Icon
              src={github}
              alt="github"
              size="w-6 h-6"
              shape=""
              isButton={true}
              href={socialLinks.github}
            />
          )}
          {socialLinks.twitter && (
            <Icon
              src={twitter}
              alt="twitter"
              size="w-6 h-6"
              shape=""
              isButton={true}
              href={socialLinks.twitter}
            />
          )}
        </div>

        <p className="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
          {bio}
        </p>
      </div>

      {isEditable && profileEditButton}

      <div className="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-2">
        <p className="flex items-center">
          <Mail className="w-4 h-4 mr-2 text-gray-800 dark:text-gray-200" />
          <span className="truncate">{userData.user.email}</span>
        </p>
        <p className="flex items-center">
          <Calendar className="w-4 h-4 mr-2 text-gray-800 dark:text-gray-200" />
          <span>{new Date(userData.user.createdAt).toLocaleDateString()}</span>
        </p>
        <p className="flex items-center">
          <Tag className="w-4 h-4 mr-2 text-gray-800 dark:text-gray-200" />
          <span>{userData.user.status}</span>
        </p>
        {Object.entries(socialLinks).map(([key, value]) => {
          if (key !== 'github' && key !== 'twitter' && value) {
            return (
              <a
                key={key}
                href={value}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
              >
                <Link className="w-4 h-4 mr-2 text-gray-800 dark:text-gray-200" />
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

