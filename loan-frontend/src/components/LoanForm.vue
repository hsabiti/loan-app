<template>
  <form @submit.prevent="submitLoan" class="mb-6 space-y-4">
    <div>
      <label class="block text-sm">Amount</label>
      <input v-model="amount" type="number" class="border p-2 w-full" />
    </div>
    <div>
      <label class="block text-sm">Term (months)</label>
      <input v-model="term" type="number" class="border p-2 w-full" />
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Apply</button>
  </form>
</template>

<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      amount: '',
      term: ''
    }
  },
  methods: {
    async submitLoan() {
      try {
        await axios.post('/loans', {
          amount: this.amount,
          term: this.term
        }, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })

        this.amount = ''
        this.term = ''
        this.$emit('loan-created')
      } catch (err) {
        alert('Failed to create loan')
        console.error(err)
      }
    }
  }
}
</script>
