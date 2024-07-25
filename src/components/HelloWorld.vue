<template>
  <div v-html="fetchedHtml"></div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import axios from 'axios'

  const fetchedHtml = ref(null)
  const fetwidgetHtml = () => {

  }

</script>
