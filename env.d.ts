/// <reference types="vite/client" />
declare module '*.vue' {
    import { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
    export default component;
  }
  
/// <reference types="vite/client" />

interface ImportMeta {
    glob: (pattern: string, options?: { eager?: boolean }) => Record<string, any>;
  }
  