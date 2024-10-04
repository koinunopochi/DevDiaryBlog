import React, { useEffect, useRef, useState } from 'react';
import mermaid from 'mermaid';

interface MermaidRendererProps {
  chart: string;
}

const MermaidRenderer: React.FC<MermaidRendererProps> = ({ chart }) => {
  const ref = useRef<HTMLDivElement>(null);
  const [key, setKey] = useState(0); // キーを追加して強制再レンダリングを制御

  useEffect(() => {
    mermaid.initialize({ startOnLoad: false });
    let isMounted = true;

    const renderChart = async () => {
      if (ref.current && isMounted) {
        try {
          const { svg } = await mermaid.render(`mermaid-svg-${key}`, chart);
          if (isMounted && ref.current) {
            ref.current.innerHTML = svg;
          }
        } catch (error) {
          console.error('Mermaid rendering failed:', error);
          if (isMounted && ref.current) {
            ref.current.innerHTML = 'Failed to render Mermaid diagram';
          }
        }
      }
    };

    renderChart();

    return () => {
      isMounted = false;
    };
  }, [chart, key]);

  // チャートが変更されたときにキーを更新して強制再レンダリング
  useEffect(() => {
    setKey((prevKey) => prevKey + 1);
  }, [chart]);

  return <div ref={ref} key={key} />;
};

export default MermaidRenderer;
