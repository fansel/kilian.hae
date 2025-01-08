declare module "*.vue" {
    import { DefineComponent } from "vue";
    const component: DefineComponent<{}, {}, any>;
    export default component;
  }
  
  //fügen Sie eine neue Deklarationsdatei (.d.ts) hinzu, die "declare module 'vue-lazy-image';" enthält.
  declare module 'vue-lazy-image';
  declare module 'vue-lazy-image-plugin';
