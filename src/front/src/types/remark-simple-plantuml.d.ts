declare module '@akebifiky/remark-simple-plantuml' {
  import { Plugin } from 'unified';

  interface SimplePlantUMLOptions {
    baseUrl?: string;
  }

  const simplePlantUML: Plugin<[SimplePlantUMLOptions?]>;

  export = simplePlantUML;
}
