<template>
  <div v-html="fetchedHtml"></div>
</template>

<script setup>
  import { ref, onMounted, defineProps } from 'vue'
  import config from '@/config'
  import axios from 'axios'

  const props = defineProps({
    name: {
      type: String,
      required: true
    },
    wid: {
      type: Number,
      required: true
    },
    id: {
      type: Number,
      required: true
    }
  })

  const fetchedHtml = ref(null)
  const fetchWidgetHtml = async () => {
    await axios.get(config.apiBaseUrl, {
      params: {
        widget: props.name,
        wid: props.wid,
        id: props.id
      }
    })
    .then(response => {
      fetchedHtml.value = response.data
    })
  }

  onMounted(() => {
    fetchWidgetHtml()
  })

</script>
