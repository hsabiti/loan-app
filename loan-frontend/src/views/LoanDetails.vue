<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Loan Details</h1>

    <div v-if="loan" class="space-y-3 border p-4 rounded shadow bg-white">
      <p class="font-semibold">Loan: {{ loan.name || 'Unnamed Loan' }}</p>  
      <p><strong>Amount:</strong> £{{ loan.amount }}</p>
      <p><strong>Annual Interest Rate:</strong> {{ loan.annual_interest_rate * 100 }}%</p>
      <p><strong>Type:</strong> {{ loan.repayment_type }}</p>
      <p><strong>Start Date:</strong> {{ loan.start_date }}</p>
      <p><strong>End Date:</strong> {{ loan.end_date }}</p>

      <div v-if="summary" class="mt-4 border-t pt-4">
        <h2 class="font-semibold">Recalculated Summary:</h2>
        <ul class="list-disc ml-6">
          <li v-for="(val, key) in summary" :key="key">
            <strong>{{ key.replaceAll('_', ' ') }}:</strong> £{{ val }}
          </li>
        </ul>
      </div>
    </div>

    <div class="mt-6">
      <button
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        @click="recalculate"
        :disabled="!loan"
      >
        Recalculate
      </button>
      <router-link to="/dashboard" class="ml-4 text-sm text-gray-600 hover:underline">← Back</router-link>
    </div>
  </div>
</template>

<script>
import axios from '../lib/axios'

export default {
  data() {
    return {
      loan: null,
      summary: null,
    }
  },
  async created() {
    const id = this.$route.params.id
    try {
      const res = await axios.get(`/loans/${id}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      })
      this.loan = res.data
    } catch (err) {
      console.error('Failed to fetch loan', err)
      alert('Could not load loan details. Please go back and try again.')
    }
  },
  methods: {
    async recalculate() {
      if (!this.loan) return
      try {
        const res = await axios.post(
          '/loan/recalculate',
          { loan_id: this.loan.id },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem('token')}`,
            },
          }
        )
        this.summary = res.data.summary
      } catch (err) {
        console.error('Recalculation failed', err)
        alert('Recalculation failed')
      }
    },
  },
}
</script>
