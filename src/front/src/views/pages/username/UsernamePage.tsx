import React, { useState, useEffect, useCallback, useMemo } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { UserService } from '@/services/UserService';
import Profile from '@/views/components/blocks/profile/Profile';
import { EnhancedApiClient } from '@/infrastructure/utils/EnhancedApiClient';
import InfiniteScrollArticleList from '@components/modules/infiniteScrollArticleList/InfiniteScrollArticleList';
import { ArticlePreviewProps } from '@components/blocks/articlePreview/ArticlePreview';

interface UsernamePageProps {
  apiClient?: EnhancedApiClient;
}

interface Article extends Omit<ArticlePreviewProps, 'onTagClick'> {
  id: string;
  title: string;
  author: {
    username: string;
    displayName: string;
    profileImage: string;
  };
  likes: number;
  tags: string[];
  createdAt: string;
  updatedAt: string;
}

interface Response {
  articles: Article[];
  nextCursor: string;
  hasNextPage: boolean;
  totalItems: number;
}

const UsernamePage: React.FC<UsernamePageProps> = ({ apiClient }) => {
  const navigate = useNavigate();
  const { username } = useParams<{ username: string }>();
  const [initialArticles, setInitialArticles] = useState<Article[]>([]);
  const [initialNextCursor, setInitialNextCursor] = useState<string | null>(
    null
  );
  const [isInitialLoading, setIsInitialLoading] = useState(true);
  const [userInfo, setUserInfo] = useState(null);

  const handleEditProfile = useMemo(
    () => () => {
      navigate('/settings/profile');
    },
    [navigate]
  );

  const handleTagClick = useMemo(
    () => (name: string) => {
      navigate(`/tags/${name}`);
    },
    [navigate]
  );

  const fetchArticles = useCallback(
    async (cursor: string | null) => {
      if (!apiClient) return { articles: [], nextCursor: null };

      try {
        const params = new URLSearchParams({
          limit: '10',
          sortBy: 'created_at',
          sortDirection: 'desc',
        });

        if (cursor) {
          params.append('cursor', cursor);
        }

        const response = await apiClient.get<Response>(
          `/api/users/${username}/articles?${params.toString()}`
        );

        console.log('Fetched articles:', response.articles); // デバッグログ

        return {
          articles: response.articles,
          nextCursor: response.hasNextPage ? response.nextCursor : null,
        };
      } catch (error) {
        console.error('Failed to fetch articles:', error);
        return { articles: [], nextCursor: null };
      }
    },
    [apiClient, username]
  );
  useEffect(() => {
    const initializeArticles = async () => {
      setIsInitialLoading(true);
      const { articles, nextCursor } = await fetchArticles(null);
      setInitialArticles(articles);
      setInitialNextCursor(nextCursor);
      setIsInitialLoading(false);
    };

    initializeArticles();
  }, [username, fetchArticles]);

  useEffect(() => {
    const fetchUserInfo = async () => {
      if (!apiClient) return;
      const userService = new UserService(apiClient);
      try {
        const info = await userService.getUserInfo({
          search_type: 'name',
          value: username || '',
        });
        setUserInfo(info);
      } catch (error) {
        console.error('Failed to fetch user info:', error);
      }
    };

    fetchUserInfo();
  }, [apiClient, username]);

  if (!apiClient) return null;

  const MemoizedProfile = useMemo(() => React.memo(Profile), []);

  return (
    <div>
      <div className='mb-3'>
      <MemoizedProfile
        isEditable={true}
        onEditProfile={handleEditProfile}
        userData={userInfo}
      />
      </div>
      {isInitialLoading ? (
        <p>記事を読み込み中...</p>
      ) : initialArticles.length > 0 ? (
        <div className='p-2'>
          <InfiniteScrollArticleList
            initialArticles={initialArticles}
            onTagClick={handleTagClick}
            fetchMoreArticles={fetchArticles}
            initialNextCursor={initialNextCursor}
          />
        </div>
      ) : (
        <p>記事がありません。</p>
      )}
    </div>
  );
};

export default UsernamePage;
