<template>
  <div v-html="fetchedHtml"></div>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import config from '@/config'
  import axios from 'axios'

  const fetchedHtml = ref(null)
  const fetwidgetHtml = () => {
    axios.get(`${config.apiBaseUrl}/widgets/widget.php?widget=golf_tournament&wid=999&id=999`)
      .then(response => {
        fetchedHtml.value = response.data
      })
  }

  onMounted(() => {
    fetwidgetHtml()
  })

</script>
