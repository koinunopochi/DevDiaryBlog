import { visit } from 'unist-util-visit';
import { h } from 'hastscript';

const remarkCustomNotes = () => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  return (tree: any) => {
    visit(tree, (node) => {
      if (
        node.type === 'containerDirective' ||
        node.type === 'leafDirective' ||
        node.type === 'textDirective'
      ) {
        // シンタックスシュガーの変換
        const nameMap: { [key: string]: string } = {
          I: 'info',
          W: 'warn',
          A: 'alert',
        };

        if (node.name in nameMap) {
          node.name = nameMap[node.name];
        }

        if (!['info', 'warn', 'alert'].includes(node.name)) return;

        const data = node.data || (node.data = {});
        const tagName = node.type === 'textDirective' ? 'span' : 'div';

        let className = 'custom-note';
        className += ` ${node.name}`;

        data.hName = tagName;
        data.hProperties = h(tagName, { className }).properties;
      }
    });
  };
};

export default remarkCustomNotes;
