import React, { useState, useMemo, useEffect } from 'react';
import { motion } from 'framer-motion';
import { useNavigate } from 'react-router-dom';

interface WishItem {
  id: number;
  name: string;
  count: number;
  category: 'feature' | 'bug' | 'improvement';
}

const wishItems: WishItem[] = [
  { id: 1, name: 'ダークモード', count: 100, category: 'feature' },
  { id: 2, name: 'パフォーマンス改善', count: 80, category: 'improvement' },
  { id: 3, name: 'バグ修正', count: 60, category: 'bug' },
  { id: 4, name: 'レスポンシブデザイン', count: 100, category: 'feature' },
  { id: 5, name: 'セキュリティ強化', count: 100, category: 'improvement' },
  { id: 6, name: 'オフライン機能', count: 90, category: 'feature' },
  { id: 7, name: 'データ同期', count: 70, category: 'improvement' },
  // テスト用に項目を追加
  ...Array.from({ length: 20 }, (_, i) => ({
    id: i + 8,
    name: `テスト項目 ${i + 1}`,
    count: Math.floor(Math.random() * 100) + 10,
    category: ['feature', 'bug', 'improvement'][
      Math.floor(Math.random() * 3)
    ] as 'feature' | 'bug' | 'improvement',
  })),
];

const getColorByCategory = (category: string) => {
  const colors = {
    feature: '#4169E1', // ロイヤルブルー
    bug: '#DC143C', // クリムゾン
    improvement: '#32CD32', // ライムグリーン
  };
  return colors[category as keyof typeof colors];
};

const getEnergyBallStyle = (
  count: number,
  maxCount: number,
  category: string
) => {
  const intensity = count / maxCount;
  const baseColor = getColorByCategory(category);
  const glowSize = 20 + intensity * 30; // グローの大きさを人数に応じて変更
  const glowOpacity = 0.5 + intensity * 0.5; // グローの不透明度を人数に応じて変更

  return {
    background: `radial-gradient(circle, ${baseColor} 0%, rgba(0,0,0,0) 70%)`,
    boxShadow: `0 0 ${glowSize}px ${glowOpacity * 100}% ${baseColor}`,
    animation: `pulse ${3 - intensity * 2}s infinite alternate`, // 人数が多いほど速く脈動
  };
};

const WaveEffect: React.FC<{ color: string }> = ({
  color,
}) => {
  return (
    <>
      {[...Array(5)].map((_, index) => (
        <motion.div
          key={index}
          className="absolute inset-0 rounded-full"
          style={{
            border: `2px solid ${color}`,
            opacity: 0,
          }}
          animate={{
            scale: [1, 2.5],
            opacity: [0.4, 0],
          }}
          transition={{
            duration: 4, // アニメーションの持続時間（秒）
            ease: 'easeInOut',
            times: [0, 1],
            repeat: Infinity,
            delay: index, // 各波の開始タイミングを1秒ずつずらす
            repeatDelay: 5, // アニメーション終了後、次の開始まで4秒待機
          }}
        />
      ))}
    </>
  );
};


const WishesRootPage: React.FC = () => {
  const [hoveredItem, setHoveredItem] = useState<number | null>(null);
  const [containerWidth, setContainerWidth] = useState(0);
  const maxCount = Math.max(...wishItems.map((item) => item.count));

    const navigate = useNavigate();

  useEffect(() => {
    const updateWidth = () => {
      const container = document.getElementById('wishes-container');
      if (container) {
        setContainerWidth(container.offsetWidth);
      }
    };

    updateWidth();
    window.addEventListener('resize', updateWidth);
    return () => window.removeEventListener('resize', updateWidth);
  }, []);

  const gridColumns = Math.floor(containerWidth / 120);
  const gridRows = Math.ceil(wishItems.length / gridColumns);

  const positionedItems = useMemo(() => {
    if (containerWidth === 0) return [];

    const grid = Array(gridRows)
      .fill(null)
      .map(() => Array(gridColumns).fill(false));
    const sortedItems = [...wishItems].sort((a, b) => b.count - a.count);

    return sortedItems.map((item) => {
      let row, col;
      do {
        row = Math.floor(Math.random() * gridRows);
        col = Math.floor(Math.random() * gridColumns);
      } while (grid[row][col]);

      grid[row][col] = true;

      const maxSize = Math.min(containerWidth / gridColumns, 120);
      const size = 60 + (item.count / maxCount) * (maxSize - 60);
      const top =
        6 +
        row * (100 / gridRows) +
        Math.random() * (100 / gridRows - size / 6);
      const left =
        col * (100 / gridColumns) +
        Math.random() * (100 / gridColumns - size / (containerWidth / 100));

      return { ...item, size, top, left };
    });
  }, [containerWidth, maxCount]);

return (
  <div className="container mx-auto p-8 min-h-screen border rounded-lg">
    <h1 className="text-4xl font-bold mb-8 text-center">改善提案クラスター</h1>
    <div
      id="wishes-container"
      className="relative w-full min-h-screen bg-gray-800 rounded-xl shadow-lg p-4 overflow-x-hidden overflow-y-auto"
      style={{ height: `${gridRows * 120}px`, maxHeight: '80vh' }}
    >
      {positionedItems.map((item) => (
        <motion.div
          key={item.id}
          className="absolute flex items-center justify-center cursor-pointer"
          style={{
            width: `${item.size}px`,
            height: `${item.size}px`,
            top: `${item.top}%`,
            left: `${item.left}%`,
          }}
          whileHover={{ scale: 1.1, zIndex: 10 }}
          initial={{ opacity: 0, scale: 0 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.5 }}
          onHoverStart={() => setHoveredItem(item.id)}
          onHoverEnd={() => setHoveredItem(null)}
          onClick={() => navigate(`/wish/${item.id}`)}
        >
          {/* エナジーボール */}
          <motion.div
            className="absolute inset-0 rounded-full"
            style={{
              ...getEnergyBallStyle(item.count, maxCount, item.category),
            }}
            animate={{
              scale: [1, 1.05, 1],
            }}
            transition={{
              duration: 2,
              ease: 'easeInOut',
              repeat: Infinity,
            }}
          />

          {/* 波動エフェクト（大きな願い項目のみ） */}
          {item.count > maxCount * 0.7 && (
            <WaveEffect
              color={getColorByCategory(item.category)}
            />
          )}

          {/* テキストコンテンツ */}
          <div className="relative z-10 text-center">
            <p
              className="text-white font-bold"
              style={{
                fontSize: `${10 + (item.count / maxCount) * 6}px`,
                textShadow: '0 0 5px rgba(0,0,0,0.5)',
              }}
            >
              {item.name}
            </p>
          </div>
        </motion.div>
      ))}
    </div>
    <div className="mt-8 text-center">
      <h2 className="text-2xl font-bold mb-4">カテゴリー</h2>
      <div className="flex justify-center gap-6">
        <div className="flex items-center">
          <div className="w-6 h-6 rounded-full bg-blue-500 mr-2"></div>
          <span className="">新機能</span>
        </div>
        <div className="flex items-center">
          <div className="w-6 h-6 rounded-full bg-red-500 mr-2"></div>
          <span className="">バグ修正</span>
        </div>
        <div className="flex items-center">
          <div className="w-6 h-6 rounded-full bg-green-500 mr-2"></div>
          <span className="">改善</span>
        </div>
      </div>
    </div>
  </div>
);
};

export default WishesRootPage;
