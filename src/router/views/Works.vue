<template>
  <div>
    <h1 class="title">WERKE</h1>
    <Gallery />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Gallery from '@/components/Gallery.vue';

export default defineComponent({
  name: 'Work',
  components: {
    Gallery,
  },
});
</script>

<style >
.title {
  text-align: center;
  font-size: 2rem;
  margin: 20px 0;
  font-weight: bold;
}
</style>
