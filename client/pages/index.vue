<template>
  <div>{{ data }}</div>
</template>
<script>
  export default {
    async asyncData ({ app }) {
      const data = await app.$axios.$get('/')
      return { data }
    }
  }
</script>
