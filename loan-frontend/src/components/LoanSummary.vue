<template>
  <div v-if="summary" class="mt-6 bg-white p-4 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Calculation Summary</h2>
    <ul class="space-y-1">
      <li v-for="(value, key) in summary" :key="key" class="flex justify-between">
        <span class="font-medium">{{ formatKey(key) }}</span>
        <span>{{ value }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: {
    summary: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatKey(key) {
      return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
    }
  }
}
</script>
