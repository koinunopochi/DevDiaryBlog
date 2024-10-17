import React, { useState } from 'react';

const ColorSwatch = ({ color, name }) => (
  <div className="flex flex-col items-center mb-4">
    <div
      className="w-20 h-20 rounded-full shadow-md"
      style={{ backgroundColor: color }}
    ></div>
    <span className="mt-2 text-sm font-medium">{name}</span>
    <span className="text-xs">{color}</span>
  </div>
);

const ColorPalette = () => {
  const [currentTheme, setCurrentTheme] = useState('light');

  const themes = {
    light: {
      name: 'ライトモード',
      colors: [
        { name: 'メインカラー', color: '#ffa500' },
        { name: 'アクセント1', color: '#e69400' },
        { name: 'アクセント2', color: '#ffb733' },
        { name: '主要テキスト', color: '#333333' },
        { name: '副次テキスト', color: '#666666' },
        { name: '反転テキスト', color: '#ffffff' },
        { name: 'メイン背景', color: '#ffffff' },
        { name: 'セカンダリ背景', color: '#fff5e6' },
        { name: 'ダーク背景', color: '#333333' },
        { name: 'メインボーダー', color: '#ffa500' },
        { name: 'セカンダリボーダー', color: '#ffb733' },
        { name: 'リンク通常時', color: '#e69400' },
        { name: 'リンクホバー時', color: '#ffa500' },
        { name: '成功', color: '#4caf50' },
        { name: '警告', color: '#ff9800' },
        { name: 'エラー', color: '#f44336' },
        { name: '情報', color: '#2196f3' },
      ],
    },
    dark: {
      name: 'ダークモード',
      colors: [
        { name: 'メインカラー', color: '#ffa500' },
        { name: 'アクセント1', color: '#ffb733' },
        { name: 'アクセント2', color: '#e69400' },
        { name: '主要テキスト', color: '#e0e0e0' },
        { name: '副次テキスト', color: '#a0a0a0' },
        { name: '反転テキスト', color: '#080808' },
        { name: 'メイン背景', color: '#080808' },
        { name: 'セカンダリ背景', color: '#1a1a1a' },
        { name: 'ライト背景', color: '#333333' },
        { name: 'メインボーダー', color: '#ffa500' },
        { name: 'セカンダリボーダー', color: '#ffb733' },
        { name: 'リンク通常時', color: '#ffa500' },
        { name: 'リンクホバー時', color: '#ffb733' },
        { name: '成功', color: '#66bb6a' },
        { name: '警告', color: '#ffa726' },
        { name: 'エラー', color: '#ef5350' },
        { name: '情報', color: '#42a5f5' },
      ],
    },
    nature: {
      name: '自然テーマ',
      colors: [
        { name: 'メインカラー', color: '#4caf50' },
        { name: 'アクセント1', color: '#8bc34a' },
        { name: 'アクセント2', color: '#cddc39' },
        { name: '主要テキスト', color: '#33691e' },
        { name: '副次テキスト', color: '#558b2f' },
        { name: '反転テキスト', color: '#ffffff' },
        { name: 'メイン背景', color: '#f1f8e9' },
        { name: 'セカンダリ背景', color: '#dcedc8' },
        { name: 'ダーク背景', color: '#33691e' },
        { name: 'メインボーダー', color: '#4caf50' },
        { name: 'セカンダリボーダー', color: '#8bc34a' },
        { name: 'リンク通常時', color: '#4caf50' },
        { name: 'リンクホバー時', color: '#8bc34a' },
        { name: '成功', color: '#66bb6a' },
        { name: '警告', color: '#ffa726' },
        { name: 'エラー', color: '#ef5350' },
        { name: '情報', color: '#42a5f5' },
      ],
    },
  };

  return (
    <div className="p-6 bg-gray-100">
      <h2 className="text-2xl font-bold mb-4">tetex.tech 拡張カラーパレット</h2>
      <div className="mb-4">
        <label htmlFor="theme-select" className="mr-2">
          テーマを選択:
        </label>
        <select
          id="theme-select"
          value={currentTheme}
          onChange={(e) => setCurrentTheme(e.target.value)}
          className="p-2 border rounded"
        >
          {Object.keys(themes).map((theme) => (
            <option key={theme} value={theme}>
              {themes[theme].name}
            </option>
          ))}
        </select>
      </div>
      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        {themes[currentTheme].colors.map((item, index) => (
          <ColorSwatch key={index} color={item.color} name={item.name} />
        ))}
      </div>
    </div>
  );
};

export default ColorPalette;
