import { useState, useEffect } from 'react';

const ColorCodeConverter = () => {
  const [hex, setHex] = useState('#000000');
  const [rgb, setRgb] = useState({ r: 0, g: 0, b: 0 });

  useEffect(() => {
    const hexToRgb = (hex) => {
      const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
      return result
        ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16),
          }
        : null;
    };

    const rgbValue = hexToRgb(hex);
    if (rgbValue) {
      setRgb(rgbValue);
    }
  }, [hex]);

  const handleHexChange = (e) => {
    setHex(e.target.value);
  };

  const handleRgbChange = (color, value) => {
    const newRgb = { ...rgb, [color]: parseInt(value) };
    setRgb(newRgb);
    setHex(
      `#${newRgb.r.toString(16).padStart(2, '0')}${newRgb.g.toString(16).padStart(2, '0')}${newRgb.b.toString(16).padStart(2, '0')}`
    );
  };

  return (
    <div className="w-full max-w-2xl mx-auto p-6 bg-white dark:bg-night-sky border shadow-md rounded-lg">
      <h2 className="text-2xl font-bold mb-4">カラーコード変換ツール</h2>
      <div className="space-y-4">
        <div className="flex items-center space-x-4">
          <input
            type="color"
            value={hex}
            onChange={handleHexChange}
            className="w-12 h-12"
          />
          <input
            type="text"
            value={hex}
            onChange={handleHexChange}
            className="p-2 border rounded"
          />
        </div>
        <div className="flex space-x-4">
          <input
            type="number"
            min="0"
            max="255"
            value={rgb.r}
            onChange={(e) => handleRgbChange('r', e.target.value)}
            className="p-2 border rounded w-20"
          />
          <input
            type="number"
            min="0"
            max="255"
            value={rgb.g}
            onChange={(e) => handleRgbChange('g', e.target.value)}
            className="p-2 border rounded w-20"
          />
          <input
            type="number"
            min="0"
            max="255"
            value={rgb.b}
            onChange={(e) => handleRgbChange('b', e.target.value)}
            className="p-2 border rounded w-20"
          />
        </div>
        <div className="p-2 border rounded bg-gray-100">
          RGB: rgb({rgb.r}, {rgb.g}, {rgb.b})
        </div>
        <div
          className="w-full h-20 rounded"
          style={{ backgroundColor: hex }}
        ></div>
      </div>
    </div>
  );
};

export default ColorCodeConverter;
