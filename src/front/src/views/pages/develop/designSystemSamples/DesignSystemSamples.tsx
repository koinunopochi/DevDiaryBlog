import React, { useState } from 'react';

const DesignSystemSamples = () => {
  const [currentTheme, setCurrentTheme] = useState('light');

  const themes = {
    light: {
      name: 'ライトモード',
      colors: {
        primary: '#ffa500',
        primaryHover: '#e69400',
        secondary: '#fff5e6',
        text: '#333333',
        subText: '#666666',
        background: '#ffffff',
        darkBackground: '#333333',
        border: '#ffa500',
        link: '#e69400',
        linkHover: '#ffa500',
        success: '#4caf50',
        warning: '#ff9800',
        error: '#f44336',
        info: '#2196f3',
      },
    },
    dark: {
      name: 'ダークモード',
      colors: {
        primary: '#ffa500',
        primaryHover: '#ffb733',
        secondary: '#1a1a1a',
        text: '#e0e0e0',
        subText: '#a0a0a0',
        background: '#080808',
        darkBackground: '#333333',
        border: '#ffa500',
        link: '#ffa500',
        linkHover: '#ffb733',
        success: '#66bb6a',
        warning: '#ffa726',
        error: '#ef5350',
        info: '#42a5f5',
      },
    },
    nature: {
      name: '自然テーマ',
      colors: {
        primary: '#4caf50',
        primaryHover: '#45a049',
        secondary: '#dcedc8',
        text: '#33691e',
        subText: '#558b2f',
        background: '#f1f8e9',
        darkBackground: '#33691e',
        border: '#4caf50',
        link: '#4caf50',
        linkHover: '#45a049',
        success: '#66bb6a',
        warning: '#ffa726',
        error: '#ef5350',
        info: '#42a5f5',
      },
    },
  };

  const currentColors = themes[currentTheme].colors;

  return (
    <div
      className="p-6 space-y-8"
      style={{
        backgroundColor: currentColors.background,
        color: currentColors.text,
      }}
    >
      <div className="mb-4">
        <label htmlFor="theme-select" className="mr-2">
          テーマを選択:
        </label>
        <select
          id="theme-select"
          value={currentTheme}
          onChange={(e) => setCurrentTheme(e.target.value)}
          className="p-2 border rounded"
          style={{
            backgroundColor: currentColors.background,
            color: currentColors.text,
            borderColor: currentColors.border,
          }}
        >
          {Object.keys(themes).map((theme) => (
            <option key={theme} value={theme}>
              {themes[theme].name}
            </option>
          ))}
        </select>
      </div>

      <h1
        className="text-3xl font-bold mb-6"
        style={{ color: currentColors.text }}
      >
        tetex.tech デザインシステムサンプル
      </h1>

      {/* ボタンサンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          ボタン
        </h2>
        <div className="space-x-4">
          <button
            style={{
              backgroundColor: currentColors.primary,
              color: currentColors.background,
            }}
            className="hover:opacity-80 font-bold py-2 px-4 rounded"
          >
            プライマリボタン
          </button>
          <button
            style={{
              backgroundColor: currentColors.background,
              color: currentColors.primary,
              borderColor: currentColors.primary,
            }}
            className="hover:opacity-80 font-bold py-2 px-4 rounded border"
          >
            セカンダリボタン
          </button>
        </div>
      </section>

      {/* テキストサンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          テキスト
        </h2>
        <p className="mb-2" style={{ color: currentColors.text }}>
          これは主要テキストです。
        </p>
        <p className="mb-2" style={{ color: currentColors.subText }}>
          これは副次テキストです。
        </p>
        <div
          style={{
            backgroundColor: currentColors.darkBackground,
            color: currentColors.background,
          }}
          className="p-2"
        >
          <p>これは暗い背景上の反転テキストです。</p>
        </div>
      </section>

      {/* 背景サンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          背景
        </h2>
        <div className="space-y-2">
          <div
            style={{
              backgroundColor: currentColors.background,
              borderColor: currentColors.border,
            }}
            className="p-4 border"
          >
            メイン背景
          </div>
          <div
            style={{
              backgroundColor: currentColors.secondary,
              borderColor: currentColors.border,
            }}
            className="p-4 border"
          >
            セカンダリ背景
          </div>
          <div
            style={{
              backgroundColor: currentColors.darkBackground,
              color: currentColors.background,
            }}
            className="p-4"
          >
            ダーク背景
          </div>
        </div>
      </section>

      {/* リンクサンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          リンク
        </h2>
        <a
          href="#"
          style={{ color: currentColors.link }}
          className="hover:underline"
        >
          これはリンクです
        </a>
      </section>

      {/* アラート・通知サンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          アラート・通知
        </h2>
        <div className="space-y-2">
          <div
            style={{ backgroundColor: currentColors.success }}
            className="text-white p-2 rounded"
          >
            成功メッセージ
          </div>
          <div
            style={{ backgroundColor: currentColors.warning }}
            className="text-white p-2 rounded"
          >
            警告メッセージ
          </div>
          <div
            style={{ backgroundColor: currentColors.error }}
            className="text-white p-2 rounded"
          >
            エラーメッセージ
          </div>
          <div
            style={{ backgroundColor: currentColors.info }}
            className="text-white p-2 rounded"
          >
            情報メッセージ
          </div>
        </div>
      </section>

      {/* ボーダーサンプル */}
      <section>
        <h2
          className="text-2xl font-semibold mb-4"
          style={{ color: currentColors.text }}
        >
          ボーダー
        </h2>
        <div className="space-y-2">
          <div
            style={{ borderColor: currentColors.border }}
            className="border-2 p-2"
          >
            メインボーダー
          </div>
          <div
            style={{ borderColor: currentColors.primary }}
            className="border-2 p-2"
          >
            アクセントボーダー
          </div>
        </div>
      </section>
    </div>
  );
};

export default DesignSystemSamples;
